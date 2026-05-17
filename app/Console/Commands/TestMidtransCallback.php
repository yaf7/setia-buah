<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderStockService;
use App\Http\Controllers\MidtransCallbackController;
use Illuminate\Console\Command;

class TestMidtransCallback extends Command
{
    protected $signature = 'midtrans:test-callback {order_id=13 : Order ID to test}';
    protected $description = 'Test Midtrans callback handler dengan simulasi payload';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::find($orderId);

        if (!$order || !$order->payment_reference) {
            $this->error('❌ Order tidak ditemukan atau tidak punya payment_reference.');
            return 1;
        }

        $midtrans = app(MidtransService::class);
        $stockService = app(OrderStockService::class);

        $this->info("=== 📨 Test Midtrans Callback ===");
        $this->line("Order ID: #$orderId");
        $this->line("Payment Ref: " . $order->payment_reference);
        $this->line("Current Status: " . $order->payment_status . " / " . $order->status);
        $this->newLine();

        // Simulasi payload callback dari Midtrans
        $payload = [
            'order_id' => $order->payment_reference,
            'transaction_id' => 'txn-' . uniqid(),
            'gross_amount' => (string)$order->total_amount,
            'payment_type' => 'credit_card',
            'currency' => 'IDR',
            'transaction_status' => 'settlement',
            'status_code' => '200', // Midtrans uses status_code
            'transaction_time' => now()->subMinutes(5)->toDateTimeString(),
            'status_message' => 'The transaction has been successfully processed',
        ];

        // Generate signature sesuai format Midtrans
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);
        $payload['signature_key'] = $signatureKey;

        // Verify signature
        if (!$midtrans->verifySignature($payload)) {
            $this->error('❌ Signature verification failed');
            return 1;
        }

        $this->info('✅ Signature Valid');
        $this->newLine();
        $this->line('🔄 Processing callback...');

        try {
            $controller = app(MidtransCallbackController::class);
            $request = \Illuminate\Http\Request::create(
                '/midtrans/callback',
                'POST',
                $payload
            );

            $response = $controller->handle($request, $midtrans, $stockService);
            
            $this->info('✅ Callback Processed Successfully');
            $this->newLine();

            // Check updated order
            $updatedOrder = Order::find($orderId);
            $this->info('=== 📊 Order Status After Callback ===');
            $this->table(
                ['Property', 'Value'],
                [
                    ['Payment Status', $updatedOrder->payment_status],
                    ['Order Status', $updatedOrder->status],
                    ['Updated At', $updatedOrder->updated_at],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
