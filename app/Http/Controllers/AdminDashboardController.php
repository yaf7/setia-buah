<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\PetaniProduct;
use App\Models\ProcurementTransaction;
use App\Models\QcReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Supply Chain Pipeline Metrics
        $pendingEstimates = PetaniProduct::where('status', 'pending')->count();
        $approvedEstimates = PetaniProduct::where('status', 'approved')->count();
        $activeProcurements = ProcurementTransaction::whereIn('status', ['pending_pickup', 'in_transit'])->count();
        $receivedAtWarehouse = PetaniProduct::where('status', 'received')->count();
        $totalStock = Inventory::sum('stock_kg');
        
        // Pesanan yang sedang dalam pengiriman
        $ordersShipped = Order::where('status', 'shipped')->count();
        
        // Alert Kadaluarsa (< 3 hari)
        $expiringStock = Inventory::where('expiry_date', '<=', Carbon::today()->addDays(3))
                                  ->where('expiry_date', '>=', Carbon::today())
                                  ->count();

        // Pesanan yang sudah dibayar dan masih perlu diproses/dikirim
        $paidOrders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalPaidOrders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        // Recent supply chain activity for timeline
        $recentEstimates = PetaniProduct::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingEstimates', 'approvedEstimates', 'activeProcurements',
            'receivedAtWarehouse', 'totalStock', 'ordersShipped', 'expiringStock',
            'paidOrders', 'totalPaidOrders',
            'recentEstimates'
        ));
    }
}