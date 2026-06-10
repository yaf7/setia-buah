<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use App\Models\ProcurementTransaction;
use Illuminate\Http\Request;

class AdminProcurementController extends Controller
{
    /**
     * Show all harvest estimates pending approval.
     */
    public function pendingApproval()
    {
        $pendingEstimates = PetaniProduct::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.procurement.pending', compact('pendingEstimates'));
    }

    /**
     * Approve a harvest estimate and show procurement form.
     */
    public function approve(PetaniProduct $estimate)
    {
        if ($estimate->status !== 'pending') {
            return redirect()->route('admin.procurement.pending')
                ->with('error', 'Estimasi panen ini sudah diproses.');
        }

        $estimate->update(['status' => 'approved']);

        return redirect()->route('admin.procurement.create', $estimate)
            ->with('success', 'Estimasi panen disetujui. Silakan buat transaksi pengadaan.');
    }

    /**
     * Reject a harvest estimate.
     */
    public function reject(Request $request, PetaniProduct $estimate)
    {
        if ($estimate->status !== 'pending') {
            return redirect()->route('admin.procurement.pending')
                ->with('error', 'Estimasi panen ini sudah diproses.');
        }

        $estimate->update(['status' => 'rejected']);

        return redirect()->route('admin.procurement.pending')
            ->with('success', 'Estimasi panen ditolak.');
    }

    /**
     * Show procurement transaction form for an approved estimate.
     */
    public function create(PetaniProduct $estimate)
    {
        if (!in_array($estimate->status, ['approved'])) {
            return redirect()->route('admin.procurement.pending')
                ->with('error', 'Estimasi panen harus disetujui terlebih dahulu.');
        }

        $estimate->load('user');

        return view('admin.procurement.create', compact('estimate'));
    }

    /**
     * Store a new procurement transaction.
     */
    public function store(Request $request, PetaniProduct $estimate)
    {
        $validated = $request->validate([
            'agreed_price_per_kg' => 'required|numeric|min:100',
            'agreed_weight_kg' => 'required|numeric|min:0.1',
            'procurement_date' => 'required|date',
            'pickup_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
        ]);

        $totalCost = $validated['agreed_price_per_kg'] * $validated['agreed_weight_kg'];

        $procurement = ProcurementTransaction::create([
            'petani_product_id' => $estimate->id,
            'admin_id' => $request->user()->id,
            'procurement_number' => ProcurementTransaction::generateNumber(),
            'agreed_price_per_kg' => $validated['agreed_price_per_kg'],
            'agreed_weight_kg' => $validated['agreed_weight_kg'],
            'total_cost' => $totalCost,
            'procurement_date' => $validated['procurement_date'],
            'pickup_method' => $validated['pickup_method'],
            'status' => 'pending_pickup',
            'notes' => $validated['notes'],
        ]);

        $estimate->update(['status' => 'procurement']);

        return redirect()->route('admin.procurement.index')
            ->with('success', 'Transaksi pengadaan #' . $procurement->procurement_number . ' berhasil dibuat.');
    }

    /**
     * List all procurement transactions.
     */
    public function index()
    {
        $procurements = ProcurementTransaction::with(['harvestEstimate.user', 'admin'])
            ->latest()
            ->paginate(15);

        return view('admin.procurement.index', compact('procurements'));
    }

    /**
     * Show procurement detail.
     */
    public function show(ProcurementTransaction $procurement)
    {
        $procurement->load(['harvestEstimate.user', 'admin', 'qcReport']);
        return view('admin.procurement.show', compact('procurement'));
    }

    /**
     * Mark procurement as in-transit (pickup/delivery started).
     */
    public function markShipping(ProcurementTransaction $procurement)
    {
        if ($procurement->status !== 'pending_pickup') {
            return back()->with('error', 'Status pengadaan tidak valid untuk operasi ini.');
        }

        $procurement->update(['status' => 'in_transit']);
        $procurement->harvestEstimate->update(['status' => 'shipping']);

        return back()->with('success', 'Status diperbarui: Dalam Perjalanan.');
    }

    /**
     * Mark procurement as received at warehouse.
     */
    public function markReceived(ProcurementTransaction $procurement)
    {
        if ($procurement->status !== 'in_transit') {
            return back()->with('error', 'Status pengadaan tidak valid untuk operasi ini.');
        }

        $procurement->update(['status' => 'received']);
        $procurement->harvestEstimate->update(['status' => 'received']);

        return back()->with('success', 'Barang diterima di gudang. Silakan lanjutkan ke proses QC.');
    }
}
