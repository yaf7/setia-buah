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
        $admin_id = $request->user()->id;
        $procurement_id = $product->procurement?->id;

        if ($data['status'] === 'accepted') {
            $grades = [
                'A' => ['weight' => $data['weight_a'] ?? 0, 'price' => $data['price_a'] ?? 0],
                'B' => ['weight' => $data['weight_b'] ?? 0, 'price' => $data['price_b'] ?? 0],
                'C' => ['weight' => $data['weight_c'] ?? 0, 'price' => $data['price_c'] ?? 0],
            ];

            $total_actual_weight = 0;
            $reports_created = 0;
            
            // Loop through each grade and create records if weight > 0
            foreach ($grades as $grade => $info) {
                if ($info['weight'] > 0) {
                    $total_actual_weight += $info['weight'];
                    
                    // Put rejected weight only on the first report to avoid duplication
                    $rejected_weight = ($reports_created === 0) ? ($data['rejected_weight_kg'] ?? 0) : 0;
                    
                    $qcReport = QcReport::create([
                        'petani_product_id' => $product->id,
                        'procurement_id' => $procurement_id,
                        'admin_id' => $admin_id,
                        'actual_weight_kg' => $info['weight'],
                        'rejected_weight_kg' => $rejected_weight,
                        'final_grade' => $grade,
                        'final_price_per_kg' => $info['price'],
                        'status' => 'accepted',
                        'notes' => $data['notes'] ?? null,
                    ]);

                    $batchNumber = 'BTH-' . now()->format('Ymd') . '-' . str_pad($qcReport->id, 4, '0', STR_PAD_LEFT);
                    
                    Inventory::create([
                        'qc_report_id' => $qcReport->id,
                        'procurement_id' => $procurement_id,
                        'batch_number' => $batchNumber,
                        'fruit_type' => $product->fruit_type,
                        'grade' => $grade,
                        'stock_kg' => $info['weight'],
                        'expiry_date' => Carbon::parse($product->harvest_date)->addDays(14),
                        'price_per_kg' => $info['price'],
                        'image' => $product->image,
                        'is_active' => $data['inventory_status'] === 'catalog',
                    ]);
                    
                    $reports_created++;
                }
            }
            
            // If no weight was entered but it was accepted, we might just have rejected weight
            if ($reports_created === 0 && ($data['rejected_weight_kg'] ?? 0) > 0) {
                 QcReport::create([
                        'petani_product_id' => $product->id,
                        'procurement_id' => $procurement_id,
                        'admin_id' => $admin_id,
                        'actual_weight_kg' => 0,
                        'rejected_weight_kg' => $data['rejected_weight_kg'],
                        'final_grade' => null,
                        'final_price_per_kg' => 0,
                        'status' => 'accepted',
                        'notes' => $data['notes'] ?? null,
                ]);
            }

            // Update product status
            $product->update([
                'status' => $data['inventory_status'] === 'catalog' ? 'cataloged' : 'qc_passed',
                'grade' => 'MIXED' // Since it can be split into multiple grades
            ]);
        } else {
            // Rejected
            QcReport::create([
                'petani_product_id' => $product->id,
                'procurement_id' => $procurement_id,
                'admin_id' => $admin_id,
                'actual_weight_kg' => 0,
                'rejected_weight_kg' => $data['rejected_weight_kg'] ?? 0,
                'final_grade' => null,
                'final_price_per_kg' => 0,
                'status' => 'rejected',
                'notes' => $data['notes'] ?? null,
            ]);
            $product->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Proses QC berhasil diselesaikan.');
    }
}