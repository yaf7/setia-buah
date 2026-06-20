<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showEditModal: false, showDetailModal: false, selectedInventory: {} }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">
                    Manajemen Gudang
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">
                    {{ $status === 'katalog' ? 'Daftar Stok Katalog' : ($status === 'gudang' ? 'Stok Barang Gudang' : 'Keseluruhan Stok Barang') }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ $status === 'katalog' ? 'Barang yang saat ini aktif dijual di toko (katalog).' : ($status === 'gudang' ? 'Barang yang tersimpan di gudang dan siap dipindahkan ke katalog.' : 'Daftar seluruh inventaris stok buah segar.') }}
                </p>
            </div>
            
            <div class="shrink-0 flex items-center gap-3">
                <span class="bg-brand-50 border border-brand-200/50 text-brand-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">
                    Total: {{ number_format($totalStock, 1, ',', '.') }} Kg
                </span>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Inventory Table -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
            <div class="p-0 overflow-x-auto">
                @if($inventories->isEmpty())
                    <div class="px-6 py-16 text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        <p class="text-sm font-bold leading-relaxed max-w-xs mx-auto">Gudang masih kosong.<br>Stok akan muncul setelah barang lolos QC.</p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <table class="w-full text-sm divide-y divide-gray-100 hidden md:table">
                        <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">No</th>
                                <th class="px-6 py-4 text-left">Jenis Buah</th>
                                <th class="px-6 py-4 text-left">Grade</th>
                                <th class="px-6 py-4 text-left">Stok</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Harga/Kg</th>
                                <th class="px-6 py-4 text-left">Kadaluarsa</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                            @foreach($inventories as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-gray-400 font-extrabold">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->fruit_type }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 shadow-sm">
                                        @else
                                            <span class="h-10 w-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">🥑</span>
                                        @endif
                                        <span class="text-gray-800 font-extrabold">{{ $item->fruit_type }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-indigo-100 text-indigo-800 border border-indigo-200/50">
                                        Grade {{ $item->grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-brand-700 font-bold">
                                    {{ number_format($item->stock_kg, 1, ',', '.') }} <span class="text-xs text-gray-400">Kg</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-green-100 text-green-800 border border-green-200/50">
                                            Katalog
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-gray-100 text-gray-800 border border-gray-200/50">
                                            Gudang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    @if($item->discount_percent > 0)
                                        <span class="block text-xs text-gray-400 line-through">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</span>
                                        <span class="text-rose-600 font-extrabold text-xs">Rp {{ number_format($item->final_price, 0, ',', '.') }} <span class="text-[9px] bg-rose-100 text-rose-800 px-1 py-0.5 rounded font-black ml-1">-{{ $item->discount_percent }}%</span></span>
                                    @else
                                        Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->expiry_date)
                                        @if($item->expiry_date->isPast())
                                            <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-rose-100 text-rose-800 border border-rose-200/50">
                                                Kadaluarsa
                                            </span>
                                        @elseif($item->expiry_date->diffInDays(now()) <= 3)
                                            <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-amber-100 text-amber-800 border border-amber-200/50">
                                                {{ $item->expiry_date->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">{{ $item->expiry_date->format('d M Y') }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail Traceability -->
                                        <button type="button" @click="
                                            selectedInventory = {
                                                id: {{ $item->id }},
                                                fruit_type: '{{ $item->fruit_type }}',
                                                grade: '{{ $item->grade }}',
                                                stock_kg: {{ $item->stock_kg }},
                                                price_per_kg: {{ $item->price_per_kg }},
                                                discount_percent: {{ $item->discount_percent ?? 0 }},
                                                batch_number: '{{ $item->batch_number }}',
                                                expiry_date: '{{ $item->expiry_date ? $item->expiry_date->format('d M Y') : '-' }}',
                                                farmer_name: '{{ addslashes(optional($item->procurement?->harvestEstimate?->user)->name ?? (optional($item->qcReport?->product?->user)->name ?? 'Tidak diketahui')) }}',
                                                farmer_location: '{{ optional($item->procurement?->harvestEstimate?->user)->latitude && optional($item->procurement?->harvestEstimate?->user)->longitude ? optional($item->procurement?->harvestEstimate?->user)->latitude . ', ' . optional($item->procurement?->harvestEstimate?->user)->longitude : '-' }}',
                                                qc_admin: '{{ addslashes(optional($item->qcReport?->admin)->name ?? 'Tidak diketahui') }}',
                                                qc_date: '{{ optional($item->qcReport)->created_at ? optional($item->qcReport)->created_at->format('d M Y H:i') : '-' }}',
                                                qc_notes: '{{ addslashes(str_replace(["\r", "\n"], " ", optional($item->qcReport)->notes ?? "-")) }}',
                                                procurement_notes: '{{ addslashes(str_replace(["\r", "\n"], " ", optional($item->procurement)->notes ?? "-")) }}'
                                            };
                                            showDetailModal = true;
                                        " class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Detail Keterlacakan (Traceability)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>

                                        <!-- Edit / Promo -->
                                        <button type="button" @click="
                                            selectedInventory = {
                                                id: {{ $item->id }},
                                                fruit_type: '{{ $item->fruit_type }}',
                                                grade: '{{ $item->grade }}',
                                                stock_kg: {{ $item->stock_kg }},
                                                price_per_kg: {{ $item->price_per_kg }},
                                                discount_percent: {{ $item->discount_percent ?? 0 }}
                                            };
                                            showEditModal = true;
                                        " class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Edit Stok, Harga, & Diskon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>

                                        <!-- Tarik / Terbitkan -->
                                        <form action="{{ route('admin.inventory.toggle-status', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 {{ $item->is_active ? 'bg-purple-50 hover:bg-purple-100 text-purple-600 border border-purple-100/50' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-100/50' }} rounded-xl text-xs font-bold transition flex items-center justify-center" title="{{ $item->is_active ? 'Tarik dari Toko (Sembunyikan)' : 'Terbitkan ke Toko' }}">
                                                @if($item->is_active)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                @endif
                                            </button>
                                        </form>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Permanen">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mobile Card List -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($inventories as $index => $item)
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-2.5">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->fruit_type }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 shadow-sm">
                                    @else
                                        <span class="h-10 w-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">🥑</span>
                                    @endif
                                    <div>
                                        <span class="block font-heading font-extrabold text-gray-800 text-sm">{{ $item->fruit_type }}</span>
                                        <span class="block text-[9px] text-gray-400 font-extrabold uppercase mt-0.5">Grade {{ $item->grade }} • {{ $item->is_active ? 'Katalog' : 'Gudang' }}</span>
                                    </div>
                                </div>
                                <span class="text-brand-700 font-extrabold text-sm">{{ number_format($item->stock_kg, 1, ',', '.') }} Kg</span>
                            </div>
                            
                            <div class="text-xs text-gray-500 font-bold space-y-1">
                                <p>Harga: 
                                    @if($item->discount_percent > 0)
                                        <span class="line-through text-gray-300 mr-1">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</span>
                                        <span class="text-rose-600">Rp {{ number_format($item->final_price, 0, ',', '.') }} <span class="text-[9px] bg-rose-100 text-rose-800 px-1 py-0.5 rounded font-black ml-1">-{{ $item->discount_percent }}%</span></span>
                                    @else
                                        <span class="text-gray-700 font-bold">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }} / Kg</span>
                                    @endif
                                </p>
                                @if($item->expiry_date)
                                    <p>Kadaluarsa: <span class="text-gray-700">{{ $item->expiry_date->format('d M Y') }}</span></p>
                                @endif
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200/50 flex justify-end gap-2">
                                <!-- Detail Traceability -->
                                <button type="button" @click="
                                    selectedInventory = {
                                        id: {{ $item->id }},
                                        fruit_type: '{{ $item->fruit_type }}',
                                        grade: '{{ $item->grade }}',
                                        stock_kg: {{ $item->stock_kg }},
                                        price_per_kg: {{ $item->price_per_kg }},
                                        discount_percent: {{ $item->discount_percent ?? 0 }},
                                        batch_number: '{{ $item->batch_number }}',
                                        expiry_date: '{{ $item->expiry_date ? $item->expiry_date->format('d M Y') : '-' }}',
                                        farmer_name: '{{ addslashes(optional($item->procurement?->harvestEstimate?->user)->name ?? (optional($item->qcReport?->product?->user)->name ?? 'Tidak diketahui')) }}',
                                        farmer_location: '{{ optional($item->procurement?->harvestEstimate?->user)->latitude && optional($item->procurement?->harvestEstimate?->user)->longitude ? optional($item->procurement?->harvestEstimate?->user)->latitude . ', ' . optional($item->procurement?->harvestEstimate?->user)->longitude : '-' }}',
                                        qc_admin: '{{ addslashes(optional($item->qcReport?->admin)->name ?? 'Tidak diketahui') }}',
                                        qc_date: '{{ optional($item->qcReport)->created_at ? optional($item->qcReport)->created_at->format('d M Y H:i') : '-' }}',
                                        qc_notes: '{{ addslashes(str_replace(["\r", "\n"], " ", optional($item->qcReport)->notes ?? "-")) }}',
                                        procurement_notes: '{{ addslashes(str_replace(["\r", "\n"], " ", optional($item->procurement)->notes ?? "-")) }}'
                                    };
                                    showDetailModal = true;
                                " class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Detail Keterlacakan (Traceability)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>

                                <!-- Edit / Promo -->
                                <button type="button" @click="
                                    selectedInventory = {
                                        id: {{ $item->id }},
                                        fruit_type: '{{ $item->fruit_type }}',
                                        grade: '{{ $item->grade }}',
                                        stock_kg: {{ $item->stock_kg }},
                                        price_per_kg: {{ $item->price_per_kg }},
                                        discount_percent: {{ $item->discount_percent ?? 0 }}
                                    };
                                    showEditModal = true;
                                " class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Edit Stok, Harga, & Diskon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>

                                <!-- Tarik / Terbitkan -->
                                <form action="{{ route('admin.inventory.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 {{ $item->is_active ? 'bg-purple-50 hover:bg-purple-100 text-purple-600 border border-purple-100/50' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-100/50' }} rounded-xl text-xs font-bold transition flex items-center justify-center" title="{{ $item->is_active ? 'Tarik dari Toko (Sembunyikan)' : 'Terbitkan ke Toko' }}">
                                        @if($item->is_active)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Hapus -->
                                <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Permanen">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Edit / Promo Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showEditModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-amber-50 text-amber-600 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-heading font-extrabold text-gray-800 leading-6">
                                    Ubah Data & Promo Stok
                                </h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    Perbarui berat stok, harga per kg, atau persentase promo diskon untuk <span class="font-bold text-gray-700" x-text="selectedInventory.fruit_type"></span> (Grade <span x-text="selectedInventory.grade"></span>).
                                </p>
                                
                                <form :action="'{{ url('/admin/inventory') }}/' + selectedInventory.id" method="POST" class="mt-6 space-y-4">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Stok Terkini (Kg)</label>
                                        <input type="number" step="0.01" min="0" name="stock_kg" x-model="selectedInventory.stock_kg" required class="mt-1.5 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-semibold text-gray-700">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Harga Jual / Kg (Rp)</label>
                                        <input type="number" min="0" name="price_per_kg" x-model="selectedInventory.price_per_kg" required class="mt-1.5 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-semibold text-gray-700">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Setup Diskon (%)</label>
                                        <input type="number" min="0" max="100" name="discount_percent" x-model="selectedInventory.discount_percent" class="mt-1.5 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-semibold text-gray-700">
                                        <p class="text-[10px] text-gray-400 mt-1">Berikan diskon jika buah mendekati kadaluarsa agar cepat laku terjual.</p>
                                    </div>

                                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                                        <button type="button" @click="showEditModal = false" class="py-2.5 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-xs sm:text-sm transition">
                                            Batal
                                        </button>
                                        <button type="submit" class="py-2.5 px-5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-xs sm:text-sm transition shadow-md shadow-brand-500/10">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Traceability Modal -->
        <div x-show="showDetailModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showDetailModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white p-6">
                        <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-blue-600 to-blue-500 flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-heading font-extrabold text-gray-800" x-text="selectedInventory.fruit_type + ' (Grade ' + selectedInventory.grade + ')'"></h3>
                                    <p class="text-[9px] text-brand-600 font-extrabold uppercase tracking-wider mt-0.5">Detail Keterlacakan (Traceability)</p>
                                </div>
                            </div>
                            <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-650 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="mt-6 space-y-4 text-xs sm:text-sm text-gray-600 font-medium">
                            <!-- Lot info -->
                            <div class="flex justify-between items-center py-2.5 border-b border-gray-50">
                                <span class="text-gray-400 font-bold">Nomor Batch / Lot</span>
                                <span class="text-gray-800 font-extrabold" x-text="selectedInventory.batch_number || '-'"></span>
                            </div>

                            <!-- Farmer info -->
                            <div class="flex justify-between items-start py-2.5 border-b border-gray-50">
                                <span class="text-gray-400 font-bold shrink-0">Petani / Asal Lahan</span>
                                <div class="text-right space-y-0.5">
                                    <p class="text-gray-800 font-extrabold" x-text="selectedInventory.farmer_name"></p>
                                    <p class="text-[10px] text-brand-600 font-bold flex items-center justify-end gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        GPS: <span x-text="selectedInventory.farmer_location"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Expiry info -->
                            <div class="flex justify-between items-center py-2.5 border-b border-gray-50">
                                <span class="text-gray-400 font-bold">Kadaluarsa (FEFO)</span>
                                <span class="text-rose-600 font-extrabold" x-text="selectedInventory.expiry_date"></span>
                            </div>

                            <!-- QC details -->
                            <div class="py-2.5 border-b border-gray-50 space-y-2">
                                <span class="text-gray-400 font-bold block">Pemeriksaan Mutu (Quality Control)</span>
                                <div class="bg-gray-50/70 p-3 rounded-2xl border border-gray-100 space-y-1.5">
                                    <p class="text-xs text-gray-600">Pemeriksa: <span class="text-gray-800 font-bold" x-text="selectedInventory.qc_admin"></span></p>
                                    <p class="text-xs text-gray-600">Waktu Lulus: <span class="text-gray-800 font-bold" x-text="selectedInventory.qc_date"></span></p>
                                    <div class="h-px bg-gray-100 my-1.5"></div>
                                    <p class="text-xs italic text-gray-500 leading-relaxed font-medium">
                                        Catatan QC: "<span x-text="selectedInventory.qc_notes"></span>"
                                    </p>
                                </div>
                            </div>

                            <!-- Procurement Notes -->
                            <div class="py-2.5 space-y-1">
                                <span class="text-gray-400 font-bold block">Catatan Petugas Pengadaan</span>
                                <p class="text-xs italic text-gray-500 leading-relaxed" x-text="selectedInventory.procurement_notes"></p>
                            </div>
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="button" @click="showDetailModal = false" class="py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm rounded-xl transition shadow-md shadow-blue-500/10">
                                Tutup Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
