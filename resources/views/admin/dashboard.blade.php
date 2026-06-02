<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Admin Title bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-200/50 px-2.5 py-1 rounded-md">
                    Konsol Administrasi & Sistem QC
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Admin Dashboard</h1>
                <p class="text-xs text-gray-500">Kelola otorisasi mitra kelompok tani, pantau pesanan e-commerce, dan tinjau sebaran lahan.</p>
            </div>
            
            <div class="shrink-0 flex items-center">
                <button onclick="document.getElementById('addPetaniModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition duration-150 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Registrasi Akun Petani
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span class="font-bold text-sm">Gagal Melakukan Aksi:</span>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Glowing Statistical Panel Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-10">
            <!-- Metric 1: Shipped Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="block group">
                <div class="bg-gradient-to-tr from-indigo-700 via-indigo-600 to-indigo-500 text-white p-6 rounded-2xl shadow-md group-hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative overflow-hidden h-full">
                    <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-200">Pesanan Dalam Pengiriman</p>
                    <p class="text-4xl font-heading font-black mt-3 leading-none">{{ $ordersToday }}</p>
                </div>
            </a>
            
            <!-- Metric 2: Warehouse Stock -->
            <div class="bg-gradient-to-tr from-brand-600 to-emerald-500 text-white p-6 rounded-2xl shadow-md relative overflow-hidden h-full">
                <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                <p class="text-[9px] font-extrabold uppercase tracking-widest text-brand-100">Total Stok Buah di Gudang</p>
                <p class="text-4xl font-heading font-black mt-3 leading-none">{{ number_format($totalStock, 1, ',', '.') }} <span class="text-xs font-extrabold text-brand-100">Kg</span></p>
            </div>
            
            <!-- Metric 3: Pending QC -->
            <a href="{{ route('admin.qc.queue') }}" class="block group">
                <div class="bg-gradient-to-tr from-amber-600 to-orange-500 text-white p-6 rounded-2xl shadow-md group-hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative overflow-hidden h-full">
                    <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-amber-100">Antrean Uji Mutu (QC)</p>
                    <p class="text-4xl font-heading font-black mt-3 leading-none">{{ $pendingQC }} <span class="text-xs font-extrabold text-amber-200">Panen</span></p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Column 1 & 2: Orders & Maps (Take 2 columns) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Paid Orders Awaiting packaging table list -->
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-brand-50/50 to-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                                Pesanan Pembeli (Akan Dikemas)
                            </h2>
                            <p class="text-xs text-gray-400 mt-0.5">Daftar transaksi lunas dari konsumen yang siap Anda pasangi resi pengiriman.</p>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <span class="bg-brand-50 border border-brand-200/50 text-brand-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">
                                {{ $totalPaidOrders }} Pesanan Lunas
                            </span>
                            <a href="{{ route('admin.orders.history') }}" class="text-xs font-extrabold px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition border border-indigo-200/50">
                                Riwayat Pembelian
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-extrabold px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <div class="p-0 overflow-x-auto">
                        @if($paidOrders->isEmpty())
                            <div class="px-6 py-12 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-xs font-bold leading-relaxed max-w-xs mx-auto">Semua pesanan lunas saat ini sudah terkirim. Tidak ada pengemasan tertunda!</p>
                            </div>
                        @else
                            <table class="w-full text-sm divide-y divide-gray-100">
                                <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Order ID</th>
                                        <th class="px-6 py-4 text-left">Nama Pembeli</th>
                                        <th class="px-6 py-4 text-left">Total Tagihan</th>
                                        <th class="px-6 py-4 text-left">Tanggal Lunas</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                                    @foreach($paidOrders as $order)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 font-heading font-extrabold text-indigo-600">
                                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <p class="text-gray-800 font-extrabold">{{ $order->customer_name }}</p>
                                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->customer_phone }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-brand-700 font-bold">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-xs font-medium text-gray-400">
                                                {{ $order->created_at->format('d M, H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="py-1 inline-flex items-center justify-center px-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition">
                                                    Proses Paket
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- GIS Farmers coordinate mapping panel -->
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                                Sebaran Lokasi Lahan Petani
                            </h2>
                            <p class="text-xs text-gray-400 mt-0.5">Pemetaan titik spasial kebun kelompok tani berdasarkan metadata GIS.</p>
                        </div>
                        <span class="bg-indigo-50 border border-indigo-200/50 text-indigo-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">
                            {{ $petaniLocations->count() }} Lahan Terdaftar
                        </span>
                    </div>

                    <div id="admin-petani-map" class="h-80 w-full rounded-2xl border border-gray-150 shadow-inner z-10"></div>
                </div>

            </div>

            <!-- Column 3: Farmers list panel -->
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white flex items-center justify-between">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base">Registrasi Petani</h2>
                        <p class="text-[10px] text-gray-400 mt-0.5">Metadata akun mitra aktif</p>
                    </div>
                    <span class="bg-brand-50 border border-brand-200/50 text-brand-700 text-xs font-extrabold px-2.5 py-1 rounded-xl">
                        {{ $petanis->count() }} Orang
                    </span>
                </div>
                
                <div class="p-0">
                    <ul class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto pr-1">
                        @forelse($petanis as $petani)
                        <li class="p-4 hover:bg-gray-50/50 transition flex items-center justify-between gap-4 font-semibold text-xs">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0 h-9 w-9 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-500 text-white flex items-center justify-center font-extrabold text-sm shadow shadow-brand-500/10">
                                    {{ strtoupper(substr($petani->name, 0, 1)) }}
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="text-gray-800 font-extrabold truncate">{{ $petani->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $petani->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('admin.petani.edit', $petani) }}" class="py-1 inline-flex items-center justify-center px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.petani.destroy', $petani) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun petani ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-1 inline-flex items-center justify-center px-3 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl font-bold transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center text-gray-400 font-bold text-xs leading-relaxed">
                            ☕ Belum ada akun petani terdaftar.<br>Silakan lakukan registrasi.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

    </div>

    <!-- Leaflet GIS Map resources -->
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
                    if (!isNaN(lat) && !isNaN(lng)) {
                        bounds.push([lat, lng]);
                        L.marker([lat, lng]).addTo(map).bindPopup("<b class='font-heading text-sm'>" + item.name + "</b><br><span class='text-xs text-gray-400'>Lahan Kelompok Tani</span>");
                    }
                });
                if (bounds.length) {
                    map.fitBounds(bounds, { padding: [40, 40] });
                }
            }
        });
    </script>

    <!-- Beautiful Tambah Petani Modal overlay -->
    <div id="addPetaniModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Transparent Blur Overlay -->
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('addPetaniModal').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel wrapper -->
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-150">
                <form action="{{ route('admin.petani.store') }}" method="POST">
                    @csrf
                    
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-7">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-50 sm:mx-0 sm:h-10 sm:w-10 text-indigo-600">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full space-y-1">
                                <h3 class="text-lg font-heading font-extrabold text-gray-800" id="modal-title">
                                    Tambah Akun Petani Baru
                                </h3>
                                <p class="text-xs text-gray-400">Buatkan otorisasi kredensial login bagi kelompok tani mitra baru.</p>
                                
                                <div class="mt-6 space-y-4 pt-2">
                                    <div class="space-y-1">
                                        <label for="name" class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Lengkap Petani</label>
                                        <input type="text" name="name" id="name" placeholder="Contoh: Pak Tani Mulyo" required class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition">
                                    </div>
                                    <div class="space-y-1">
                                        <label for="email" class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Alamat Email Mitra</label>
                                        <input type="email" name="email" id="email" placeholder="Contoh: tanimulyo@gmail.com" required class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition">
                                    </div>
                                    <div class="space-y-1">
                                        <label for="password" class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kata Sandi Akses</label>
                                        <input type="password" name="password" id="password" required class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-mono" minlength="8">
                                        <p class="text-[10px] text-gray-400 mt-1 font-medium">Panjang sandi minimal 8 karakter demi keamanan kredensial.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-sm font-bold text-white focus:outline-none sm:ml-2 sm:w-auto transition active:scale-95 duration-150">
                            Simpan Akun
                        </button>
                        <button type="button" onclick="document.getElementById('addPetaniModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                            Batal
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>