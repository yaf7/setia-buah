<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = Session::getId();
        $query = Cart::with('inventory');
        
        if (auth('buyer')->check()) {
            $query->where('user_id', auth('buyer')->id());
        } else {
            $query->where('session_id', $sessionId);
        }

        $cartItems = $query->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity_kg * optional($item->inventory)->price_per_kg;
        });

        return view('shop.cart', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity_kg' => 'required|numeric|min:0.5',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);
        
        if ($inventory->stock_kg < $request->quantity_kg) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $sessionId = Session::getId();
        $userId = auth('buyer')->id();

        $cart = Cart::firstOrNew([
            'inventory_id' => $request->inventory_id,
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
        ]);

        $cart->quantity_kg += $request->quantity_kg;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}