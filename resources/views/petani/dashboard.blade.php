<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 400)">
        
        <!-- Header Banner Sambutan Premium -->
        <div class="relative bg-gradient-to-br from-brand-700 via-brand-600 to-emerald-600 rounded-3xl p-6 sm:p-8 text-white shadow-premium overflow-hidden mb-8">
            <!-- Decorative Ornaments -->
            <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-15 pointer-events-none">
                <svg viewBox="0 0 100 100" fill="currentColor" class="h-full w-full object-cover">
                    <path d="M50 0 C75 25, 75 75, 50 100 C25 75, 25 25, 50 0 Z" />
                </svg>
            </div>
            <div class="absolute -left-10 -bottom-10 h-32 w-32 bg-white/5 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-white/20 text-white border border-white/30 backdrop-blur-sm shadow-sm">
                        🚜 Portal Kemitraan Petani SetiaBuah
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-heading font-black tracking-tight mt-1">
                        Selamat Datang Kembali, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-100 font-medium max-w-2xl leading-relaxed">
                        Kelola seluruh data estimasi panen buah segar Anda, pantau status penawaran logistik, serta perbarui koordinat lahan pertanian Anda secara real-time.
                    </p>
                </div>
                
                <div class="shrink-0">
                    <a href="{{ route('petani.products.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-emerald-50 text-brand-700 rounded-2xl font-black text-sm shadow-xl hover:shadow-2xl active:scale-95 transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        Buat Estimasi Panen
                    </a>
                </div>
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

        <!-- Grid Dashboard Stats (Premium layout) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            @php
            $statItems = [
                [
                    'label' => 'Total Panen', 
                    'value' => $stats['total'], 
                    'color' => 'slate', 
                    'border_color' => 'border-slate-200/60',
                    'text_color' => 'text-slate-700',
                    'bg_light' => 'bg-slate-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>'
                ],
                [
                    'label' => 'Menunggu', 
                    'value' => $stats['pending'], 
                    'color' => 'amber', 
                    'border_color' => 'border-amber-200/60',
                    'text_color' => 'text-amber-700',
                    'bg_light' => 'bg-amber-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                ],
                [
                    'label' => 'Disetujui', 
                    'value' => $stats['approved'], 
                    'color' => 'blue', 
                    'border_color' => 'border-blue-200/60',
                    'text_color' => 'text-blue-700',
                    'bg_light' => 'bg-blue-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                ],
                [
                    'label' => 'Dalam Proses', 
                    'value' => $stats['in_progress'], 
                    'color' => 'indigo', 
                    'border_color' => 'border-indigo-200/60',
                    'text_color' => 'text-indigo-700',
                    'bg_light' => 'bg-indigo-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5" /></svg>'
                ],
                [
                    'label' => 'Selesai / Lolos', 
                    'value' => $stats['completed'], 
                    'color' => 'emerald', 
                    'border_color' => 'border-emerald-200/60',
                    'text_color' => 'text-emerald-700',
                    'bg_light' => 'bg-emerald-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>'
                ],
                [
                    'label' => 'Ditolak', 
                    'value' => $stats['rejected'], 
                    'color' => 'rose', 
                    'border_color' => 'border-rose-200/60',
                    'text_color' => 'text-rose-700',
                    'bg_light' => 'bg-rose-50/80',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                ],
            ];
            @endphp
            @foreach($statItems as $stat)
            <div class="bg-white border {{ $stat['border_color'] }} rounded-2xl p-4.5 flex flex-col justify-between shadow-premium hover:shadow-premium-hover transform hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between gap-3 mb-2.5">
                    <span class="text-[9px] font-extrabold uppercase tracking-wider {{ $stat['text_color'] }}">
                        {{ $stat['label'] }}
                    </span>
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center {{ $stat['bg_light'] }} {{ $stat['text_color'] }}">
                        {!! $stat['svg'] !!}
                    </div>
                </div>
                <p class="text-3xl font-heading font-black text-gray-800 leading-none">
                    {{ $stat['value'] }}
                </p>
            </div>
            @endforeach
        </div>

        <!-- Section Peta Koordinat Lahan (GIS) -->
        <div class="space-y-6 mb-8">
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                            <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                            Pemetaan Spasial Lahan (GIS)
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Plot atau klik pada peta untuk menentukan titik koordinat lahan pertanian Anda.</p>
                    </div>
                    <form action="{{ route('petani.location.update') }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[10px] font-extrabold text-gray-400">GPS</span>
                            <input type="text" name="coordinates" id="coordinates" value="{{ old('coordinates', auth()->user()->latitude && auth()->user()->longitude ? auth()->user()->latitude . ', ' . auth()->user()->longitude : '') }}" placeholder="-8.06831, 112.07810" class="py-2.5 pl-10 pr-3 w-full max-w-[200px] rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs font-bold text-center transition bg-white" required>
                        </div>
                        <button type="submit" class="py-2.5 text-xs font-extrabold bg-brand-600 hover:bg-brand-700 text-white px-5 rounded-xl shadow-md shadow-brand-600/10 hover:shadow-brand-600/20 active:scale-95 transition">Simpan Lahan</button>
                        <button type="button" id="toggle-lock" class="py-2.5 text-xs font-extrabold bg-amber-500 hover:bg-amber-600 text-white px-5 rounded-xl transition shadow-md shadow-amber-500/10">🔒 Kunci</button>
                    </form>
                </div>
                <div id="petani-map" class="h-72 w-full rounded-2xl border border-gray-150 shadow-inner z-10"></div>
            </div>
        </div>

        <!-- Tabel Daftar Estimasi Panen -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-5 sm:p-6">
            <h2 class="font-heading font-extrabold text-gray-800 text-base mb-5 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                Daftar Estimasi Panen Anda
            </h2>

            <!-- Desktop View -->
            <div x-show="!loading" style="display: none;" class="hidden md:block border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">Komoditas</th>
                            <th class="px-6 py-4 text-left">Estimasi Berat</th>
                            <th class="px-6 py-4 text-left">Harga Penawaran</th>
                            <th class="px-6 py-4 text-left">Status Alur</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 shadow-sm">
                                    @else
                                        <span class="h-10 w-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">🥑</span>
                                    @endif
                                    <div>
                                        <span class="block text-gray-800 font-extrabold">{{ $product->fruit_type }}</span>
                                        @if($product->grade)
                                            <span class="block text-[10px] text-gray-400 font-extrabold uppercase mt-0.5">Grade {{ $product->grade }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ number_format($product->estimated_weight_kg, 1, ',', '.') }} <span class="text-xs text-gray-400">Kg</span>
                            </td>
                            <td class="px-6 py-4 text-brand-700 font-bold">
                                Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 inline-flex text-[10px] leading-none font-extrabold rounded-md uppercase bg-{{ $product->status_color }}-100 text-{{ $product->status_color }}-800 border border-{{ $product->status_color }}-200/50">
                                    {{ $product->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($product->status === 'pending')
                                        <a href="{{ route('petani.products.edit', $product) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Edit Estimasi Panen">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus estimasi panen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Estimasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                                                           @else
                                        @if($product->qcReports()->exists())
                                            @php
                                                $totalAccepted = $product->qcReports->sum('actual_weight_kg') ?? 0;
                                                $totalRejected = $product->qcReports->sum('rejected_weight_kg') ?? 0;
                                                $totalEarnings = $product->qcReports->sum(function($r) {
                                                    return $r->actual_weight_kg * $r->final_price_per_kg;
                                                });
                                                $qcNotes = optional($product->qcReports->first())->notes;
                                                
                                                if ($totalAccepted > 0 && $totalRejected > 0) {
                                                    $modalStatusLabel = 'Lolos Sebagian';
                                                    $modalStatusColor = 'amber';
                                                } elseif ($totalAccepted > 0) {
                                                    $modalStatusLabel = 'Lolos Sepenuhnya';
                                                    $modalStatusColor = 'emerald';
                                                } else {
                                                    $modalStatusLabel = 'Diretur Penuh';
                                                    $modalStatusColor = 'rose';
                                                }
                                            @endphp
                                            <div x-data="{ openQcModal: false }">
                                                <button @click="openQcModal = true" type="button" class="py-1.5 px-3 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/50 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm" title="Lihat Laporan Quality Control (QC)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                                    <span>Detail QC</span>
                                                </button>
 
                                                <!-- QC Modal -->
                                                <div x-show="openQcModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                                        <div x-show="openQcModal" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="openQcModal = false"></div>
 
                                                        <div x-show="openQcModal" 
                                                             x-transition:enter="transition ease-out duration-300"
                                                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                             x-transition:leave="transition ease-in duration-200"
                                                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                             class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-premium rounded-3xl border border-gray-100 z-10">
                                                            
                                                            <div class="flex justify-between items-start border-b border-gray-100 pb-3.5 mb-4">
                                                                <div>
                                                                    <div class="flex items-center gap-2">
                                                                        <h3 class="text-base font-heading font-extrabold text-gray-800" id="modal-title">Laporan Hasil QC</h3>
                                                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-{{ $modalStatusColor }}-100 text-{{ $modalStatusColor }}-800 border border-{{ $modalStatusColor }}-200/50">
                                                                            {{ $modalStatusLabel }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-[10px] text-gray-400 font-extrabold uppercase mt-1">{{ $product->updated_at->format('d M Y - H:i') }}</p>
                                                                </div>
                                                                <button @click="openQcModal = false" class="text-gray-400 hover:text-gray-650 transition p-1 hover:bg-gray-50 rounded-lg">
                                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                                </button>
                                                            </div>

                                                            <!-- Info Lahan / Estimasi Asal -->
                                                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-3.5 mb-4 text-xs">
                                                                <div class="grid grid-cols-2 gap-2 text-gray-600">
                                                                    <div>
                                                                        <span class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold">Komoditas</span>
                                                                        <span class="font-extrabold text-gray-800 text-xs">{{ $product->fruit_type }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold">Estimasi Awal</span>
                                                                        <span class="font-extrabold text-gray-800 text-xs">{{ number_format($product->estimated_weight_kg, 1, ',', '.') }} Kg</span>
                                                                    </div>
                                                                </div>
                                                            </div>
 
                                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                                <div class="bg-emerald-50/40 p-4 rounded-2xl border border-emerald-100/50 relative overflow-hidden group hover:border-emerald-250 transition-all duration-300">
                                                                    <div class="absolute -right-2 -bottom-2 opacity-5 text-emerald-600 group-hover:scale-110 transition-transform">
                                                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                                    </div>
                                                                    <p class="text-[9px] text-emerald-700 font-extrabold uppercase tracking-wider mb-1">Lolos Gudang</p>
                                                                    <p class="text-2xl font-black text-emerald-800 leading-none">
                                                                        {{ number_format($totalAccepted, 1, ',', '.') }} 
                                                                        <span class="text-xs font-bold text-emerald-600">KG</span>
                                                                    </p>
                                                                </div>
 
                                                                <div class="bg-rose-50/40 p-4 rounded-2xl border border-rose-100/50 relative overflow-hidden group hover:border-rose-250 transition-all duration-300">
                                                                    <div class="absolute -right-2 -bottom-2 opacity-5 text-rose-600 group-hover:scale-110 transition-transform">
                                                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                                    </div>
                                                                    <p class="text-[9px] text-rose-700 font-extrabold uppercase tracking-wider mb-1">Diretur (Reject)</p>
                                                                    <p class="text-2xl font-black text-rose-800 leading-none">
                                                                        {{ number_format($totalRejected, 1, ',', '.') }} 
                                                                        <span class="text-xs font-bold text-rose-600">KG</span>
                                                                    </p>
                                                                </div>
                                                            </div>
 
                                                            <!-- Detail Grading / Hasil Klasifikasi Buah -->
                                                            <div class="space-y-2.5 mb-4">
                                                                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Rincian Klasifikasi & Hasil Panen</h4>
                                                                <div class="border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-100 text-xs shadow-sm bg-white">
                                                                    @php $hasGrades = false; @endphp
                                                                    @foreach($product->qcReports as $report)
                                                                        @if($report->status === 'accepted' && $report->actual_weight_kg > 0)
                                                                            @php 
                                                                                $hasGrades = true; 
                                                                                $subtotal = $report->actual_weight_kg * $report->final_price_per_kg;
                                                                            @endphp
                                                                            <div class="flex items-center justify-between p-3.5 hover:bg-gray-50/50 transition">
                                                                                <div class="flex flex-col gap-1">
                                                                                    @if($report->final_grade === 'A')
                                                                                        <span class="w-max px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-100 text-amber-800 font-extrabold text-[9px] uppercase tracking-wider flex items-center gap-1">
                                                                                            ⭐ Grade A
                                                                                        </span>
                                                                                    @elseif($report->final_grade === 'B')
                                                                                        <span class="w-max px-2.5 py-1 rounded-lg bg-brand-50 border border-brand-100 text-brand-850 font-extrabold text-[9px] uppercase tracking-wider flex items-center gap-1">
                                                                                            🌱 Grade B
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="w-max px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-800 font-extrabold text-[9px] uppercase tracking-wider flex items-center gap-1">
                                                                                            🍂 Grade C
                                                                                        </span>
                                                                                    @endif
                                                                                    <span class="text-[10px] text-gray-400 font-medium">Harga: Rp {{ number_format($report->final_price_per_kg, 0, ',', '.') }} / Kg</span>
                                                                                </div>
                                                                                <div class="text-right">
                                                                                    <p class="text-gray-800 font-black text-sm">{{ number_format($report->actual_weight_kg, 1, ',', '.') }} Kg</p>
                                                                                    <p class="text-[11px] text-brand-700 font-black">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    
                                                                    @if(!$hasGrades)
                                                                        <div class="p-4 text-center text-gray-400 italic text-xs bg-gray-50/20">
                                                                            Tidak ada komoditas yang lolos QC (Seluruhnya Ditolak).
                                                                        </div>
                                                                    @else
                                                                        <!-- Total Earnings row -->
                                                                        <div class="flex items-center justify-between p-3.5 bg-brand-50/30 border-t border-brand-100/50">
                                                                            <span class="font-extrabold text-brand-900 text-xs">Total Pendapatan Lolos QC:</span>
                                                                            <span class="font-black text-brand-700 text-sm">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
 
                                                            @if($totalRejected > 0)
                                                                <div class="bg-rose-50/40 p-4 rounded-2xl text-xs mb-4 border border-rose-100/60 shadow-inner">
                                                                    <div class="flex items-center gap-1.5 text-rose-700 font-bold mb-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                                        <span class="text-[10px] uppercase tracking-widest font-extrabold">Rincian Barang Reject (Diretur):</span>
                                                                    </div>
                                                                    <div class="space-y-1.5 text-gray-700 leading-relaxed font-semibold">
                                                                        <p>Berat yang Diretur: <span class="text-rose-600 font-black">{{ number_format($totalRejected, 1, ',', '.') }} Kg</span></p>
                                                                        <div class="pt-1.5 border-t border-rose-100/50">
                                                                            <p class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold mb-0.5">Alasan Pengembalian / Catatan:</p>
                                                                            <p class="italic text-gray-800 bg-white/70 p-2.5 rounded-xl border border-rose-100/20 font-medium">
                                                                                "{{ $qcNotes ?? 'Tidak ada catatan khusus.' }}"
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="bg-emerald-50/30 p-3.5 rounded-2xl text-xs mb-4 border border-emerald-100/40 flex items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                    <span class="text-emerald-800 font-bold">Seluruh barang berhasil lolos QC dengan baik.</span>
                                                                </div>
                                                            @endif
 
                                                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                                                                <button @click="openQcModal = false" type="button" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-brand-500/10">
                                                                    Tutup Laporan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($product->status === 'rejected')
                                            <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data estimasi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Data">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div x-show="!loading" style="display: none;" class="md:hidden space-y-4">
                @foreach($products as $product)
                <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-2xl space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2.5">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 shadow-sm">
                            @else
                                <span class="h-10 w-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">🥑</span>
                            @endif
                            <div>
                                <span class="block font-heading font-extrabold text-gray-800 text-sm">{{ $product->fruit_type }}</span>
                                <span class="block text-[9px] text-gray-400 font-extrabold uppercase mt-0.5">Estimasi: {{ number_format($product->estimated_weight_kg, 1, ',', '.') }} Kg</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-{{ $product->status_color }}-100 text-{{ $product->status_color }}-800 border border-{{ $product->status_color }}-200/50">{{ $product->status_label }}</span>
                    </div>
                    <div class="text-xs text-gray-500 font-semibold">
                        <p>Harga Penawaran: <span class="text-brand-700 font-bold">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }} / Kg</span></p>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200/50 flex justify-end gap-2">
                        @if($product->status === 'pending')
                            <a href="{{ route('petani.products.edit', $product) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Edit Estimasi Panen">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus estimasi panen ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Estimasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                        @else
                            @if($product->qcReports()->exists())
                                @php
                                    $totalAcceptedMobile = $product->qcReports->sum('actual_weight_kg') ?? 0;
                                    $totalRejectedMobile = $product->qcReports->sum('rejected_weight_kg') ?? 0;
                                    $totalEarningsMobile = $product->qcReports->sum(function($r) {
                                        return $r->actual_weight_kg * $r->final_price_per_kg;
                                    });
                                    $qcNotesMobile = optional($product->qcReports->first())->notes;
                                    
                                    if ($totalAcceptedMobile > 0 && $totalRejectedMobile > 0) {
                                        $modalStatusLabelMobile = 'Lolos Sebagian';
                                        $modalStatusColorMobile = 'amber';
                                    } elseif ($totalAcceptedMobile > 0) {
                                        $modalStatusLabelMobile = 'Lolos Sepenuhnya';
                                        $modalStatusColorMobile = 'emerald';
                                    } else {
                                        $modalStatusLabelMobile = 'Diretur Penuh';
                                        $modalStatusColorMobile = 'rose';
                                    }
                                @endphp
                                <div x-data="{ openQcModalMobile: false }">
                                    <button @click="openQcModalMobile = true" type="button" class="py-1.5 px-3 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/50 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm" title="Lihat Laporan Quality Control (QC)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        <span>Detail QC</span>
                                    </button>
 
                                    <!-- QC Mobile Modal -->
                                    <div x-show="openQcModalMobile" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-center justify-center min-h-screen px-4 text-center">
                                            <div x-show="openQcModalMobile" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openQcModalMobile = false"></div>
                                            <div x-show="openQcModalMobile" class="relative inline-block w-full max-w-sm p-6 text-left align-middle bg-white shadow-premium rounded-3xl border border-gray-100 z-10">
                                                
                                                <div class="flex justify-between items-start border-b border-gray-100 pb-3 mb-4">
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <h3 class="text-base font-heading font-extrabold text-gray-800">Laporan Hasil QC</h3>
                                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-{{ $modalStatusColorMobile }}-100 text-{{ $modalStatusColorMobile }}-800 border border-{{ $modalStatusColorMobile }}-200/50">
                                                                {{ $modalStatusLabelMobile }}
                                                            </span>
                                                        </div>
                                                        <p class="text-[10px] text-gray-400 font-extrabold uppercase mt-1">{{ $product->updated_at->format('d M Y - H:i') }}</p>
                                                    </div>
                                                    <button @click="openQcModalMobile = false" class="text-gray-400 hover:text-gray-650 transition p-1 hover:bg-gray-50 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>

                                                <!-- Info Lahan / Estimasi Asal Mobile -->
                                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-3.5 mb-4 text-xs">
                                                    <div class="grid grid-cols-2 gap-2 text-gray-600">
                                                        <div>
                                                            <span class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold">Komoditas</span>
                                                            <span class="font-extrabold text-gray-800 text-xs">{{ $product->fruit_type }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold">Estimasi Awal</span>
                                                            <span class="font-extrabold text-gray-800 text-xs">{{ number_format($product->estimated_weight_kg, 1, ',', '.') }} Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
 
                                                <div class="grid grid-cols-2 gap-3 mb-4">
                                                    <div class="bg-emerald-50/40 p-3.5 rounded-2xl border border-emerald-100/50 relative overflow-hidden group">
                                                        <p class="text-[9px] text-emerald-700 font-extrabold uppercase tracking-wider mb-1">Lolos Gudang</p>
                                                        <p class="text-xl font-black text-emerald-800 leading-none">
                                                            {{ number_format($totalAcceptedMobile, 1, ',', '.') }} 
                                                            <span class="text-[10px] font-bold text-emerald-600">KG</span>
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="bg-rose-50/40 p-3.5 rounded-2xl border border-rose-100/50 relative overflow-hidden group">
                                                        <p class="text-[9px] text-rose-700 font-extrabold uppercase tracking-wider mb-1">Diretur (Reject)</p>
                                                        <p class="text-xl font-black text-rose-800 leading-none">
                                                            {{ number_format($totalRejectedMobile, 1, ',', '.') }} 
                                                            <span class="text-[10px] font-bold text-rose-600">KG</span>
                                                        </p>
                                                    </div>
                                                </div>
 
                                                <!-- Detail Grading / Hasil Klasifikasi Buah Mobile -->
                                                <div class="space-y-2 mb-4">
                                                    <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Rincian Klasifikasi & Hasil Panen</h4>
                                                    <div class="border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-100 text-xs shadow-sm bg-white">
                                                        @php $hasGradesMobile = false; @endphp
                                                        @foreach($product->qcReports as $report)
                                                            @if($report->status === 'accepted' && $report->actual_weight_kg > 0)
                                                                @php 
                                                                    $hasGradesMobile = true; 
                                                                    $subtotalMobile = $report->actual_weight_kg * $report->final_price_per_kg;
                                                                @endphp
                                                                <div class="flex items-center justify-between p-3 hover:bg-gray-50/50 transition">
                                                                    <div class="flex flex-col gap-1">
                                                                        @if($report->final_grade === 'A')
                                                                            <span class="w-max px-2 py-0.5 rounded bg-amber-50 border border-amber-100 text-amber-800 font-extrabold text-[9px] uppercase tracking-wider">⭐ Grade A</span>
                                                                        @elseif($report->final_grade === 'B')
                                                                            <span class="w-max px-2 py-0.5 rounded bg-brand-50 border border-brand-100 text-brand-850 font-extrabold text-[9px] uppercase tracking-wider">🌱 Grade B</span>
                                                                        @else
                                                                            <span class="w-max px-2 py-0.5 rounded bg-indigo-50 border border-indigo-100 text-indigo-800 font-extrabold text-[9px] uppercase tracking-wider">🍂 Grade C</span>
                                                                        @endif
                                                                        <span class="text-[9px] text-gray-400 font-medium">Harga: Rp {{ number_format($report->final_price_per_kg, 0, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <p class="text-gray-800 font-black text-xs">{{ number_format($report->actual_weight_kg, 1, ',', '.') }} Kg</p>
                                                                        <p class="text-[10px] text-brand-700 font-black">Rp {{ number_format($subtotalMobile, 0, ',', '.') }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        
                                                        @if(!$hasGradesMobile)
                                                            <div class="p-3.5 text-center text-gray-400 italic text-xs bg-gray-50/20">
                                                                Tidak ada komoditas yang lolos QC (Seluruhnya Ditolak).
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-between p-3 bg-brand-50/30 border-t border-brand-100/50">
                                                                <span class="font-extrabold text-brand-900 text-xs">Total Pendapatan:</span>
                                                                <span class="font-black text-brand-700 text-xs">Rp {{ number_format($totalEarningsMobile, 0, ',', '.') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
 
                                                @if($totalRejectedMobile > 0)
                                                    <div class="bg-rose-50/40 p-3.5 rounded-2xl text-xs mb-4 border border-rose-100/60 shadow-inner">
                                                        <div class="flex items-center gap-1.5 text-rose-700 font-bold mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                            <span class="text-[9px] uppercase tracking-widest font-extrabold">Rincian Barang Reject:</span>
                                                        </div>
                                                        <div class="space-y-1.5 text-gray-700 leading-relaxed font-semibold">
                                                            <p>Berat Diretur: <span class="text-rose-600 font-black">{{ number_format($totalRejectedMobile, 1, ',', '.') }} Kg</span></p>
                                                            <div class="pt-1.5 border-t border-rose-100/50">
                                                                <p class="text-[9px] uppercase tracking-widest text-gray-400 block font-bold mb-0.5">Alasan/Catatan:</p>
                                                                <p class="italic text-gray-800 bg-white/70 p-2 rounded-xl border border-rose-100/20 font-medium">
                                                                    "{{ $qcNotesMobile ?? 'Tidak ada catatan khusus.' }}"
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="bg-emerald-50/30 p-3 rounded-2xl text-xs mb-4 border border-emerald-100/40 flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        <span class="text-emerald-800 font-bold">Seluruh barang lolos QC.</span>
                                                    </div>
                                                @endif
 
                                                <div class="pt-2">
                                                    <button @click="openQcModalMobile = false" type="button" class="w-full rounded-xl border border-gray-300 py-2.5 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($product->status === 'rejected')
                                <form action="{{ route('petani.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data estimasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100/50 rounded-xl text-xs font-bold transition flex items-center justify-center" title="Hapus Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endif
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
                lockButton.textContent = isLocked ? '🔓 Buka Kunci' : '🔒 Kunci';
                if (isLocked) {
                    lockButton.className = 'py-2.5 text-xs font-extrabold bg-brand-50 border border-brand-200 text-brand-700 px-5 rounded-xl transition';
                } else {
                    lockButton.className = 'py-2.5 text-xs font-extrabold bg-amber-500 hover:bg-amber-600 text-white px-5 rounded-xl transition shadow-md shadow-amber-500/10';
                }
            });
            
            map.on('click', function (e) {
                if (isLocked) return;
                coordsInput.value = e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
                if (marker) marker.setLatLng(e.latlng); else marker = L.marker(e.latlng).addTo(map);
            });
        });
    </script>
</x-app-layout>