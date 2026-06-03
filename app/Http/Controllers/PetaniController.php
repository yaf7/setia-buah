<?php

namespace App\Http\Controllers;

use App\Models\PetaniProduct;
use App\Http\Requests\StorePetaniProductRequest;
use App\Http\Requests\UpdatePetaniProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetaniController extends Controller
{
    public function dashboard(Request $request)
    {
        $products = PetaniProduct::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
            
        return view('petani.dashboard', compact('products'));
    }

    public function create()
    {
        return view('petani.create');
    }

    public function store(StorePetaniProductRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('petani_products', 'public');
        }

        PetaniProduct::create($data);

        return redirect()->route('petani.dashboard')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        return view('petani.edit', compact('product'));
    }

    public function update(UpdatePetaniProductRequest $request, PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('petani_products', 'public');
        }

        $product->update($data);

        return redirect()->route('petani.dashboard')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        
        // Hapus juga data tersebut di Katalog Toko (Inventory Gudang) jika sebelumnya sudah diterima (accepted)
        if ($product->status === 'accepted') {
            \App\Models\Inventory::where('fruit_type', $product->fruit_type)
                ->where('grade', $product->grade)
                ->where('price_per_kg', $product->price_per_kg)
                ->delete();
        }
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('petani.dashboard')->with('success', 'Produk berhasil dihapus dari sistem Petani maupun Toko.');
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'coordinates' => 'required|string',
        ]);

        $parts = array_map('trim', explode(',', $validated['coordinates']));
        if (count($parts) !== 2) {
            return back()->withErrors(['coordinates' => 'Format koordinat tidak valid. Gunakan "lat, long".'])->withInput();
        }

        $latitude = filter_var($parts[0], FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($parts[1], FILTER_VALIDATE_FLOAT);

        if ($latitude === false || $longitude === false || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return back()->withErrors(['coordinates' => 'Koordinat harus berupa angka valid.'])->withInput();
        }

        $request->user()->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return redirect()->route('petani.dashboard')->with('success', 'Koordinat lahan berhasil disimpan.');
    }

    private function authorizeProduct(PetaniProduct $product): void
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
