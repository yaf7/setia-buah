<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)">
        
        <!-- Premium Welcome Header -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">
                    Portal Kemitraan Petani
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Dashboard Petani</h1>
                <p class="text-xs text-gray-500">Kelola suplai komoditas buah segar dan petakan titik koordinat lahan Anda.</p>
            </div>
            
            <div class="shrink-0 flex items-center">
                <a href="{{ route('petani.products.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition duration-150 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Estimasi Panen
                </a>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-center justify-between gap-3 transition">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
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

        <!-- Map & Lahan GIS Panel -->
        <div class="space-y-6 mb-8">
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                            <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                            Pemetaan Spasial Lahan (GIS)
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Silakan klik pada peta di bawah untuk memplot koordinat lahan Anda secara presisi.</p>
                    </div>
                    
                    <form action="{{ route('petani.location.update') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="coordinates" id="coordinates" value="{{ old('coordinates', auth()->user()->latitude && auth()->user()->longitude ? auth()->user()->latitude . ', ' . auth()->user()->longitude : '') }}" placeholder="-8.06831, 112.07810" class="py-2 w-full max-w-[200px] rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 text-xs font-bold text-center transition" required>
                        
                        <button type="submit" class="py-2 text-xs font-extrabold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow shadow-indigo-600/10 active:scale-95 transition">
                            Simpan
                        </button>
                        
                        <button type="button" id="toggle-lock" class="py-2 text-xs font-extrabold bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl transition">
                            Kunci Lokasi
                        </button>
                    </form>
                </div>
                
                <div id="petani-map" class="h-72 w-full rounded-2xl border border-gray-150 shadow-inner z-10"></div>
            </div>
        </div>

        <!-- Harvest Records Panel -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
            <h2 class="font-heading font-extrabold text-gray-800 text-base mb-5 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                Daftar Estimasi Pasokan Panen
            </h2>

            <!-- Skeleton Loader (Desktop Table) -->
            <div x-show="loading" class="hidden md:block animate-pulse bg-gray-50 rounded-2xl h-48 w-full border border-gray-100"></div>

            <!-- Desktop Table -->
            <div x-show="!loading" style="display: none;" class="hidden md:block border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">Komoditas & Kualitas</th>
                            <th class="px-6 py-4 text-left">Estimasi Berat</th>
                            <th class="px-6 py-4 text-left">Nilai Jual/Kg</th>
                            <th class="px-6 py-4 text-left">Status Mutu (QC)</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">🥑</span>
                                    <div>
                                        <span class="block text-gray-800 font-extrabold">{{ $product->fruit_type }}</span>
                                        @if($product->status === 'accepted')
                                            <span class="block text-[10px] text-gray-400 font-extrabold">Grade {{ $product->grade }}</span>
                                        @else
                                            <span class="block text-[10px] text-gray-400 font-extrabold">Belum di-QC</span>
                                        @endif                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $product->estimated_weight_kg }} <span class="text-xs text-gray-400">Kilogram</span>
                            </td>
                            <td class="px-6 py-4 text-brand-700 font-bold">
                                Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($product->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-amber-100 text-amber-800 border border-amber-200/50">
                                        Pending Review
                                    </span>
                                @elseif($product->status === 'accepted')
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-emerald-100 text-emerald-800 border border-emerald-200/50">
                                        Lolos Mutu
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-rose-100 text-rose-800 border border-rose-200/50">
                                        Ditolak QC
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('petani.products.edit', $product) }}" class="py-1.5 inline-flex items-center justify-center px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div x-show="!loading" style="display: none;" class="md:hidden space-y-4">
                @foreach($products as $product)
                <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex flex-col space-y-3.5">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🥑</span>
                            <div>
                                <span class="block font-heading font-extrabold text-gray-800 text-sm leading-tight">{{ $product->fruit_type }}</span>
                                @if($product->status === 'accepted')
                                    <span class="block text-[9px] text-gray-400 font-extrabold uppercase mt-0.5">Grade {{ $product->grade }}</span>
                                @else
                                    <span class="block text-[9px] text-gray-400 font-extrabold uppercase mt-0.5">Menunggu QC</span>
                                @endif                            </div>
                        </div>
                        
                        @if($product->status === 'pending')
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">Pending</span>
                        @elseif($product->status === 'accepted')
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-800">Lolos</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-rose-100 text-rose-800">Ditolak</span>
                        @endif
                    </div>
                    
                    <div class="text-xs text-gray-500 font-bold space-y-1">
                        <p>Estimasi: <span class="text-gray-700">{{ $product->estimated_weight_kg }} Kg</span></p>
                        <p>Harga Jual: <span class="text-brand-700">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }} / Kg</span></p>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200/50 flex justify-end">
                        <a href="{{ route('petani.products.edit', $product) }}" class="py-1.5 inline-flex items-center justify-center px-4 bg-white border border-gray-200 text-gray-600 hover:text-gray-900 rounded-xl text-xs font-bold shadow-sm transition">
                            Edit Data
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>

    </div>

    <!-- Leaflet Mapping Library CDN Link -->
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
            var map = L.map('petani-map').setView([startLat, startLng], initial.length === 2 && !isNaN(initial[0]) && !isNaN(initial[1]) ? 14 : 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker = null;
            if (initial.length === 2 && !isNaN(initial[0]) && !isNaN(initial[1])) {
                marker = L.marker([startLat, startLng]).addTo(map);
            }

            function setLocked(state) {
                isLocked = state;
                coordsInput.readOnly = state;
                lockButton.textContent = state ? 'Buka Kunci' : 'Kunci Lokasi';
                lockButton.classList.toggle('bg-gray-100', !state);
                lockButton.classList.toggle('text-gray-700', !state);
                lockButton.classList.toggle('bg-yellow-100', state);
                lockButton.classList.toggle('text-yellow-850', state);
            }

            lockButton.addEventListener('click', function () {
                setLocked(!isLocked);
            });

            map.on('click', function (e) {
                if (isLocked) {
                    return;
                }
                var lat = e.latlng.lat.toFixed(6);
                var lng = e.latlng.lng.toFixed(6);
                coordsInput.value = lat + ', ' + lng;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
            });
        });
    </script>
</x-app-layout>
