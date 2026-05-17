<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function track(Order $order)
    {
        // Simple security: Hanya user pemilik order atau guest yang punya session tracking (dalam case real, kita set token).
        // Untuk contoh ini kita loosly izinkan melihat jika tau ID nya
        $order->load('orderItems.inventory', 'shipment', 'payment');
        
        return view('shop.track', compact('order'));
    }
}