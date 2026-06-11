<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderStockService;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    private function allowedNextStatuses(Order $order): array
    {
        return match ($order->status) {
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            default => [],
        };
    }

    public function index(Request $request)
    {
        $query = Order::query();

        // Remove payment_status filter so admin can see unpaid orders to verify them manually
        // $query->where('payment_status', 'paid');

        // if a specific status is requested, apply it (e.g., ?status=shipped)
        if ($request->filled('status')) {
            $status = $request->query('status');
            $query->where('status', $status);
        } else {
            // default: only show orders that are pending or processing (exclude shipped)
            $query->whereIn('status', ['pending', 'processing']);
        }

        $orders = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function history(Request $request)
    {
        $query = Order::query();

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
            // Anda bisa menambahkan filter tahun jika diperlukan
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->year);
            } else {
                $query->whereYear('created_at', now()->year);
            }
        }

        $orders = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        $items = $order->orderItems()->with('inventory')->get();
        
        return view('admin.orders.show', compact('order', 'items'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $allowedStatuses = $this->allowedNextStatuses($order);

        if ($allowedStatuses === []) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Status pesanan ini sudah final dan tidak bisa diubah lagi.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', $allowedStatuses)],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diubah menjadi: '.$validated['status']);
    }

    public function approve(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Hanya pesanan pending yang bisa disetujui ke proses.');
        }

        $order->update(['status' => 'processing']);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan telah disetujui dan sedang diproses.');
    }

    public function reject(Order $order)
    {
        if (! in_array($order->status, ['pending', 'processing'], true)) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Pesanan yang sudah dikirim atau selesai tidak bisa dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan telah dibatalkan.');
    }

    public function markShipped(Order $order)
    {
        if ($order->status !== 'processing') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Nomor resi hanya bisa diinput untuk pesanan yang sedang diproses.');
        }

        $validated = request()->validate([
            'tracking_number' => ['required', 'string'],
        ]);

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $validated['tracking_number'],
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan telah dikirim dengan nomor resi: '.$validated['tracking_number']);
    }

    public function markPaymentSuccess(Order $order, OrderStockService $stockService)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('admin.orders.show', $order)
                ->with('info', 'Pembayaran pesanan ini sudah ditandai berhasil sebelumnya.');
        }

        \DB::transaction(function () use ($order, $stockService) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            $stockService->deductForOrder($order);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pembayaran pesanan telah ditandai berhasil.');
    }

    public function checkPaymentStatus(Order $order, MidtransService $midtrans, OrderStockService $stockService)
    {
        if (! $order->payment_reference) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order tidak memiliki referensi pembayaran.');
        }

        $status = $midtrans->checkTransactionStatus($order->payment_reference);

        if (! $status) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Gagal mengecek status pembayaran ke Midtrans.');
        }

        $transactionStatus = $status['transaction_status'] ?? '';

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($order->payment_status !== 'paid') {
                \DB::transaction(function () use ($order, $status, $stockService) {
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

                return redirect()->route('admin.orders.show', $order)
                    ->with('success', 'Pembayaran berhasil dikonfirmasi dari Midtrans dan status pesanan diperbarui.');
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('info', 'Status pembayaran di Midtrans adalah settlement/berhasil, tapi sistem sudah mencatatnya.');
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('warning', 'Status pembayaran di Midtrans: '.$transactionStatus.'. Pembayaran mungkin belum berhasil atau masih proses.');
    }
}
