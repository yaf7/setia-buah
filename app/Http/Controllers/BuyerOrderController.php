<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerOrderController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('buyer')->user();
        
        if (!$user) {
            return redirect()->route('buyer.login');
        }

        $orders = $user->orders()
            ->with('orderItems.inventory', 'shipment', 'payment')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('shop.dashboard', compact('orders'));
    }

    public function track(Order $order, MidtransService $midtrans, OrderStockService $stockService)
    {
        if ($order->payment_status !== 'paid' && $order->payment_reference) {
            $status = $midtrans->checkTransactionStatus($order->payment_reference);
            \Log::info('Midtrans Manual Check', ['order_id' => $order->id, 'payment_reference' => $order->payment_reference, 'midtrans_status' => $status]);
            if ($status) {
                $transactionStatus = $status['transaction_status'] ?? '';
                if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
                    DB::transaction(function () use ($order, $status, $stockService) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                        ]);
                        if ($order->payment) {
                            $order->payment->update([
                                'status' => 'paid',
                                'transaction_id' => $status['transaction_id'] ?? null,
                                'payment_type' => $status['payment_type'] ?? null,
                                'paid_at' => now(),
                                'payload' => $status,
                            ]);
                        }
                        $stockService->deductForOrder($order);
                    });
                } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
                    $order->update(['payment_status' => 'failed']);
                    if ($order->payment) {
                        $order->payment->update(['status' => 'failed', 'payload' => $status]);
                    }
                }
            }
        }

        // Simple security: Hanya user pemilik order atau guest yang punya session tracking (dalam case real, kita set token).
        // Untuk contoh ini kita loosly izinkan melihat jika tau ID nya
        $order->load('orderItems.inventory', 'shipment', 'payment');
        
        return view('shop.track', compact('order'));
    }
}