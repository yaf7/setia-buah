<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)">
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">
                    Portal Kemitraan Petani
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Dashboard Petani</h1>
                <p class="text-xs text-gray-500">Kelola estimasi panen dan pantau status penawaran Anda.</p>
            </div>
            
            <div class="shrink-0 flex items-center">
                <a href="{{ route('petani.products.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition duration-150 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Estimasi Panen
                </a>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl">
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
            @php
            $statItems = [
                ['label' => 'Total', 'value' => $stats['total'], 'color' => 'gray', 'icon' => '📊'],
                ['label' => 'Menunggu', 'value' => $stats['pending'], 'color' => 'amber', 'icon' => '⏳'],
                ['label' => 'Disetujui', 'value' => $stats['approved'], 'color' => 'blue', 'icon' => '✅'],
                ['label' => 'Proses', 'value' => $stats['in_progress'], 'color' => 'indigo', 'icon' => '🔄'],
                ['label' => 'Selesai', 'value' => $stats['completed'], 'color' => 'emerald', 'icon' => '✨'],
                ['label' => 'Ditolak', 'value' => $stats['rejected'], 'color' => 'rose', 'icon' => '❌'],
            ];
            @endphp
            @foreach($statItems as $stat)
            <div class="bg-{{ $stat['color'] }}-50 border border-{{ $stat['color'] }}-200/50 rounded-2xl p-4 text-center">
                <div class="text-lg mb-1">{{ $stat['icon'] }}</div>
                <p class="text-xl font-heading font-black text-{{ $stat['color'] }}-800">{{ $stat['value'] }}</p>
                <p class="text-[9px] font-extrabold uppercase tracking-widest text-{{ $stat['color'] }}-600">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="space-y-6 mb-8">
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                            <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                            Pemetaan Spasial Lahan (GIS)
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Klik pada peta untuk memplot koordinat lahan Anda.</p>
                    </div>
                    <form action="{{ route('petani.location.update') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="coordinates" id="coordinates" value="{{ old('coordinates', auth()->user()->latitude && auth()->user()->longitude ? auth()->user()->latitude . ', ' . auth()->user()->longitude : '') }}" placeholder="-8.06831, 112.07810" class="py-2 w-full max-w-[200px] rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 text-xs font-bold text-center transition" required>
                        <button type="submit" class="py-2 text-xs font-extrabold bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-xl shadow transition">Simpan</button>
                        <button type="button" id="toggle-lock" class="py-2 text-xs font-extrabold bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-xl transition">Kunci</button>
                    </form>
                </div>
                <div id="petani-map" class="h-72 w-full rounded-2xl border border-gray-150 shadow-inner z-10"></div>
            </div>
        </div>

        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
            <h2 class="font-heading font-extrabold text-gray-800 text-base mb-5 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                Daftar Estimasi Panen Anda
            </h2>

            <div x-show="!loading" style="display: none;" class="hidden md:block border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">Komoditas</th>
                            <th class="px-6 py-4 text-left">Estimasi Berat</th>
                            <th class="px-6 py-4 text-left">Harga Penawaran</th>
                            <th class="px-6 py-4 text-left">Status Alur</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100">
                                    @else
                                        <span class="text-base">🥑</span>
                                    @endif
                                    <div>
                                        <span class="block text-gray-800 font-extrabold">{{ $product->fruit_type }}</span>
                                        @if($product->grade)
                                            <span class="block text-[10px] text-gray-400 font-extrabold">Grade {{ $product->grade }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $product->estimated_weight_kg }} <span class="text-xs text-gray-400">Kg</span></td>
                            <td class="px-6 py-4 text-brand-700 font-bold">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-{{ $product->status_color }}-100 text-{{ $product->status_color }}-800 border border-{{ $product->status_color }}-200/50">
                                    {{ $product->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($product->status === 'pending')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('petani.products.edit', $product) }}" class="py-1.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">Edit</a>
                                        <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus estimasi ini?')">
                                            @csrf @method('DELETE')
                                            <button class="py-1.5 px-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition border border-rose-200/50">Hapus</button>
                                        </form>
                                    </div>
                                @elseif($product->status === 'rejected')
                                    <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="py-1.5 px-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition border border-rose-200/50">Hapus</button>
                                    </form>
                                @else
                                    <div x-data="{ openQcModal: false }" class="flex justify-end">
                                        <button @click="openQcModal = true" type="button" class="py-1.5 px-4 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-xl text-xs font-bold transition border border-brand-200/50 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            Detail QC
                                        </button>

                                        <div x-show="openQcModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                                <div x-show="openQcModal" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="openQcModal = false"></div>

                                                <div x-show="openQcModal" x-transition.duration.300ms class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                                                    
                                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                                                        <div>
                                                            <h3 class="text-lg font-extrabold text-gray-800" id="modal-title">Laporan Hasil QC</h3>
                                                            <p class="text-xs text-gray-500">{{ $product->updated_at->format('d M Y') }}</p>
                                                        </div>
                                                        <button @click="openQcModal = false" class="text-gray-400 hover:text-gray-600 transition">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4 mb-5">
                                                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                                                            <p class="text-[10px] text-emerald-600 font-extrabold uppercase tracking-wider mb-1">Lolos Gudang</p>
                                                            <p class="text-2xl font-black text-gray-800">
                                                                {{ $product->qcReports->sum('actual_weight_kg') ?? 0 }} 
                                                                <span class="text-sm font-semibold text-gray-500">KG</span>
                                                            </p>
                                                        </div>

                                                        <div class="bg-rose-50 p-4 rounded-xl border border-rose-200">
                                                            <div class="flex items-center gap-1 mb-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                                <p class="text-[10px] text-rose-600 font-extrabold uppercase tracking-wider">Diretur</p>
                                                            </div>
                                                            <p class="text-2xl font-black text-rose-600">
                                                                {{ $product->qcReports->sum('rejected_weight_kg') ?? 0 }} 
                                                                <span class="text-sm font-semibold text-rose-500">KG</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="bg-gray-50 p-3.5 rounded-xl text-sm border border-gray-200/60">
                                                        <span class="font-bold text-gray-800 block mb-1 text-xs">Catatan Admin:</span>
                                                        <p class="italic text-gray-600 text-xs leading-relaxed">
                                                            "{{ optional($product->qcReports->first())->notes ?? 'Tidak ada catatan tambahan.' }}"
                                                        </p>
                                                    </div>

                                                    <div class="mt-6">
                                                        <button @click="openQcModal = false" type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                                                            Tutup Laporan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-show="!loading" style="display: none;" class="md:hidden space-y-4">
                @foreach($products as $product)
                <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100">
                            @else
                                <span class="text-base">🥑</span>
                            @endif
                            <div>
                                <span class="block font-heading font-extrabold text-gray-800 text-sm">{{ $product->fruit_type }}</span>
                                <span class="block text-[9px] text-gray-400 font-extrabold uppercase mt-0.5">{{ $product->estimated_weight_kg }} Kg</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-{{ $product->status_color }}-100 text-{{ $product->status_color }}-800">{{ $product->status_label }}</span>
                    </div>
                    <div class="text-xs text-gray-500 font-bold">
                        <p>Harga: <span class="text-brand-700">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }} / Kg</span></p>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200/50 flex justify-end gap-2">
                        @if($product->status === 'pending')
                            <a href="{{ route('petani.products.edit', $product) }}" class="py-1.5 px-4 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold transition">Edit</a>
                            <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="py-1.5 px-3 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold border border-rose-200/50">Hapus</button>
                            </form>
                        @elseif($product->status === 'rejected')
                            <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="py-1.5 px-3 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold border border-rose-200/50">Hapus</button>
                            </form>
                        @else
                            <div x-data="{ openQcModalMobile: false }">
                                <button @click="openQcModalMobile = true" type="button" class="py-1.5 px-4 bg-brand-50 text-brand-700 rounded-xl text-xs font-bold border border-brand-200/50 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Detail QC
                                </button>

                                <div x-show="openQcModalMobile" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen px-4 text-center">
                                        <div x-show="openQcModalMobile" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openQcModalMobile = false"></div>
                                        <div x-show="openQcModalMobile" class="relative inline-block w-full max-w-sm p-5 text-left align-middle bg-white shadow-xl rounded-2xl z-10">
                                            <h3 class="text-lg font-extrabold text-gray-800 mb-4 border-b pb-2">Laporan Hasil QC</h3>
                                            
                                            <div class="space-y-3 mb-4">
                                                <div class="flex justify-between items-center bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                                                    <span class="text-xs text-emerald-600 font-bold uppercase">Lolos Gudang</span>
                                                    <span class="font-black text-gray-800">{{ $product->qcReports->sum('actual_weight_kg') ?? 0 }} KG</span>
                                                </div>
                                                <div class="flex justify-between items-center bg-rose-50 p-3 rounded-lg border border-rose-100">
                                                    <span class="text-xs text-rose-600 font-bold uppercase">Diretur</span>
                                                    <span class="font-black text-rose-600">{{ $product->qcReports->sum('rejected_weight_kg') ?? 0 }} KG</span>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded-lg text-xs mb-4">
                                                <span class="font-bold text-gray-700 block mb-1">Catatan:</span>
                                                <p class="text-gray-600 italic">"{{ optional($product->qcReports->first())->notes ?? 'Tidak ada catatan.' }}"</p>
                                            </div>

                                            <button @click="openQcModalMobile = false" type="button" class="w-full rounded-xl border border-gray-300 py-2 bg-white text-sm font-bold text-gray-700">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var coordsInput = document.getElementById('coordinates');
            var lockButton = document.getElementById('toggle-lock');
            var isLocked = false;
            var initial = coordsInput.value.split(',').map(function (v) { return parseFloat(v.trim()); });
            var startLat = initial.length === 2 && !isNaN(initial[0]) ? initial[0] : -2.5489;
            var startLng = initial.length === 2 && !isNaN(initial[1]) ? initial[1] : 118.0149;
            var map = L.map('petani-map').setView([startLat, startLng], initial.length === 2 && !isNaN(initial[0]) ? 14 : 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
            var marker = null;
            if (initial.length === 2 && !isNaN(initial[0]) && !isNaN(initial[1])) marker = L.marker([startLat, startLng]).addTo(map);
            lockButton.addEventListener('click', function () {
                isLocked = !isLocked;
                coordsInput.readOnly = isLocked;
                lockButton.textContent = isLocked ? 'Buka' : 'Kunci';
            });
            map.on('click', function (e) {
                if (isLocked) return;
                coordsInput.value = e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
                if (marker) marker.setLatLng(e.latlng); else marker = L.marker(e.latlng).addTo(map);
            });
        });
    </script>
</x-app-layout>