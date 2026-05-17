<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminDashboardController;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminDashboardOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_paid_pending_and_processing_orders_for_shipping_workflow(): void
    {
        Order::create([
            'customer_name' => 'Pending Buyer',
            'total_amount' => 10000,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        Order::create([
            'customer_name' => 'Processing Buyer',
            'total_amount' => 20000,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);

        Order::create([
            'customer_name' => 'Delivered Buyer',
            'total_amount' => 30000,
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);

        $response = app(AdminDashboardController::class)(new Request());
        $paidOrders = collect($response->getData()['paidOrders']);

        $this->assertSame(2, $paidOrders->count());
        $this->assertTrue($paidOrders->contains(fn ($order) => $order->status === 'pending'));
        $this->assertTrue($paidOrders->contains(fn ($order) => $order->status === 'processing'));
        $this->assertFalse($paidOrders->contains(fn ($order) => $order->status === 'delivered'));
        $this->assertSame(2, $response->getData()['totalPaidOrders']);
    }

    public function test_it_counts_only_unpaid_orders_in_today_summary(): void
    {
        Order::create([
            'customer_name' => 'Unpaid Today',
            'total_amount' => 10000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        Order::create([
            'customer_name' => 'Paid Today',
            'total_amount' => 20000,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);

        $response = app(AdminDashboardController::class)(new Request());

        $this->assertSame(1, $response->getData()['ordersToday']);
    }
}