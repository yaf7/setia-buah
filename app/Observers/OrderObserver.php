<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->logActivity('created', $order, $order->getAttributes());
    }

    public function updated(Order $order): void
    {
        $this->logActivity('updated', $order, $order->getChanges());
    }

    public function deleted(Order $order): void
    {
        $this->logActivity('deleted', $order, $order->getAttributes());
    }

    private function logActivity(string $action, Order $order, array $changes): void
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => Order::class,
                'model_id' => $order->id,
                'changes' => $changes,
            ]);
        }
    }
}