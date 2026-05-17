<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;
use App\Services\BiteshipService;
use App\Services\MidtransService;

class CheckoutController extends Controller
{
    public function index()
    {
        $sessionId = Session::getId();
        $query = Cart::with('inventory');
        
        if (auth('buyer')->check()) {
            $query->where('user_id', auth('buyer')->id());
        } else {
            $query->where('session_id', $sessionId);
        }

        $cartItems = $query->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity_kg * optional($item->inventory)->price_per_kg;
        });

        return view('shop.checkout', compact('cartItems', 'total'));
    }

    public function shippingRates(Request $request, BiteshipService $biteship)
    {
        if (! config('services.biteship.api_key')) {
            return response()->json(['message' => 'BITESHIP_API_KEY belum diisi.'], 500);
        }

        if (! config('services.biteship.origin_postal_code')) {
            return response()->json(['message' => 'BITESHIP_ORIGIN_POSTAL_CODE belum diisi.'], 500);
        }

        $data = $request->validate([
            'shipping_postal_code' => ['required', 'string', 'max:10'],
            'couriers' => ['nullable', 'string'],
        ]);

        $sessionId = Session::getId();
        $query = Cart::with('inventory');

        if (auth('buyer')->check()) {
            $query->where('user_id', auth('buyer')->id());
        } else {
            $query->where('session_id', $sessionId);
        }

        $cartItems = $query->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong.'], 422);
        }

        $items = $cartItems->map(function ($item) {
            $weightGram = (int) round($item->quantity_kg * 1000);

            return [
                'name' => $item->inventory->fruit_type ?? 'Produk',
                'quantity' => (int) max(1, round($item->quantity_kg, 0)),
                'weight' => max(1, $weightGram),
                'value' => (int) round($item->quantity_kg * ($item->inventory->price_per_kg ?? 0), 0),
            ];
        })->values()->all();

        $payload = [
            'origin_postal_code' => config('services.biteship.origin_postal_code'),
            'destination_postal_code' => $data['shipping_postal_code'],
            'couriers' => $data['couriers'] ?? 'jne,jnt,sicepat,anteraja,ninja,tiki,pos,lion',
            'items' => $items,
        ];

        try {
            $rates = $biteship->getRates($payload);

            return response()->json($rates);
        } catch (ConnectionException $e) {
            return response()->json([
                'message' => 'Gagal terhubung ke Biteship. Cek koneksi internet/DNS Anda.',
            ], 503);
        } catch (\RuntimeException $e) {
            $message = $e->getMessage();

            if (str_contains(strtolower($message), 'no sufficient balance')) {
                return response()->json([
                    'message' => 'Saldo akun Biteship tidak cukup untuk cek ongkir. Silakan top up saldo Biteship lalu coba lagi.',
                    'provider_message' => $message,
                ], 422);
            }

            return response()->json(['message' => $message], 500);
        }
    }

    public function store(CheckoutRequest $request, MidtransService $midtrans)
    {
        $data = $request->validated();
        
        if (! config('services.midtrans.server_key')) {
            return response()->json(['message' => 'MIDTRANS_SERVER_KEY belum diisi.'], 500);
        }

        if (! config('services.midtrans.client_key')) {
            return response()->json(['message' => 'MIDTRANS_CLIENT_KEY belum diisi.'], 500);
        }
        
        $sessionId = Session::getId();
        $userId = auth('buyer')->id();
        
        $query = Cart::with('inventory');
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Keranjang kosong.');
        }

        $subtotalAmount = $cartItems->sum(function ($item) {
            return $item->quantity_kg * optional($item->inventory)->price_per_kg;
        });

        $shippingCost = (float) $data['shipping_cost'];
        $grandTotal = $subtotalAmount + $shippingCost;

        try {
            [$order, $payment] = DB::transaction(function () use ($data, $userId, $sessionId, $subtotalAmount, $shippingCost, $grandTotal, $cartItems) {
                $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'shipping_province' => $data['shipping_province'],
                'shipping_city' => $data['shipping_city'],
                'shipping_postal_code' => $data['shipping_postal_code'],
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'subtotal_amount' => $subtotalAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $grandTotal,
                'courier_name' => $data['courier_name'],
                'courier_service' => $data['courier_service'],
                'payment_reference' => $this->makePaymentReference(),
                ]);

                foreach ($cartItems as $item) {
                    if ($item->inventory) {
                        $subtotal = $item->quantity_kg * $item->inventory->price_per_kg;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'inventory_id' => $item->inventory_id,
                            'quantity_kg' => $item->quantity_kg,
                            'price_per_kg' => $item->inventory->price_per_kg,
                            'subtotal' => $subtotal,
                        ]);
                    }
                }
                // Bersihkan keranjang
                if ($userId) {
                    Cart::where('user_id', $userId)->delete();
                } else {
                    Cart::where('session_id', $sessionId)->delete();
                }

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'midtrans',
                    'status' => 'pending',
                ]);

                return [$order, $payment];
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat pesanan: '.$e->getMessage(),
            ], 500);
        }

        try {
            $itemDetails = $cartItems->map(function ($item) {
                $subtotal = $item->quantity_kg * ($item->inventory->price_per_kg ?? 0);

                return [
                    'id' => 'INV-'.$item->inventory_id,
                    'price' => (int) round($subtotal, 0),
                    'quantity' => 1,
                    'name' => ($item->inventory->fruit_type ?? 'Produk').' ('.rtrim(rtrim(number_format($item->quantity_kg, 2, '.', ''), '0'), '.').' Kg)',
                ];
            })->values()->all();

            $itemDetails[] = [
                'id' => 'SHIP-'.$order->id,
                'price' => (int) round($shippingCost, 0),
                'quantity' => 1,
                'name' => 'Ongkir '.$data['courier_name'].' '.$data['courier_service'],
            ];

            $customer = [
                'name' => $order->customer_name,
                'phone' => $order->customer_phone,
                'email' => auth('buyer')->user()->email ?? null,
            ];

            $snap = $midtrans->createSnapToken($order, $customer, $itemDetails);

            $order->update([
                'payment_reference' => $snap['order_id'],
            ]);

            $payment->update([
                'snap_token' => $snap['snap_token'],
                'payload' => $snap['payload'],
            ]);

            return response()->json([
                'snap_token' => $snap['snap_token'],
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat pesanan: '.$e->getMessage(),
            ], 500);
        }
    }

    protected function makePaymentReference(): string
    {
        return 'ORD-'.Str::upper(Str::random(12));
    }
}