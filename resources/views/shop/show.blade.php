<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta 
            title="{{ $product->fruit_type }} Grade {{ $product->grade }} - SetiaBuah"
            description="Beli {{ $product->fruit_type }} berkualitas Grade {{ $product->grade }} langsung dari Setia Buah dengan harga Rp {{ number_format($product->price_per_kg, 0,',','.') }} per Kg."
            image="{{ $product->image ? Storage::url($product->image) : asset('logo.png') }}"
            :json-ld="[
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->fruit_type,
                'description' => $product->description ?? 'Buah segar kualitas '. $product->grade,
                'image' => $product->image ? Storage::url($product->image) : asset('logo.png'),
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'IDR',
                    'price' => $product->price_per_kg,
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'availability' => $product->stock_kg > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
                ]
            ]"
        />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Back Navigation Button -->
        <div class="mb-8">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-gray-500 hover:text-brand-600 transition group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Katalog
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <p class="text-sm font-bold">Produk Berhasil Ditambahkan!</p>
                        <p class="text-xs text-brand-600 font-medium mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <a href="{{ route('cart.index') }}" class="text-xs font-extrabold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition shrink-0 shadow-sm">
                    Ke Keranjang Belanja →
                </a>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Main Product Card Panel split-grid -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden mb-10">
            <div class="flex flex-col lg:flex-row items-stretch">
                
                <!-- Left-hand side Image Showcase -->
                <div class="w-full lg:w-1/2 bg-gray-50 flex items-center justify-center p-6 sm:p-10 relative">
                    <!-- Subtle Leaf Accent -->
                    <div class="absolute top-5 right-5 h-8 w-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold text-xs select-none">
                        🌱
                    </div>

                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->fruit_type }}" class="max-h-[360px] object-contain filter drop-shadow-lg transform hover:scale-[1.03] transition-transform duration-300">
                    @else
                        <div class="w-full h-72 bg-gray-200 flex flex-col items-center justify-center text-gray-400 rounded-2xl gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-sm font-bold">Foto Tidak Tersedia</span>
                        </div>
                    @endif
                </div>

                <!-- Right-hand side Purchase Control -->
                <div class="w-full lg:w-1/2 p-6 sm:p-10 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-gray-100">
                    <div>
                        <!-- Elegant Grade Badging -->
                        <div class="mb-3">
                            @if($product->grade === 'A')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-amber-500 to-yellow-400 text-white shadow-md shadow-amber-500/10">
                                    ⭐ Grade A (Premium)
                                </span>
                            @elseif($product->grade === 'B')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-brand-600 to-brand-500 text-white shadow-md shadow-brand-500/10">
                                    🌱 Grade B (Standar)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-indigo-600 to-purple-500 text-white shadow-md shadow-indigo-500/10">
                                    🏭 Grade C (Olahan)
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-heading font-extrabold text-gray-800 tracking-tight leading-tight">
                            {{ $product->fruit_type }}
                        </h1>
                        
                        <!-- Rich Info Section -->
                        <div class="mt-5 space-y-3.5 text-sm text-gray-600">
                            <h3 class="font-bold text-gray-700">Deskripsi Kualitas:</h3>
                            <p class="leading-relaxed font-medium">
                                {{ $product->description ?? 'Buah segar kualitas terbaik dari kelompok tani binaan SetiaBuah. Dipetik langsung pada tingkat kematangan optimal, dibersihkan higienis, serta disimpan pada suhu logistik ideal demi menjaga kesegaran vitamin alami sampai di rumah Anda.' }}
                            </p>
                        </div>

                        <!-- Price Indicator Tag -->
                        <div class="mt-6 sm:mt-8 flex items-baseline gap-1 text-brand-700 bg-brand-50/50 p-4 rounded-2xl border border-brand-100/30">
                            <span class="text-sm font-bold">Rp</span>
                            <span class="text-3xl sm:text-4xl font-black leading-none">{{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                            <span class="text-xs font-extrabold text-gray-400">/ Kilogram (Kg)</span>
                        </div>

                        <!-- Stocks Info -->
                        <div class="mt-4 flex items-center gap-3 text-xs sm:text-sm font-bold">
                            <span class="text-gray-400">Sisa Stok Siap Kirim:</span>
                            <span class="px-2.5 py-1 rounded-lg text-white font-black {{ $product->stock_kg > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                {{ $product->stock_kg }} Kg
                            </span>
                        </div>
                    </div>

                    <!-- Cart Purchase Interaction Form -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        @if(auth('buyer')->check())
                        <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="inventory_id" value="{{ $product->id }}">
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4" x-data="{ qty: 1 }">
                                <div class="space-y-1.5 shrink-0">
                                    <label for="quantity" class="block text-xs font-extrabold text-gray-400 uppercase tracking-widest">Kuantitas Beli (Kg)</label>
                                    <div class="flex items-center">
                                        <input type="number" id="quantity" name="quantity_kg" min="0.5" step="0.5" max="{{ $product->stock_kg }}" x-model="qty" class="min-h-[46px] block max-w-[120px] w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 text-sm font-bold text-center transition bg-white" required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="min-h-[46px] flex-grow text-sm font-extrabold text-white rounded-xl shadow-md transition-all duration-200 flex items-center justify-center gap-2 {{ $product->stock_kg > 0 ? 'bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 hover:shadow-brand-500/20 active:scale-98' : 'bg-gray-300 cursor-not-allowed shadow-none' }}" {{ $product->stock_kg <= 0 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    {{ $product->stock_kg > 0 ? 'Masukkan ke Keranjang' : 'Maaf, Stok Buah Habis' }}
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="bg-amber-50 border border-amber-200/50 rounded-2xl p-5 text-center space-y-3">
                            <div class="flex items-center justify-center gap-2 text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                <p class="text-sm font-bold">Silakan login terlebih dahulu untuk memesan</p>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                <a href="{{ route('buyer.login') }}" class="min-h-[46px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-extrabold text-sm shadow-md transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    Login Pelanggan
                                </a>
                                <a href="{{ route('buyer.register') }}" class="min-h-[46px] inline-flex items-center justify-center px-6 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-bold text-sm transition">
                                    Belum punya akun? Daftar
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- Academic Food Traceability & Quality Control Panel Dashboard -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-500 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <div>
                    <h2 class="font-heading font-extrabold text-gray-800 text-lg sm:text-xl">Food Traceability & Jaminan Mutu</h2>
                    <p class="text-xs text-gray-500">Transparansi rantai pasok dari kebun kelompok tani ke rumah Anda</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Checkpoint 1: Kelayakan QC -->
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100/50 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span> Checkpoint 1
                        </span>
                        <h4 class="font-heading font-extrabold text-gray-800 text-sm">Penjaminan Mutu (QC)</h4>
                        <p class="text-xs leading-relaxed text-gray-500">
                            Produk telah lolos inspeksi kualitas fisik oleh Quality Control. Diuji kesegaran, ketiadaan hama, dan konsistensi warna buah.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-black text-brand-700 bg-brand-100/40 px-2 py-0.5 rounded-md mt-4 w-max">
                        ✓ LULUS INSPEKSI QC
                    </span>
                </div>

                <!-- Checkpoint 2: FEFO Storage -->
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100/50 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span> Checkpoint 2
                        </span>
                        <h4 class="font-heading font-extrabold text-gray-800 text-sm">Pergudangan FEFO</h4>
                        <p class="text-xs leading-relaxed text-gray-500">
                            Penyimpanan logistik menggunakan asas First-Expired-First-Out. Tanggal kadaluarsa dihitung otomatis 14 hari dari waktu panen petani.
                        </p>
                    </div>
                    @if($product->expiry_date)
                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-brand-700 bg-brand-100/40 px-2 py-0.5 rounded-md mt-4 w-max">
                            📅 KADALUARSA: {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-gray-400 bg-gray-200 px-2 py-0.5 rounded-md mt-4 w-max">
                            📅 KADALUARSA: 14 HARI DARI PANEN
                        </span>
                    @endif
                </div>

                <!-- Checkpoint 3: Petani Origin -->
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100/50 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="text-xs font-extrabold text-brand-600 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span> Checkpoint 3
                        </span>
                        <h4 class="font-heading font-extrabold text-gray-800 text-sm">Informasi Produsen</h4>
                        <p class="text-xs leading-relaxed text-gray-500">
                            Disuplai langsung oleh kelompok tani bersertifikat. Lahan pertanian dipetakan secara spasial (GPS Koordinat) guna keamanan rantai pasok.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-black text-brand-700 bg-brand-100/40 px-2 py-0.5 rounded-md mt-4 w-max">
                        🚜 DIRECT FARMER COOPERATIVE
                    </span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>