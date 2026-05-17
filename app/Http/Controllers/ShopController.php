<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::where('stock_kg', '>', 0);

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
        return view('shop.show', compact('product'));
    }
}