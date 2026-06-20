<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = Inventory::with([
            'qcReport.admin',
            'qcReport.product.user',
            'procurement.harvestEstimate.user'
        ])->orderBy('fruit_type')->orderBy('grade');
        
        if ($status === 'katalog') {
            $query->where('is_active', true);
        } elseif ($status === 'gudang') {
            $query->where('is_active', false);
        }

        $inventories = $query->get();

        // Calculate total stock specifically for the view shown
        $totalStock = $status === 'katalog' 
            ? Inventory::where('is_active', true)->sum('stock_kg')
            : ($status === 'gudang' 
                ? Inventory::where('is_active', false)->sum('stock_kg') 
                : Inventory::sum('stock_kg'));

        return view('admin.inventory.index', compact('inventories', 'totalStock', 'status'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'stock_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $inventory->update([
            'stock_kg' => $validated['stock_kg'],
            'price_per_kg' => $validated['price_per_kg'],
            'discount_percent' => $validated['discount_percent'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'Data stok berhasil diperbarui.');
    }

    public function toggleStatus(Inventory $inventory)
    {
        $inventory->update([
            'is_active' => !$inventory->is_active
        ]);

        $statusName = $inventory->is_active ? 'Katalog' : 'Gudang';
        return redirect()->back()->with('success', "Stok berhasil dipindahkan ke {$statusName}.");
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus.');
    }
}
