<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Katalog Buah Segar Pilihan - SetiaBuah" />
    </x-slot>

    <!-- ========================================== -->
    <!-- BANNER HERO (SLIDESHOW GAMBAR BERJALAN)    -->
    <!-- ========================================== -->
    <div 
        x-data="{
            activeSlide: 0,
            slides: [
                '{{ asset('images/2.jpg') }}',
                '{{ asset('images/3.jpg') }}',
                '{{ asset('images/4.jpeg') }}'
            ],
            init() {
                setInterval(() => {
                    this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
                }, 4000);
            }
        }"
        class="relative overflow-hidden pt-32 pb-14 sm:pt-44 sm:pb-20 mb-12 shadow-xl min-h-[480px] flex items-center bg-slate-950 border-b border-slate-900"
    >
        
        <!-- Looping Gambar Slider -->
        <template x-for="(slide, index) in slides" :key="index">
            <img 
                x-show="activeSlide === index"
                :src="slide"
                alt="Slider Image"
                class="absolute inset-0 w-full h-full object-cover z-0 opacity-40 transition-all duration-1000"
                x-transition:enter="transition ease-in-out duration-1000"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-40 scale-100"
                x-transition:leave="transition ease-in-out duration-1000 absolute"
                x-transition:leave-start="opacity-40 scale-100"
                x-transition:leave-end="opacity-0 scale-105"
            >
        </template>

        <!-- Layer Overlay Gelap + Gradien -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-950/50 z-0"></div>

        <!-- Konten Teks -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left w-full">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase bg-brand-500/25 border border-brand-400/35 text-brand-200 backdrop-blur-sm shadow-sm">
                        🌱 100% Direct From Farmers
                    </span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-black text-white tracking-tight leading-tight">
                        Katalog Buah Segar <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-green-300 drop-shadow-[0_2px_10px_rgba(52,211,153,0.3)]">
                            Unggulan Nusantara
                        </span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-300 font-medium leading-relaxed max-w-lg">
                        Nikmati kelezatan buah segar pilihan yang lolos uji penjaminan mutu (Quality Control) ketat dan dikirim langsung dari lahan kelompok tani tepercaya.
                    </p>
                    
                    <!-- Indikator Titik (Dots) Slider -->
                    <div class="flex items-center justify-center sm:justify-start gap-2 pt-4">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button 
                                @click="activeSlide = index"
                                :class="{'bg-brand-500 w-6': activeSlide === index, 'bg-white/20 hover:bg-white/50 w-2': activeSlide !== index}"
                                class="h-2 rounded-full transition-all duration-300 shadow-sm"
                                aria-label="Ganti slide"
                            ></button>
                        </template>
                    </div>
                </div>
                
                @if(auth('buyer')->check() || !auth()->check())
                    @php
                        $cartCount = auth('buyer')->check() 
                            ? \App\Models\Cart::where('user_id', auth('buyer')->id())->count() 
                            : \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())->count();
                    @endphp
                    <div class="shrink-0 mt-4 sm:mt-0">
                        <a href="{{ route('cart.index') }}" class="relative py-3.5 inline-flex items-center gap-2.5 px-6 border border-brand-500/20 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white shadow-xl shadow-brand-600/10 hover:shadow-brand-600/25 hover:scale-105 active:scale-98 transition-all duration-300 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Lihat Keranjang Anda</span>
                            @if($cartCount > 0)
                                <span class="absolute -top-2.5 -right-2.5 inline-flex items-center justify-center w-6 h-6 text-xs font-black text-white bg-rose-500 border-2 border-white rounded-full shadow">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END BANNER HERO -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- SIDEBAR FILTER -->
            <aside class="w-full lg:w-72 lg:sticky lg:top-28 z-20">
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-6 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="font-heading font-extrabold text-gray-800 text-base">Filter Katalog</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </div>

                    <form action="{{ route('shop.index') }}" method="GET" class="space-y-5" x-data="{ busy: false }" @submit="busy = true">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Cari Buah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Alpukat Miki..." class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 pl-10 pr-3 text-xs font-semibold text-gray-700 transition">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Grade Kualitas</label>
                            <select name="grade" class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3 text-xs font-semibold text-gray-700 transition bg-white">
                                <option value="">Semua Kualitas (Grade)</option>
                                <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>Grade A (Premium Konsumsi)</option>
                                <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>Grade B (Standar Harian)</option>
                                <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>Grade C (Olahan Industri)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 text-white rounded-xl hover:from-brand-700 hover:to-brand-600 font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/25 active:scale-98 transition flex items-center justify-center gap-2">
                            <span x-show="!busy">Terapkan Filter</span>
                            <span x-show="busy" class="flex items-center gap-1.5" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyaring...
                            </span>
                        </button>

                        @if(request()->anyFilled(['search', 'grade']))
                            <a href="{{ route('shop.index') }}" class="block text-center text-xs font-extrabold text-gray-400 hover:text-brand-600 mt-2 transition">
                                Reset Filter & Pencarian
                            </a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- DAFTAR PRODUK -->
            <div class="flex-grow w-full">
                
                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <p class="text-sm font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
                    @forelse($products->groupBy('fruit_type') as $fruitType => $variants)
                        @php
                            $representativeProduct = $variants->first();
                            $minPrice = $variants->min('final_price');
                            $totalStock = $variants->sum('stock_kg');
                            $earliestExpiry = $variants->whereNotNull('expiry_date')->min('expiry_date');
                        @endphp

                        <!-- Verified Card layout -->
                        <a href="{{ route('shop.show', $representativeProduct) }}" class="group block bg-white rounded-3xl border border-gray-100 shadow-premium hover:shadow-premium-hover hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden relative focus:outline-none focus:ring-2 focus:ring-brand-500">
                            
                            <!-- QC Verified Badge on top of image -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/90 text-white backdrop-blur-sm shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Lolos QC
                                </span>
                            </div>

                            <div class="relative overflow-hidden bg-gray-50/40 h-52 flex items-center justify-center p-4">
                                @if($representativeProduct->image)
                                    <img src="{{ Storage::url($representativeProduct->image) }}" alt="{{ $fruitType }}" class="h-full object-contain filter drop-shadow-md group-hover:scale-105 transition-transform duration-350" loading="lazy">
                                @else
                                    <img src="{{ asset('images/1.jpg') }}" alt="{{ $fruitType }} Default" class="h-full w-full object-cover filter drop-shadow-md group-hover:scale-105 transition-transform duration-350" loading="lazy">
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-heading font-black text-gray-800 line-clamp-1 group-hover:text-brand-700 transition-colors">
                                    {{ $fruitType }}
                                </h3>
                                
                                <div class="mt-2.5 flex flex-wrap items-center gap-2 text-[10px] font-extrabold">
                                    <span class="flex items-center gap-1 bg-brand-50 text-brand-850 px-2.5 py-1 rounded-lg border border-brand-100/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        Stok: {{ $totalStock }} Kg
                                    </span>
                                    @if($earliestExpiry)
                                        <span class="flex items-center gap-1 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg border border-rose-100/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Exp: {{ \Carbon\Carbon::parse($earliestExpiry)->format('d M') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-5 mb-5 flex items-baseline gap-1 text-brand-700">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider mr-1">Mulai Dari</span>
                                    <span class="text-xs font-black">Rp</span>
                                    <span class="text-2xl font-black leading-none">{{ number_format($minPrice, 0, ',', '.') }}</span>
                                    <span class="text-xs font-bold text-gray-400">/ Kg</span>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="py-2.5 flex items-center justify-center gap-1.5 w-full bg-brand-50 hover:bg-brand-600 text-brand-850 hover:text-white border border-brand-200/50 hover:border-brand-600 rounded-xl font-extrabold text-xs transition-all duration-300">
                                        <span>Pilih Grade & Beli</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-16 px-6 text-center bg-white border border-gray-150 rounded-3xl shadow-premium">
                            <div class="h-16 w-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="font-heading font-extrabold text-gray-700 text-base">Buah Tidak Ditemukan</h4>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1 font-medium">
                                Coba kurangi filter atau ketik kata pencarian lain untuk menemukan produk buah segar kami.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>