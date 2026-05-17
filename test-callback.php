#!/usr/bin/env php
<?php

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderStockService;
use App\Http\Controllers\MidtransCallbackController;

$orderId = $argv[1] ?? 13;

$order = Order::find($orderId);
if (!$order || !$order->payment_reference) {
    echo "❌ Order tidak ditemukan atau tidak punya payment_reference.\n";
    exit(1);
}

$midtrans = app(MidtransService::class);
$stockService = app(OrderStockService::class);

// Simulasi payload callback dari Midtrans
$payload = [
    'order_id' => $order->payment_reference,
    'transaction_id' => 'txn-' . uniqid(),
    'gross_amount' => (string)$order->total_amount,
    'payment_type' => 'credit_card',
    'currency' => 'IDR',
    'transaction_status' => 'settlement',
    'transaction_time' => now()->subMinutes(5)->toDateTimeString(),
    'status_message' => 'The transaction has been successfully processed',
];

// Generate signature
$serverKey = config('services.midtrans.server_key');
$signatureKey = hash('sha512', $order->payment_reference . $payload['status_message'] . $payload['gross_amount'] . $serverKey);
$payload['signature_key'] = $signatureKey;

echo "=== 📨 Test Midtrans Callback ===\n";
echo "Order ID: #" . $order->id . "\n";
echo "Payment Ref: " . $order->payment_reference . "\n";
echo "Transaction ID: " . $payload['transaction_id'] . "\n";
echo "Status: " . $payload['transaction_status'] . "\n\n";

// Verify signature
if ($midtrans->verifySignature($payload)) {
    echo "✅ Signature Valid\n\n";
} else {
    echo "❌ Signature Invalid\n";
    exit(1);
}

// Process callback
echo "🔄 Processing callback...\n\n";

$controller = app(MidtransCallbackController::class);
$request = \Illuminate\Http\Request::create(
    '/midtrans/callback',
    'POST',
    $payload
);

try {
    $response = $controller->handle($request, $midtrans, $stockService);
    echo "✅ Callback Processed\n";
    
    // Check updated order
    $updatedOrder = Order::find($orderId);
    echo "\n=== 📊 Order Status After Callback ===\n";
    echo "Payment Status: " . $updatedOrder->payment_status . "\n";
    echo "Order Status: " . $updatedOrder->status . "\n";
    echo "Updated At: " . $updatedOrder->updated_at . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
