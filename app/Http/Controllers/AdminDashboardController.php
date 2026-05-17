<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\QcReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Ringkasan: pesanan yang sedang dalam pengiriman
        $ordersToday = Order::where('status', 'shipped')->count();
        
        // Stok Gudang
        $totalStock = Inventory::sum('stock_kg');
        
        // Alert Kadaluarsa (< 3 hari)
        $expiringStock = Inventory::where('expiry_date', '<=', Carbon::today()->addDays(3))
                                  ->where('expiry_date', '>=', Carbon::today())
                                  ->count();
                                  
        // Margin Asumsi (Selisih Harga Jual dan Beli - Mock Data for Dashboard)
        $margin = 1500000; 
        
        // Tugas Pending (Produk yang butuh QC)
        $pendingQC = \App\Models\PetaniProduct::where('status', 'pending')->count();

        // Daftar Petani
        $petanis = \App\Models\User::where('role', 'petani')->latest()->get();

        // Titik koordinat petani
        $petaniLocations = \App\Models\User::where('role', 'petani')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'latitude', 'longitude']);

        // Pesanan yang sudah dibayar dan masih perlu diproses/dikirim
        $paidOrders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalPaidOrders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return view('admin.dashboard', compact(
            'ordersToday', 'totalStock', 'expiringStock', 'margin', 'pendingQC', 'petanis', 'petaniLocations',
            'paidOrders', 'totalPaidOrders'
        ));
    }
}