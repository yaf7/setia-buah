<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\HarvestReminderService;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderStockService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('harvest:remind', function (HarvestReminderService $harvestReminderService) {
    $sentCount = $harvestReminderService->sendReminders();

    $this->info("Notifikasi panen diproses: {$sentCount}");
})->purpose('Send harvest reminders to petani users.');

Artisan::command('payment:check-unpaid', function (MidtransService $midtrans, OrderStockService $stockService) {
    $unpaidOrders = Order::where('payment_status', 'unpaid')
        ->whereNotNull('payment_reference')
        ->where('created_at', '>=', now()->subHours(24))
        ->get();

    if ($unpaidOrders->isEmpty()) {
        $this->info('Tidak ada order unpaid yang perlu dicek.');
        return;
    }

    $updated = 0;

    foreach ($unpaidOrders as $order) {
        $status = $midtrans->checkTransactionStatus($order->payment_reference);

        if (! $status) {
            continue;
        }

        $transactionStatus = $status['transaction_status'] ?? '';

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($order->payment_status !== 'paid') {
                \DB::transaction(function () use ($order, $status, $stockService) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);

                    if ($order->payment) {
                        $order->payment->update([
                            'status' => 'paid',
                            'transaction_id' => $status['transaction_id'] ?? null,
                            'payment_type' => $status['payment_type'] ?? null,
                            'paid_at' => now(),
                            'payload' => $status,
                        ]);
                    }

                    $stockService->deductForOrder($order);
                });

                $updated++;
                $this->info("Order #{$order->id} payment status updated to paid");
            }
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            if ($order->payment_status !== 'failed') {
                $order->update(['payment_status' => 'failed']);

                if ($order->payment) {
                    $order->payment->update([
                        'status' => 'failed',
                        'payload' => $status,
                    ]);
                }

                $updated++;
                $this->info("Order #{$order->id} payment status updated to failed");
            }
        }
    }

    $this->info("Pembaruan status pembayaran selesai: {$updated} order diperbarui.");
})->purpose('Check and sync payment status from Midtrans for unpaid orders.');

Schedule::command('harvest:remind')->dailyAt('08:00');
Schedule::command('payment:check-unpaid')->everyMinute();

