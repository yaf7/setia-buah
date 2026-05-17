<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Petani</h1>
            <a href="{{ route('petani.products.create') }}" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                + Tambah Stok
            </a>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 bg-green-100 text-green-800 p-4 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded-lg shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h2 class="text-lg font-bold text-amber-900">Notifikasi Panen</h2>
                    <p class="text-sm text-amber-800">Pengingat untuk jadwal panen yang sudah dekat.</p>
                </div>
                <span class="inline-flex items-center justify-center min-w-10 h-10 px-3 rounded-full bg-amber-200 text-amber-900 font-semibold">
                    {{ $harvestNotifications->count() }}
                </span>
            </div>

            @if($harvestNotifications->isNotEmpty())
                <ul class="space-y-3">
                    @foreach($harvestNotifications as $notification)
                        @php($data = $notification->data)
                        <li class="bg-white rounded-lg border border-amber-100 p-3 text-sm text-gray-700">
                            <div class="font-semibold text-gray-900">{{ $data['message'] ?? 'Jadwal panen telah diperbarui.' }}</div>
                            <div class="mt-1 text-gray-600">
                                {{ $data['fruit_type'] ?? '-' }} @if(!empty($data['grade'])) (Grade {{ $data['grade'] }}) @endif
                                @if(!empty($data['harvest_date'])) · Tanggal panen: {{ \Illuminate\Support\Carbon::parse($data['harvest_date'])->translatedFormat('d M Y') }} @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="bg-white rounded-lg border border-amber-100 p-3 text-sm text-gray-700">
                    Belum ada notifikasi panen. Notifikasi akan muncul saat tanggal panen sudah mendekat.
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Koordinat Lahan</h2>
                    <p class="text-sm text-gray-600">Klik peta untuk mengambil titik atau isi manual.</p>
                </div>
                <form action="{{ route('petani.location.update') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="coordinates" id="coordinates" value="{{ old('coordinates', auth()->user()->latitude && auth()->user()->longitude ? auth()->user()->latitude . ', ' . auth()->user()->longitude : '') }}" placeholder="-8.068314818782161, 112.07810113956172" class="w-full sm:w-80 rounded border-gray-300 min-h-[44px] px-3">
                    <button type="submit" class="min-h-[44px] px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Simpan</button>
                    <button type="button" id="toggle-lock" class="min-h-[44px] px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Kunci Lokasi</button>
                </form>
            </div>
            <div id="petani-map" class="mt-4 h-64 w-full rounded-lg border border-gray-200"></div>
        </div>

        <!-- Skeleton Loader (Desktop Table) -->
        <div x-show="loading" class="hidden md:block animate-pulse bg-white rounded-lg shadow h-64 w-full"></div>

        <!-- Desktop Table -->
        <div x-show="!loading" style="display: none;" class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buah & Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estimasi (Kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Kg</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4">{{ $product->fruit_type }} ({{ $product->grade }})</td>
                        <td class="px-6 py-4">{{ $product->estimated_weight_kg }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 'pending' ? 'yellow' : 'green' }}-100 text-{{ $product->status == 'pending' ? 'yellow' : 'green' }}-800">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('petani.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 min-h-[44px] min-w-[44px] inline-flex items-center justify-center">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div x-show="!loading" style="display: none;" class="md:hidden space-y-4">
            @foreach($products as $product)
            <div class="bg-white p-4 rounded-lg shadow flex flex-col space-y-2">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-900">{{ $product->fruit_type }} ({{ $product->grade }})</span>
                    <span class="text-sm bg-gray-100 px-2 py-1 rounded">{{ ucfirst($product->status) }}</span>
                </div>
                <div class="text-sm text-gray-600">Berat: {{ $product->estimated_weight_kg }} Kg | Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}/kg</div>
                <div class="pt-2 border-t flex justify-end">
                    <a href="{{ route('petani.products.edit', $product) }}" class="min-h-[44px] px-4 bg-gray-100 text-gray-800 rounded-lg inline-flex items-center justify-center font-medium">Edit</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $products->links() }}
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
                lockButton.classList.toggle('hover:bg-gray-200', !state);
                lockButton.classList.toggle('bg-yellow-100', state);
                lockButton.classList.toggle('text-yellow-800', state);
                lockButton.classList.toggle('hover:bg-yellow-200', state);
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
