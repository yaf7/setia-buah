<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">Edit Estimasi</span>
            <h1 class="text-2xl font-heading font-extrabold text-gray-800 mt-2">Edit Estimasi Panen</h1>
            <p class="text-xs text-gray-500 mt-1">Perbarui data penawaran estimasi panen Anda.</p>
        </div>
        
        <form action="{{ route('petani.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-premium border border-gray-150 space-y-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jenis Buah</label>
                    <input type="text" name="fruit_type" value="{{ old('fruit_type', $product->fruit_type) }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Target Kualitas (Grade)</label>
                    <select name="grade" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                        <option value="" disabled>Pilih Grade</option>
                        <option value="A" {{ old('grade', $product->grade) == 'A' ? 'selected' : '' }}>Grade A (Premium)</option>
                        <option value="B" {{ old('grade', $product->grade) == 'B' ? 'selected' : '' }}>Grade B (Standar)</option>
                        <option value="C" {{ old('grade', $product->grade) == 'C' ? 'selected' : '' }}>Grade C (Olahan)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Estimasi Berat (Kg)</label>
                    <input type="text" inputmode="decimal" pattern="[0-9]*\.?[0-9]*" name="estimated_weight_kg" value="{{ old('estimated_weight_kg', $product->estimated_weight_kg) }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Penawaran per Kg (Rp)</label>
                    <input type="number" inputmode="numeric" name="price_per_kg" value="{{ old('price_per_kg', $product->price_per_kg) }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tanggal Panen</label>
                <input type="date" name="harvest_date" value="{{ old('harvest_date', $product->harvest_date->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Catatan / Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Upload Gambar Baru (Opsional, Max 2MB)</label>
                @if($product->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($product->image) }}" alt="Gambar Produk" class="h-24 w-24 object-cover rounded-xl border border-gray-100">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full py-2.5 text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <a href="{{ route('petani.dashboard') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold shadow transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>
