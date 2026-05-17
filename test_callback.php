<?php

require 'bootstrap/app.php';
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Services\MidtransService;

$orderId = 13; // Order ID yang ingin di-test

$order = Order::find($orderId);
if (!$order || !$order->payment_reference) {
    echo "Order tidak ditemukan atau tidak punya payment_reference.\n";
    exit;
}

$midtrans = app(MidtransService::class);

// Simulasi payload callback dari Midtrans
$payload = [
    'order_id' => $order->payment_reference,
    'transaction_id' => 'a3f14a4c-3031-4f6c-9aea-5cb1a5368572',
    'gross_amount' => (string)$order->total_amount,
    'payment_type' => 'credit_card',
    'currency' => 'IDR',
    'transaction_status' => 'settlement', // settlement = berhasil bayar
    'transaction_time' => now()->subMinutes(5)->toDateTimeString(),
    'status_message' => 'The transaction has been successfully processed',
];

// Generate signature
$serverKey = config('services.midtrans.server_key');
$signatureKey = hash('sha512', $order->payment_reference . $payload['status_message'] . $payload['gross_amount'] . $serverKey);
$payload['signature_key'] = $signatureKey;

echo "=== Test Callback Payload ===\n";
echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Verify signature
if ($midtrans->verifySignature($payload)) {
    echo "✓ Signature Valid\n";
} else {
    echo "✗ Signature Invalid\n";
    exit;
}

// Simulate the callback
echo "\nSimulating Midtrans Callback Handler...\n";

$controller = app(\App\Http\Controllers\MidtransCallbackController::class);
$request = \Illuminate\Http\Request::create(
    '/midtrans/callback',
    'POST',
    $payload
);

try {
    $response = $controller->handle($request, $midtrans, app(\App\Services\OrderStockService::class));
    echo "\n✓ Callback Processed Successfully\n";
    echo "Response: " . $response->getContent() . "\n";
    
    // Check updated order status
    $updatedOrder = Order::find($orderId);
    echo "\n=== Order Status After Callback ===\n";
    echo "Payment Status: " . $updatedOrder->payment_status . "\n";
    echo "Order Status: " . $updatedOrder->status . "\n";
    
} catch (\Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
