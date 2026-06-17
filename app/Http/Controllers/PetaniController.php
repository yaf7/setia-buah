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
        // KODE YANG DIUBAH: Menambahkan ->with('qcReports') agar data relasi ikut ditarik
        $products = PetaniProduct::where('user_id', $request->user()->id)
            ->with('qcReports')
            ->latest()
            ->paginate(10);
            
        // Count stats for dashboard
        $stats = [
            'total' => PetaniProduct::where('user_id', $request->user()->id)->count(),
            'pending' => PetaniProduct::where('user_id', $request->user()->id)->where('status', 'pending')->count(),
            'approved' => PetaniProduct::where('user_id', $request->user()->id)->where('status', 'approved')->count(),
            'in_progress' => PetaniProduct::where('user_id', $request->user()->id)->whereIn('status', ['procurement', 'shipping', 'received'])->count(),
            'completed' => PetaniProduct::where('user_id', $request->user()->id)->whereIn('status', ['qc_passed', 'cataloged'])->count(),
            'rejected' => PetaniProduct::where('user_id', $request->user()->id)->where('status', 'rejected')->count(),
        ];

        return view('petani.dashboard', compact('products', 'stats'));
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

        return redirect()->route('petani.dashboard')->with('success', 'Estimasi panen berhasil dikirim untuk persetujuan admin.');
    }

    public function edit(PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        return view('petani.edit', compact('product'));
    }

    public function update(UpdatePetaniProductRequest $request, PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        
        // Only allow editing if status is still pending
        if ($product->status !== 'pending') {
            return redirect()->route('petani.dashboard')
                ->with('error', 'Estimasi panen yang sudah diproses tidak dapat diedit.');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('petani_products', 'public');
        }

        $product->update($data);

        return redirect()->route('petani.dashboard')->with('success', 'Estimasi panen berhasil diperbarui.');
    }

    public function destroy(PetaniProduct $product)
    {
        $this->authorizeProduct($product);
        
        // Only allow deletion if status is pending or rejected
        if (!in_array($product->status, ['pending', 'rejected'])) {
            return redirect()->route('petani.dashboard')
                ->with('error', 'Estimasi panen yang sudah diproses tidak dapat dihapus.');
        }
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('petani.dashboard')->with('success', 'Estimasi panen berhasil dihapus.');
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