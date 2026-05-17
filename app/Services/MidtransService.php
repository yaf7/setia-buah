<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MidtransService
{
    public function createSnapToken(Order $order, array $customer, array $items): array
    {
        // SELALU generate order ID baru untuk menghindari duplikasi
        $midtransOrderId = $this->generateOrderId($order->id);

        $grossAmount = (int) round($order->total_amount, 0);

        // Validate amount
        if ($grossAmount <= 0) {
            throw new \RuntimeException('Jumlah pembayaran tidak valid: ' . $order->total_amount);
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $customer['name'] ?? 'Customer',
                'phone' => $customer['phone'] ?? '081234567890',
                'email' => $customer['email'] ?? 'customer@example.com',
            ],
            'item_details' => $items,
        ];

            $notificationUrl = config('services.midtrans.notification_url');
            if ($notificationUrl) {
                $payload['notification_url'] = $notificationUrl;
            }

        \Log::info('Midtrans Payload', [
            'order_id' => $midtransOrderId,
            'gross_amount' => $grossAmount,
            'server_key_prefix' => substr($this->serverKey(), 0, 15),
            'base_url' => $this->snapBaseUrl(),
            'notification_url' => $notificationUrl,
        ]);

        $response = Http::withBasicAuth($this->serverKey(), '')
            ->withHeaders(['Accept' => 'application/json'])
            ->timeout(30)
            ->post($this->snapBaseUrl().'/snap/v1/transactions', $payload);

        if (! $response->successful()) {
            $statusCode = $response->status();
            $responseBody = $response->body();
            $errorMessage = $response->json('status_message') ?? $response->json('error_id') ?? 'Unknown error';
            
            \Log::error('Midtrans API Error', [
                'status' => $statusCode,
                'message' => $errorMessage,
                'response' => $response->json(),
                'payload' => $payload,
                'order_id' => $order->id,
            ]);
            
            throw new \RuntimeException('Midtrans Error (' . $statusCode . '): ' . $errorMessage);
        }

        $token = $response->json('token');
        if (!$token) {
            \Log::error('Midtrans No Token', [
                'response' => $response->json(),
                'payload' => $payload,
                'order_id' => $order->id,
            ]);
            throw new \RuntimeException('Midtrans tidak mengembalikan token');
        }

        \Log::info('Snap Token Created', [
            'order_id' => $midtransOrderId,
            'laravel_order_id' => $order->id,
        ]);

        return [
            'order_id' => $midtransOrderId,
            'snap_token' => $token,
            'payload' => $payload,
        ];
    }

    public function verifySignature(array $payload): bool
    {
        $serverKey = $this->serverKey();
        $signature = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$serverKey);

        return hash_equals($signature, (string) ($payload['signature_key'] ?? ''));
    }

    protected function snapBaseUrl(): string
    {
        if (config('services.midtrans.is_production')) {
            return 'https://app.midtrans.com';
        }

        return 'https://app.sandbox.midtrans.com';
    }

    protected function apiBaseUrl(): string
    {
        if (config('services.midtrans.is_production')) {
            return 'https://api.midtrans.com';
        }

        return 'https://api.sandbox.midtrans.com';
    }

    protected function serverKey(): string
    {
        return (string) config('services.midtrans.server_key');
    }

    protected function generateOrderId(int $orderId): string
    {
        // Generate unique order ID dengan timestamp untuk menghindari duplikasi
        $timestamp = (int) (microtime(true) * 1000); // milliseconds
        return 'ORD-' . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT) . '-' . $timestamp;
    }

    public function checkTransactionStatus(string $orderId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->serverKey(), '')
                ->withHeaders(['Accept' => 'application/json'])
                ->timeout(30)
                ->get($this->apiBaseUrl().'/v2/'.$orderId.'/status');

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error('Midtrans Check Status Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'order_id' => $orderId,
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('Midtrans Check Status Exception', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return null;
        }
    }
}
