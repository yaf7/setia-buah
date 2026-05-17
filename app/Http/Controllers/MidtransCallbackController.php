<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderStockService;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans, OrderStockService $stockService)
    {
        $payload = $request->all();

        \Log::info('Midtrans Callback Received', [
            'order_id' => $payload['order_id'] ?? 'unknown',
            'transaction_status' => $payload['transaction_status'] ?? 'unknown',
            'signature_key' => substr($payload['signature_key'] ?? '', 0, 20),
        ]);

        if (! $midtrans->verifySignature($payload)) {
            \Log::warning('Midtrans Callback Signature Invalid', [
                'order_id' => $payload['order_id'] ?? 'unknown',
            ]);
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $order = Order::where('payment_reference', $payload['order_id'] ?? null)->first();

        if (! $order) {
            \Log::warning('Midtrans Callback Order Not Found', [
                'order_id' => $payload['order_id'] ?? 'unknown',
            ]);
            return response()->json(['message' => 'Order not found.'], 404);
        }

        \Log::info('Midtrans Callback Processing', [
            'order_id' => $order->id,
            'transaction_status' => $payload['transaction_status'] ?? '',
            'transaction_id' => $payload['transaction_id'] ?? '',
        ]);

        $payment = $order->payment;
        $transactionStatus = $payload['transaction_status'] ?? '';

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($order->payment_status !== 'paid') {
                \DB::transaction(function () use ($order, $payment, $payload, $stockService) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);

                    if ($payment) {
                        $payment->update([
                            'status' => 'paid',
                            'transaction_id' => $payload['transaction_id'] ?? null,
                            'payment_type' => $payload['payment_type'] ?? null,
                            'paid_at' => now(),
                            'payload' => $payload,
                        ]);
                    }

                    $stockService->deductForOrder($order);
                });

                \Log::info('Midtrans Callback Payment Marked Paid', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id ?? null,
                ]);
            }
        }

        if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $order->update([
                'payment_status' => 'failed',
            ]);

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'payload' => $payload,
                ]);
            }

            \Log::info('Midtrans Callback Payment Failed', [
                'order_id' => $order->id,
                'transaction_status' => $transactionStatus,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
