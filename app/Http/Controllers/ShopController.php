<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::where('stock_kg', '>', 0)->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('fruit_type', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', compact('products'));
    }

    public function show(Inventory $product)
    {
        $availableGrades = Inventory::where('fruit_type', $product->fruit_type)
            ->where('is_active', true)
            ->orderBy('grade', 'asc')
            ->get();

        return view('shop.show', compact('product', 'availableGrades'));
    }
}