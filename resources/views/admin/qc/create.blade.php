<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-purple-600 bg-purple-50 border border-purple-200/50 px-2.5 py-1 rounded-md">Tahap 5: Quality Control</span>
            <h1 class="text-2xl font-heading font-extrabold text-gray-800 mt-2">Form Quality Control (QC)</h1>
            <p class="text-xs text-gray-500 mt-1">Penentuan kualitas dan harga jual akhir setelah penerimaan gudang.</p>
        </div>

        {{-- Informasi Pengadaan & Petani --}}
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-5 rounded-2xl mb-6 border border-purple-100">
            <h3 class="font-heading font-extrabold text-purple-800 text-sm mb-3 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-purple-500"></span>
                Informasi Barang Diterima
            </h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500 text-xs font-bold">Petani:</span><p class="font-extrabold text-gray-800">{{ $product->user->name }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Komoditas:</span><p class="font-extrabold text-gray-800">{{ $product->fruit_type }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Estimasi Berat:</span><p class="font-extrabold text-gray-800">{{ number_format($product->estimated_weight_kg, 2) }} Kg</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Target Grade:</span><p class="font-extrabold text-brand-700">Grade {{ $product->grade }}</p></div>
                @if($product->procurement)
                <div><span class="text-gray-500 text-xs font-bold">No. Pengadaan:</span><p class="font-extrabold text-indigo-700">{{ $product->procurement->procurement_number }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Harga Beli:</span><p class="font-extrabold text-brand-700">Rp {{ number_format($product->procurement->agreed_price_per_kg, 0, ',', '.') }}/Kg</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Metode:</span><p class="font-extrabold text-gray-800">{{ $product->procurement->pickup_method === 'pickup' ? 'Dijemput' : 'Diantar' }}</p></div>
                @else
                <div><span class="text-gray-500 text-xs font-bold">Harga Penawaran:</span><p class="font-extrabold text-brand-700">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}/Kg</p></div>
                @endif
            </div>
        </div>

        {{-- Form QC --}}
        <form action="{{ route('admin.qc.store', $product) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-premium border border-gray-150 space-y-5">
            @csrf
            <input type="hidden" name="petani_product_id" value="{{ $product->id }}">

            @if($product->procurement)
            <div class="mb-4">
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Beli Petani (Rp/Kg)</label>
                <input type="text" value="Rp {{ number_format($product->procurement->agreed_price_per_kg, 0, ',', '.') }}" class="w-full rounded-xl bg-gray-100 border-gray-200 py-2.5 px-3.5 text-sm max-w-sm" readonly>
            </div>
            @endif

            <div class="space-y-4 border border-gray-200 rounded-2xl p-4 bg-gray-50">
                <h4 class="font-heading font-extrabold text-gray-700 text-sm mb-2">Input Hasil Sortir Grade</h4>
                
                <!-- Grade A -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-extrabold text-amber-500 uppercase tracking-widest mb-1">⭐ Berat Grade A (Kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_a" value="{{ old('weight_a') }}" class="w-full rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 py-2.5 px-3.5 text-sm bg-white" placeholder="0.00">
                        @error('weight_a') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Jual Grade A (Rp/Kg)</label>
                        <input type="number" name="price_a" id="price_a" min="0" step="100" value="{{ old('price_a') }}" class="w-full rounded-xl border border-gray-200 bg-gray-100 py-2.5 px-3.5 text-sm font-bold text-gray-600" readonly>
                    </div>
                </div>

                <!-- Grade B -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-extrabold text-brand-600 uppercase tracking-widest mb-1">🌱 Berat Grade B (Kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_b" value="{{ old('weight_b') }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm bg-white" placeholder="0.00">
                        @error('weight_b') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Harga Jual Grade B (Rp/Kg)</label>
                        <input type="number" name="price_b" id="price_b" min="0" step="100" value="{{ old('price_b') }}" class="w-full rounded-xl border border-gray-200 bg-gray-100 py-2.5 px-3.5 text-sm font-bold text-gray-600" readonly>
                    </div>
                </div>

            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Berat Barang Rusak / Diretur (Kg)</label>
                <input type="number" step="0.01" min="0" name="rejected_weight_kg" value="{{ old('rejected_weight_kg', 0) }}" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm max-w-sm" required>
                <p class="text-xs text-gray-500 mt-1">Sisa barang yang tidak masuk ke grade manapun.</p>
                @error('rejected_weight_kg') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tujuan Barang Setelah QC</label>
                <select name="inventory_status" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                    <option value="catalog" {{ old('inventory_status') == 'catalog' ? 'selected' : '' }}>Langsung Masuk Katalog (Tampil di Toko)</option>
                    <option value="warehouse" {{ old('inventory_status') == 'warehouse' ? 'selected' : '' }}>Simpan di Stok Gudang (Belum Tampil di Toko)</option>
                </select>
                @error('inventory_status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Keputusan QC</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm" required>
                    <option value="accepted" selected>✅ Diterima — Masuk Katalog & Inventory</option>
                    <option value="rejected">❌ Ditolak — Tidak Memenuhi Standar</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Catatan QC (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4 gap-2">
                <a href="{{ route('admin.qc.queue') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-bold shadow transition">Simpan Hasil QC</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hargaBeli = {{ $product->procurement ? (float) $product->procurement->agreed_price_per_kg : (float) $product->price_per_kg }};
            
            document.getElementById('price_a').value = hargaBeli * 2;
            document.getElementById('price_b').value = hargaBeli * 1.5;
            document.getElementById('price_c').value = hargaBeli * 1.2;
        });
    </script>
</x-app-layout>