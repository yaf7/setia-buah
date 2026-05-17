<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderStockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStockServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deducts_stock_only_when_payment_is_confirmed(): void
    {
        $inventory = Inventory::create([
            'fruit_type' => 'Avokad',
            'grade' => 'A',
            'stock_kg' => 10,
            'expiry_date' => now()->addDays(7)->toDateString(),
            'price_per_kg' => 23000,
        ]);

        $order = Order::create([
            'customer_name' => 'Faishal Arrasyid',
            'total_amount' => 46000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'inventory_id' => $inventory->id,
            'quantity_kg' => 3.5,
            'price_per_kg' => 23000,
            'subtotal' => 80500,
        ]);

        $this->assertSame('10.00', Inventory::findOrFail($inventory->id)->stock_kg);

        app(OrderStockService::class)->deductForOrder($order);

        $this->assertSame('6.50', Inventory::findOrFail($inventory->id)->stock_kg);
    }
}