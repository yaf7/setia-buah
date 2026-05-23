<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8" x-data="formDraft()" x-init="loadDraft()">
        <h1 class="text-2xl font-bold mb-6">Tambah Stok Hasil Tani</h1>

        @if($errors->any())
            <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span class="font-bold text-sm">Gagal Menyimpan Data:</span>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('petani.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Buah</label>
                <input type="text" name="fruit_type" value="{{ old('fruit_type') }}" x-model="draft.fruit_type" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2.5 px-3 @error('fruit_type') border-rose-500 @enderror" required>
                @error('fruit_type') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Grade</label>
                <select name="grade" x-model="draft.grade" @change="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3 @error('grade') border-rose-500 @enderror" required>
                    <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>A (Kualitas Ekspor/Premium)</option>
                    <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B (Standar Supermarket)</option>
                    <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C (Olahan/Pasar Tradisional)</option>
                </select>
                @error('grade') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estimasi Berat (Kg)</label>
                    <input type="number" inputmode="decimal" step="0.01" min="0.1" name="estimated_weight_kg" value="{{ old('estimated_weight_kg') }}" x-model="draft.estimated_weight_kg" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3 @error('estimated_weight_kg') border-rose-500 @enderror" required>
                    @error('estimated_weight_kg') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Kg (Rp)</label>
                    <input type="number" inputmode="numeric" min="100" name="price_per_kg" value="{{ old('price_per_kg') }}" x-model="draft.price_per_kg" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3 @error('price_per_kg') border-rose-500 @enderror" required>
                    @error('price_per_kg') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Panen</label>
                <input type="date" name="harvest_date" value="{{ old('harvest_date') }}" x-model="draft.harvest_date" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3 @error('harvest_date') border-rose-500 @enderror" required>
                @error('harvest_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Gambar Buah (Max 2MB)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full py-2.5 text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" required>
                @error('image') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <button type="button" @click="clearDraft" class="py-2.5 min-w-[44px] px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Reset Draft</button>
                <button type="submit" class="py-2.5 min-w-[44px] px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium w-full sm:w-auto">Simpan Stok</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formDraft', () => ({
                draft: { fruit_type: '', grade: 'A', estimated_weight_kg: '', price_per_kg: '', harvest_date: '' },
                loadDraft() {
                    const saved = localStorage.getItem('petani_form_draft');
                    if (saved) this.draft = JSON.parse(saved);
                },
                saveDraft() {
                    localStorage.setItem('petani_form_draft', JSON.stringify(this.draft));
                },
                clearDraft() {
                    this.draft = { fruit_type: '', grade: 'A', estimated_weight_kg: '', price_per_kg: '', harvest_date: '' };
                    localStorage.removeItem('petani_form_draft');
                }
            }))
        })
    </script>
</x-app-layout>
