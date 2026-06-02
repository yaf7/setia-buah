<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Edit Stok Hasil Tani</h1>
        
        <form action="{{ route('petani.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Buah</label>
                <input type="text" name="fruit_type" value="{{ old('fruit_type', $product->fruit_type) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-3" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estimasi Berat (Kg)</label>
                    <input type="text" inputmode="decimal" pattern="[0-9]*\.?[0-9]*" name="estimated_weight_kg" value="{{ old('estimated_weight_kg', $product->estimated_weight_kg) }}" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Kg (Rp)</label>
                    <input type="number" inputmode="numeric" name="price_per_kg" value="{{ old('price_per_kg', $product->price_per_kg) }}" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Panen</label>
                <input type="date" name="harvest_date" value="{{ old('harvest_date', $product->harvest_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Gambar Baru (Opsional, Max 2MB)</label>
                @if($product->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($product->image) }}" alt="Gambar Produk" class="h-24 w-24 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full py-2.5 text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <a href="{{ route('petani.dashboard') }}" class="py-2.5 inline-flex items-center px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="py-2.5 min-w-[44px] px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium w-full sm:w-auto">Simpan Perubahan</button>
            </div>
        </form>

        <form action="{{ route('petani.products.destroy', $product) }}" method="POST" class="mt-4 text-right" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-900 border border-red-600 p-2 rounded-lg">Hapus Stok</button>
        </form>
    </div>
</x-app-layout>
