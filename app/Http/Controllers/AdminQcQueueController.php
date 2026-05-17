<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use Illuminate\Http\Request;

class AdminQcQueueController extends Controller
{
    public function __invoke(Request $request)
    {
        $pendingItems = PetaniProduct::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        $pendingQC = PetaniProduct::where('status', 'pending')->count();

        return view('admin.qc.index', compact('pendingItems', 'pendingQC'));
    }
}
