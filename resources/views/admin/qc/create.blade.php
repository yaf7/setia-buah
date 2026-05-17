<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Form Quality Control (QC)</h1>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6 border">
            <h3 class="font-bold text-gray-700">Informasi Petani</h3>
            <p>Nama Petani: {{ $product->user->name }}</p>
            <p>Buah: {{ $product->fruit_type }}</p>
            <p>Grade Estimasi Petani: {{ $product->grade }}</p>
            <p>Berat Estimasi Petani: {{ $product->estimated_weight_kg }} Kg</p>
        </div>

        <form action="{{ route('admin.qc.store', $product) }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            
            <input type="hidden" name="petani_product_id" value="{{ $product->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-700">Berat Aktual Timbangan (Kg)</label>
                <input type="text" inputmode="decimal" pattern="[0-9]*\.?[0-9]*" name="actual_weight_kg" value="{{ old('actual_weight_kg', $product->estimated_weight_kg) }}" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Grade Final (Hasil QC)</label>
                <select name="final_grade" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
                    <option value="A" {{ old('final_grade', $product->grade) == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('final_grade', $product->grade) == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('final_grade', $product->grade) == 'C' ? 'selected' : '' }}>C</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Keputusan</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
                    <option value="accepted">Terima & Masukkan Gudang</option>
                    <option value="rejected">Tolak (Cacad/Rusak)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan QC (Opsional)</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 p-2">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="min-h-[44px] inline-flex items-center px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="min-h-[44px] min-w-[44px] px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">Simpan Hasil QC</button>
            </div>
        </form>
    </div>
</x-app-layout>