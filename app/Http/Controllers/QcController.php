<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use App\Models\ProcurementTransaction;
use App\Models\QcReport;
use App\Models\Inventory;
use App\Http\Requests\StoreQcReportRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QcController extends Controller
{
    public function create(PetaniProduct $product)
    {
        // QC only allowed for items received at warehouse
        if ($product->status !== 'received') {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Barang harus sudah diterima di gudang sebelum proses QC.');
        }

        $product->load(['user', 'procurement']);

        return view('admin.qc.create', compact('product'));
    }

    public function store(StoreQcReportRequest $request, PetaniProduct $product)
    {
        if ($product->status !== 'received') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Barang harus sudah diterima di gudang sebelum proses QC.');
        }

        $data = $request->validated();
        $data['admin_id'] = $request->user()->id;
        $data['procurement_id'] = $product->procurement?->id;

        $qcReport = QcReport::create($data);

        if ($data['status'] === 'accepted') {
            // Update product status to QC passed
            $product->update([
                'status' => 'qc_passed',
                'grade' => $data['final_grade'] ?? null
            ]);

            // Automatically create inventory (Katalog + Inventory)
            $batchNumber = 'BTH-' . now()->format('Ymd') . '-' . str_pad($qcReport->id, 4, '0', STR_PAD_LEFT);
            
            Inventory::create([
                'qc_report_id' => $qcReport->id,
                'procurement_id' => $product->procurement?->id,
                'batch_number' => $batchNumber,
                'fruit_type' => $product->fruit_type,
                'grade' => $data['final_grade'],
                'stock_kg' => $data['actual_weight_kg'],
                'expiry_date' => Carbon::parse($product->harvest_date)->addDays(14),
                'price_per_kg' => $data['final_price_per_kg'],
                'image' => $product->image,
            ]);

            // Update status to cataloged (ready for sale)
            $product->update(['status' => 'cataloged']);
        } else {
            $product->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Proses QC berhasil diselesaikan.');
    }
}