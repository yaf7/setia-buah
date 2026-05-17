<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Shipment;
use App\Services\BiteshipService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateShipmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $orderId)
    {
    }

    public function handle(BiteshipService $biteship): void
    {
        $order = Order::with('orderItems.inventory')->findOrFail($this->orderId);

        if ($order->shipment) {
            return;
        }

        $items = $order->orderItems->map(function ($item) {
            $weightGram = (int) round($item->quantity_kg * 1000);

            return [
                'name' => $item->inventory->fruit_type ?? 'Produk',
                'quantity' => (int) max(1, round($item->quantity_kg, 0)),
                'weight' => max(1, $weightGram),
                'value' => (int) round($item->subtotal, 0),
            ];
        })->values()->all();

        $response = $biteship->createShipment($order, $items);
        $data = $response['data'] ?? [];

        $shipment = Shipment::create([
            'order_id' => $order->id,
            'shipment_id' => $data['id'] ?? null,
            'courier_name' => $order->courier_name,
            'courier_service' => $order->courier_service,
            'tracking_number' => $data['courier']['tracking_id'] ?? $data['tracking_id'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'estimated_delivery' => $data['expected_delivery_date'] ?? $data['delivery_date'] ?? null,
            'payload' => $response,
        ]);

        $order->update([
            'tracking_number' => $shipment->tracking_number,
            'status' => 'shipped',
        ]);
    }
}
