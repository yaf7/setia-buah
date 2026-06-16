<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8" x-data="formDraft()" x-init="loadDraft()">
        <div class="mb-6">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">Estimasi Panen</span>
            <h1 class="text-2xl font-heading font-extrabold text-gray-800 mt-2">Tambah Estimasi Panen</h1>
            <p class="text-xs text-gray-500 mt-1">Kirim penawaran hasil panen Anda untuk ditinjau admin.</p>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('petani.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-premium border border-gray-150 space-y-5">
            @csrf
            
            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jenis Buah</label>
                <input type="text" name="fruit_type" value="{{ old('fruit_type') }}" x-model="draft.fruit_type" @input="saveDraft" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm @error('fruit_type') border-rose-500 @enderror" required placeholder="Contoh: Alpukat, Mangga, Jeruk">
                @error('fruit_type') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Estimasi Berat (Kg)</label>
                    <input type="number" inputmode="decimal" step="0.01" min="0.1" name="estimated_weight_kg" value="{{ old('estimated_weight_kg') }}" x-model="draft.estimated_weight_kg" @input="saveDraft" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                    @error('estimated_weight_kg') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Penawaran per Kg (Rp)</label>
                    <input type="number" inputmode="numeric" min="100" name="price_per_kg" value="{{ old('price_per_kg') }}" x-model="draft.price_per_kg" @input="saveDraft" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                    @error('price_per_kg') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tanggal Panen</label>
                <input type="date" name="harvest_date" value="{{ old('harvest_date') }}" x-model="draft.harvest_date" @input="saveDraft" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                @error('harvest_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Catatan / Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" placeholder="Kondisi buah, varietas, dll.">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Upload Foto Buah (Max 2MB)</label>
                <input type="file" name="image" accept="image/*" class="w-full py-2.5 text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" required>
                @error('image') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <button type="button" @click="clearDraft" class="px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-bold">Reset</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold shadow transition">Kirim Estimasi Panen</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formDraft', () => ({
                draft: { fruit_type: '', estimated_weight_kg: '', price_per_kg: '', harvest_date: '' },
                loadDraft() { const s = localStorage.getItem('petani_form_draft'); if (s) this.draft = JSON.parse(s); },
                saveDraft() { localStorage.setItem('petani_form_draft', JSON.stringify(this.draft)); },
                clearDraft() { this.draft = { fruit_type: '', estimated_weight_kg: '', price_per_kg: '', harvest_date: '' }; localStorage.removeItem('petani_form_draft'); }
            }))
        })
    </script>
</x-app-layout>
