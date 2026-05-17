<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderStockService
{
    public function deductForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->loadMissing('orderItems.inventory');

            foreach ($order->orderItems as $item) {
                if (! $item->inventory_id || ! $item->inventory) {
                    continue;
                }

                $updated = Inventory::whereKey($item->inventory_id)
                    ->where('stock_kg', '>=', $item->quantity_kg)
                    ->decrement('stock_kg', $item->quantity_kg);

                if ($updated === 0) {
                    throw new RuntimeException('Stok produk tidak mencukupi untuk order #'.$order->id.'.');
                }
            }
        });
    }
}