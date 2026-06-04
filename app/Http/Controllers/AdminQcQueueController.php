<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use Illuminate\Http\Request;

class AdminQcQueueController extends Controller
{
    public function __invoke(Request $request)
    {
        // QC queue now shows items that are received at warehouse
        $pendingItems = PetaniProduct::with(['user', 'procurement'])
            ->where('status', 'received')
            ->latest()
            ->paginate(10);

        $pendingQC = PetaniProduct::where('status', 'received')->count();

        return view('admin.qc.index', compact('pendingItems', 'pendingQC'));
    }
}
