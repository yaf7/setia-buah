<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200/50 px-2.5 py-1 rounded-md">
                    Manajemen Mitra
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Data Petani</h1>
                <p class="text-xs text-gray-500">Kelola pendaftaran mitra petani dan pantau sebaran lahan mereka.</p>
            </div>
            
            <div class="shrink-0 flex items-center gap-2">
                <button onclick="document.getElementById('addPetaniModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition duration-150 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Registrasi Petani
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
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Column 1 & 2: Map -->
            <div class="lg:col-span-2 space-y-8">
                <!-- GIS Map -->
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                                Sebaran Lokasi Lahan Petani
                            </h2>
                            <p class="text-[10px] text-gray-400 mt-1">Peta geografis sumber komoditas buah.</p>
                        </div>
                        <span class="bg-indigo-50 border border-indigo-200/50 text-indigo-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">{{ $petaniLocations->count() }} Lahan Terpetakan</span>
                    </div>
                    <div id="admin-petani-map" class="h-[500px] w-full rounded-2xl border border-gray-150 shadow-inner z-10"></div>
                </div>
            </div>

            <!-- Column 3: Farmers list -->
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white flex items-center justify-between">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base">Daftar Mitra Petani</h2>
                        <p class="text-[10px] text-gray-400 mt-0.5">Petani yang terdaftar</p>
                    </div>
                    <span class="bg-brand-50 border border-brand-200/50 text-brand-700 text-xs font-extrabold px-2.5 py-1 rounded-xl">{{ $petanis->count() }} Orang</span>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto pr-1">
                        @forelse($petanis as $petani)
                        <li class="p-4 hover:bg-gray-50/50 transition flex flex-col gap-3 font-semibold text-xs border-b border-gray-50 last:border-0">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-500 text-white flex items-center justify-center font-extrabold text-base shadow shadow-brand-500/10">{{ strtoupper(substr($petani->name, 0, 1)) }}</div>
                                <div class="ml-3 min-w-0">
                                    <p class="text-gray-800 font-extrabold truncate text-sm">{{ $petani->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $petani->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 w-full mt-1">
                                <a href="{{ route('admin.petani.edit', $petani) }}" class="flex-1 py-1.5 inline-flex justify-center items-center px-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-bold transition">Edit</a>
                                <form action="{{ route('admin.petani.destroy', $petani) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus akun petani ini beserta seluruh datanya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-1.5 inline-flex justify-center items-center px-3 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl font-bold transition">Hapus</button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center text-gray-400 font-bold text-xs flex flex-col items-center">
                            <span class="text-3xl mb-2">👩‍🌾</span>
                            Belum ada petani terdaftar.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet GIS Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('admin-petani-map').setView([-2.5489, 118.0149], 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
            var locations = @json($petaniLocations);
            if (locations.length) {
                var bounds = [];
                locations.forEach(function (item) {
                    var lat = parseFloat(item.latitude), lng = parseFloat(item.longitude);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        bounds.push([lat, lng]);
                        L.marker([lat, lng]).addTo(map).bindPopup("<b>" + item.name + "</b><br><span class='text-xs'>Lahan Petani</span>");
                    }
                });
                if (bounds.length) map.fitBounds(bounds, { padding: [40, 40] });
            }
        });
    </script>

    <!-- Add Petani Modal -->
    <div id="addPetaniModal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="document.getElementById('addPetaniModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-150">
                <form action="{{ route('admin.petani.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-7">
                        <h3 class="text-lg font-heading font-extrabold text-gray-800">Tambah Akun Petani Baru</h3>
                        <p class="text-xs text-gray-400 mb-6">Buatkan kredensial login bagi mitra baru.</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                                <input type="text" name="name" required class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Email</label>
                                <input type="email" name="email" required class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                                <input type="password" name="password" required minlength="8" class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-mono">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-sm font-bold text-white transition">Simpan</button>
                        <button type="button" onclick="document.getElementById('addPetaniModal').classList.add('hidden')" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-200 px-4 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
