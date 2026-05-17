<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8" x-data="formDraft()" x-init="loadDraft()">
        <h1 class="text-2xl font-bold mb-6">Tambah Stok Hasil Tani</h1>
        
        <form action="{{ route('petani.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Buah</label>
                <input type="text" name="fruit_type" x-model="draft.fruit_type" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 min-h-[44px] px-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Grade</label>
                <select name="grade" x-model="draft.grade" @change="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
                    <option value="A">A (Kualitas Ekspor/Premium)</option>
                    <option value="B">B (Standar Supermarket)</option>
                    <option value="C">C (Olahan/Pasar Tradisional)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estimasi Berat (Kg)</label>
                    <input type="text" inputmode="decimal" pattern="[0-9]*\.?[0-9]*" name="estimated_weight_kg" x-model="draft.estimated_weight_kg" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Kg (Rp)</label>
                    <input type="number" inputmode="numeric" name="price_per_kg" x-model="draft.price_per_kg" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Panen</label>
                <input type="date" name="harvest_date" x-model="draft.harvest_date" @input="saveDraft" class="mt-1 block w-full rounded-md border-gray-300 min-h-[44px] px-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Gambar Buah (Max 2MB)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full min-h-[44px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" required>
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <button type="button" @click="clearDraft" class="min-h-[44px] min-w-[44px] px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Reset Draft</button>
                <button type="submit" class="min-h-[44px] min-w-[44px] px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium w-full sm:w-auto">Simpan Stok</button>
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
