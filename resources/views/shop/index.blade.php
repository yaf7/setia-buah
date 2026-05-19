<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Katalog Buah Segar Pilihan - SetiaBuah" />
    </x-slot>

    <!-- Premium Hero Mesh Header Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-brand-700 via-brand-600 to-emerald-700 text-white py-14 sm:py-20 mb-12 shadow-md">
        <!-- Abstract Shapes Overlay -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.15),transparent_40%)]"></div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-brand-500/20 text-brand-200 border border-brand-500/30">
                        🌱 100% Direct From Farmers
                    </span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold tracking-tight leading-none">
                        Katalog Buah Segar <br class="hidden sm:inline">Unggulan Nusantara
                    </h1>
                    <p class="text-sm sm:text-base text-brand-100/90 font-medium leading-relaxed max-w-lg">
                        Nikmati kelezatan buah segar pilihan yang lolos uji penjaminan mutu (Quality Control) ketat dan dikirim langsung dari lahan kelompok tani tepercaya.
                    </p>
                </div>
                
                @if(auth('buyer')->check() || !auth()->check())
                    @php
                        $cartCount = auth('buyer')->check() 
                            ? \App\Models\Cart::where('user_id', auth('buyer')->id())->count() 
                            : \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())->count();
                    @endphp
                    <div class="shrink-0">
                        <a href="{{ route('cart.index') }}" class="relative min-h-[48px] inline-flex items-center gap-2.5 px-6 py-3 border border-white/30 rounded-2xl bg-white/10 hover:bg-white text-white hover:text-brand-800 backdrop-blur-sm hover:scale-105 active:scale-98 shadow-lg transition-all duration-200 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Lihat Keranjang Anda</span>
                            @if($cartCount > 0)
                                <span class="absolute -top-2.5 -right-2.5 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-accent-coral border-2 border-brand-600 rounded-full shadow">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Catalog Directory Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- Sticky Filter & Search Sidebar Panel -->
            <aside class="w-full lg:w-72 lg:sticky lg:top-28 z-20">
                <div class="bg-white border border-gray-150 rounded-2xl shadow-premium p-6 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-150">
                        <h2 class="font-heading font-extrabold text-gray-800 text-base">Filter Katalog</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </div>

                    <form action="{{ route('shop.index') }}" method="GET" class="space-y-5" x-data="{ busy: false }" @submit="busy = true">
                        <!-- Search Box -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-extrabold text-gray-400 uppercase tracking-widest">Cari Buah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Alpukat Miki..." class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 min-h-[44px] pl-9 pr-3 text-sm transition">
                            </div>
                        </div>

                        <!-- Grade Dropdown -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-extrabold text-gray-400 uppercase tracking-widest">Grade Kualitas</label>
                            <select name="grade" class="mt-1 w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 min-h-[44px] px-3 text-sm transition bg-white">
                                <option value="">Semua Kualitas (Grade)</option>
                                <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>Grade A (Premium Konsumsi)</option>
                                <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>Grade B (Standar Harian)</option>
                                <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>Grade C (Olahan Industri)</option>
                            </select>
                        </div>

                        <!-- Apply Button -->
                        <button type="submit" class="w-full min-h-[44px] bg-gradient-to-tr from-brand-600 to-brand-500 text-white rounded-xl hover:from-brand-700 hover:to-brand-600 font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition flex items-center justify-center gap-2">
                            <span x-show="!busy">Terapkan Filter</span>
                            <span x-show="busy" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyaring...
                            </span>
                        </button>

                        @if(request()->anyFilled(['search', 'grade']))
                            <a href="{{ route('shop.index') }}" class="block text-center text-xs font-bold text-gray-500 hover:text-brand-600 mt-2 transition">
                                Reset Filter & Pencarian
                            </a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- Product Showcase Grid Area -->
            <div class="flex-grow w-full">
                
                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <p class="text-sm font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
                    @forelse($products as $product)
                        <!-- Premium Interactive Fruit Card -->
                        <div class="group bg-white rounded-2xl border border-gray-150 shadow-premium hover:shadow-premium-hover transform hover:-translate-y-1 duration-300 flex flex-col overflow-hidden relative">
                            
                            <!-- Premium Dynamic Grade Tag -->
                            <div class="absolute top-3 left-3 z-10">
                                @if($product->grade === 'A')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-amber-500 to-yellow-400 text-white shadow-md shadow-amber-500/20">
                                        ⭐ Grade {{ $product->grade }}
                                    </span>
                                @elseif($product->grade === 'B')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-brand-600 to-brand-500 text-white shadow-md shadow-brand-500/20">
                                        🌱 Grade {{ $product->grade }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-gradient-to-tr from-indigo-600 to-purple-500 text-white shadow-md shadow-indigo-500/20">
                                        🏭 Grade {{ $product->grade }}
                                    </span>
                                @endif
                            </div>

                            <!-- Product Thumbnail Image Wrapper -->
                            <div class="relative overflow-hidden bg-gray-50 h-52 flex items-center justify-center p-4">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->fruit_type }}" class="h-full object-contain filter drop-shadow-md group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                @else
                                    <div class="text-gray-400 flex flex-col items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-xs font-bold">Tanpa Foto</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Content Details -->
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg sm:text-xl font-heading font-extrabold text-gray-800 line-clamp-1 group-hover:text-brand-600 transition-colors">
                                    {{ $product->fruit_type }}
                                </h3>
                                
                                <div class="mt-2.5 flex items-center gap-4 text-xs font-bold text-gray-500">
                                    <span class="flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-1 rounded-lg">
                                        📦 Stok: {{ $product->stock_kg }} Kg
                                    </span>
                                    @if($product->expiry_date)
                                        <span class="flex items-center gap-1 bg-rose-50 text-rose-700 px-2 py-1 rounded-lg">
                                            ⏰ Exp: {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-5 mb-5 flex items-baseline gap-1 text-brand-700">
                                    <span class="text-xs font-bold">Rp</span>
                                    <span class="text-2xl font-black leading-none">{{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                                    <span class="text-xs font-bold text-gray-400">/ Kg</span>
                                </div>
                                
                                <!-- Card Action Button -->
                                <div class="mt-auto">
                                    <a href="{{ route('shop.show', $product) }}" class="min-h-[44px] flex items-center justify-center w-full bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/50 rounded-xl transition duration-150 font-bold text-xs sm:text-sm shadow-sm">
                                        Lihat Detail Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty Catalog State Screen -->
                        <div class="col-span-full py-16 px-6 text-center bg-white border border-gray-150 rounded-2xl shadow-premium">
                            <div class="h-16 w-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="font-heading font-extrabold text-gray-700 text-base">Buah Tidak Ditemukan</h4>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1">
                                Coba kurangi filter atau ketik kata pencarian lain untuk menemukan produk buah segar kami.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Custom Elegant Pagination Link Wrapper -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>