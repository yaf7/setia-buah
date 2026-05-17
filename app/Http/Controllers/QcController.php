<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use App\Models\QcReport;
use App\Models\Inventory;
use App\Http\Requests\StoreQcReportRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QcController extends Controller
{
    public function create(PetaniProduct $product)
    {
        return view('admin.qc.create', compact('product'));
    }

    public function store(StoreQcReportRequest $request, PetaniProduct $product)
    {
        $data = $request->validated();
        $data['admin_id'] = $request->user()->id;

        $qcReport = QcReport::create($data);

        // Update product status
        $product->update([
            'status' => $data['status']
        ]);

        // Jika diterima, masukkan ke Inventory Stok Gudang
        if ($data['status'] === 'accepted') {
            Inventory::create([
                'fruit_type' => $product->fruit_type,
                'grade' => $data['final_grade'],
                'stock_kg' => $data['actual_weight_kg'],
                'expiry_date' => Carbon::parse($product->harvest_date)->addDays(14), // Asumsi 14 hari
                'price_per_kg' => $product->price_per_kg,
                'image' => $product->image,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'QC berhasil disimpan.');
    }
}