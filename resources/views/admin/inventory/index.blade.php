<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
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
                                    Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
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
                                    @if(!$item->is_active)
                                        <form action="{{ route('admin.inventory.toggle-status', $item) }}" method="POST" onsubmit="return confirm('Pindahkan stok ini ke halaman Toko/Katalog?')">
                                            @csrf
                                            <button type="submit" class="py-1.5 inline-flex items-center justify-center gap-1.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100/50 rounded-xl text-xs font-bold transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                Ke Stok Katalog
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini dari katalog?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="py-1.5 inline-flex items-center justify-center gap-1.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
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
                                <p>Harga: <span class="text-gray-700">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }} / Kg</span></p>
                                @if($item->expiry_date)
                                    <p>Kadaluarsa: <span class="text-gray-700">{{ $item->expiry_date->format('d M Y') }}</span></p>
                                @endif
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200/50 flex justify-end">
                                @if(!$item->is_active)
                                    <form action="{{ route('admin.inventory.toggle-status', $item) }}" method="POST" onsubmit="return confirm('Pindahkan stok ini ke halaman Toko/Katalog?')">
                                        @csrf
                                        <button type="submit" class="py-1.5 inline-flex items-center justify-center gap-1.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100/50 rounded-xl text-xs font-bold transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                            Ke Stok Katalog
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini dari katalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="py-1.5 inline-flex items-center justify-center gap-1.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
