<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-200/50 px-2.5 py-1 rounded-md">Tahap 2: Transaksi Pengadaan</span>
            <h1 class="text-2xl font-heading font-extrabold text-gray-800 mt-2">Buat Transaksi Pengadaan</h1>
            <p class="text-xs text-gray-500 mt-1">Dokumen resmi pembelian hasil panen dari petani.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Info Estimasi Panen -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-5 rounded-2xl mb-6 border border-indigo-100">
            <h3 class="font-heading font-extrabold text-indigo-800 text-sm mb-3 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-indigo-500"></span>
                Detail Estimasi Panen (Disetujui)
            </h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500 text-xs font-bold">Petani:</span><p class="font-extrabold text-gray-800">{{ $estimate->user->name }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Komoditas:</span><p class="font-extrabold text-gray-800">{{ $estimate->fruit_type }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Estimasi Berat:</span><p class="font-extrabold text-gray-800">{{ $estimate->estimated_weight_kg }} Kg</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Harga Penawaran:</span><p class="font-extrabold text-brand-700">Rp {{ number_format($estimate->price_per_kg, 0, ',', '.') }}/Kg</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Tanggal Panen:</span><p class="font-extrabold text-gray-800">{{ $estimate->harvest_date->format('d M Y') }}</p></div>
            </div>
        </div>

        <!-- Form Pengadaan -->
        <form action="{{ route('admin.procurement.store', $estimate) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-premium border border-gray-150 space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Disepakati (Rp/Kg)</label>
                    <input type="number" name="agreed_price_per_kg" value="{{ old('agreed_price_per_kg', $estimate->price_per_kg) }}" min="100" step="100" required class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">
                    @error('agreed_price_per_kg') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Berat Disepakati (Kg)</label>
                    <input type="number" name="agreed_weight_kg" value="{{ old('agreed_weight_kg', $estimate->estimated_weight_kg) }}" min="0.1" step="0.01" required class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">
                    @error('agreed_weight_kg') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tanggal Pengadaan</label>
                    <input type="date" name="procurement_date" value="{{ old('procurement_date', now()->format('Y-m-d')) }}" required class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Metode Pengambilan</label>
                    <select name="pickup_method" required class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">
                        <option value="pickup" {{ old('pickup_method') == 'pickup' ? 'selected' : '' }}>🚛 Dijemput ke Lokasi Petani</option>
                        <option value="delivery" {{ old('pickup_method') == 'delivery' ? 'selected' : '' }}>📦 Diantar oleh Petani</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" placeholder="Catatan tambahan untuk pengadaan ini...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <a href="{{ route('admin.procurement.pending') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-bold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow transition">Buat Transaksi Pengadaan</button>
            </div>
        </form>
    </div>
</x-app-layout>
