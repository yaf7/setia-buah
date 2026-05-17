<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Admin Dashboard</h1>
            <button onclick="document.getElementById('addPetaniModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Petani
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-800">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ringkasan Kartu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="block">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 h-full">
                    <p class="text-sm font-medium uppercase tracking-wider opacity-80 mb-1">Pesanan Dalam Pengiriman</p>
                    <p class="text-4xl font-bold">{{ $ordersToday }}</p>
                </div>
            </a>
            
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 h-full">
                <p class="text-sm font-medium uppercase tracking-wider opacity-80 mb-1">Total Stok (Kg)</p>
                <p class="text-4xl font-bold">{{ number_format($totalStock, 1, ',', '.') }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-amber-400 to-amber-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 h-full">
                <p class="text-sm font-medium uppercase tracking-wider opacity-80 mb-1">Butuh QC (Pending)</p>
                <p class="text-4xl font-bold">{{ $pendingQC }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <!-- Pesanan Pembeli yang Sudah Dibayar -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-red-50 to-orange-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pesanan Pembeli (Menunggu Proses)</h2>
                        <p class="text-sm text-gray-600 mt-1">Pesanan yang sudah dibayar dan siap diproses</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-red-100 text-red-800 text-sm font-bold px-3 py-1 rounded-full">{{ $totalPaidOrders }} Pesanan</span>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                            Lihat Semua →
                        </a>
                    </div>
                </div>
                <div class="p-0">
                    @if($paidOrders->isEmpty())
                        <div class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">Tidak ada pesanan yang perlu diproses.</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead class="border-b bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold">No. Pesanan</th>
                                    <th class="px-6 py-3 text-left font-semibold">Pembeli</th>
                                    <th class="px-6 py-3 text-left font-semibold">Total</th>
                                    <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                                    <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($paidOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-semibold text-indigo-600">#{{ $order->id }}</td>
                                        <td class="px-6 py-3">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-600">{{ $order->created_at->format('d M H:i') }}</td>
                                        <td class="px-6 py-3 text-center">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold px-3 py-1.5 rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200 inline-block">
                                                Proses
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Akun Petani</h2>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $petanis->count() }} Orang</span>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
                        @forelse($petanis as $petani)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                                    {{ strtoupper(substr($petani->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $petani->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $petani->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.petani.edit', $petani) }}" class="text-xs font-semibold px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100">Edit</a>
                                <form action="{{ route('admin.petani.destroy', $petani) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-md bg-red-50 text-red-700 hover:bg-red-100">Hapus</button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-8 text-center text-gray-500">
                            Belum ada akun petani terdaftar.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Peta Koordinat Petani</h2>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $petaniLocations->count() }} Titik</span>
                </div>
                <div class="p-6">
                    <div id="admin-petani-map" class="h-80 w-full rounded-lg border border-gray-200"></div>
                    @if($petaniLocations->isEmpty())
                        <p class="mt-3 text-sm text-gray-500">Belum ada koordinat petani yang tersimpan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('admin-petani-map').setView([-2.5489, 118.0149], 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var locations = @json($petaniLocations);
            if (locations.length) {
                var bounds = [];
                locations.forEach(function (item) {
                    var lat = parseFloat(item.latitude);
                    var lng = parseFloat(item.longitude);
                    bounds.push([lat, lng]);
                    L.marker([lat, lng]).addTo(map).bindPopup(item.name);
                });
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        });
    </script>

    <!-- Modal Tambah Petani -->
    <div id="addPetaniModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('addPetaniModal').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.petani.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Tambah Akun Petani Baru
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" required class="mt-1 flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                        <input type="email" name="email" id="email" required class="mt-1 flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                                        <input type="password" name="password" id="password" required class="mt-1 flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2" minlength="8">
                                        <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Akun
                        </button>
                        <button type="button" onclick="document.getElementById('addPetaniModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>