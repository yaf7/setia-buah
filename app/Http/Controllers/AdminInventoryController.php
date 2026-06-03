<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::orderBy('fruit_type')
            ->orderBy('grade')
            ->get();

        $totalStock = Inventory::sum('stock_kg');

        return view('admin.inventory.index', compact('inventories', 'totalStock'));
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Stok berhasil dihapus dari gudang.');
    }
}
