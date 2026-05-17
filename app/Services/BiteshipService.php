<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class BiteshipService
{
    public function getRates(array $payload): array
    {
        $response = Http::withToken($this->apiKey())
            ->withHeaders(['Accept' => 'application/json'])
            ->timeout(20)
            ->post($this->baseUrl().'/v1/rates/couriers', $payload);

        if (! $response->successful()) {
            $message = $response->json('message')
                ?? $response->json('error')
                ?? data_get($response->json('errors'), '0.message')
                ?? 'Gagal mengambil ongkir.';
            throw new \RuntimeException($message.' (HTTP '.$response->status().')');
        }

        return $response->json();
    }

    public function createShipment(Order $order, array $items): array
    {
        $payload = [
            'origin_contact_name' => config('services.biteship.origin_contact_name'),
            'origin_contact_phone' => config('services.biteship.origin_contact_phone'),
            'origin_address' => config('services.biteship.origin_address'),
            'origin_postal_code' => config('services.biteship.origin_postal_code'),
            'origin_province' => config('services.biteship.origin_province'),
            'origin_city' => config('services.biteship.origin_city'),
            'destination_contact_name' => $order->customer_name,
            'destination_contact_phone' => $order->customer_phone,
            'destination_address' => $order->shipping_address,
            'destination_postal_code' => $order->shipping_postal_code,
            'destination_province' => $order->shipping_province,
            'destination_city' => $order->shipping_city,
            'courier_company' => $order->courier_name,
            'courier_type' => $order->courier_service,
            'delivery_type' => 'now',
            'items' => $items,
        ];

        $response = Http::withToken($this->apiKey())
            ->withHeaders(['Accept' => 'application/json'])
            ->timeout(20)
            ->post($this->baseUrl().'/v1/shipments', $payload);

        if (! $response->successful()) {
            $message = $response->json('message')
                ?? $response->json('error')
                ?? data_get($response->json('errors'), '0.message')
                ?? 'Gagal membuat pengiriman.';
            throw new \RuntimeException($message.' (HTTP '.$response->status().')');
        }

        return $response->json();
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('services.biteship.base_url'), '/');
    }

    protected function apiKey(): string
    {
        return (string) config('services.biteship.api_key');
    }
}
