<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Setia Buah') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#10b981', // Premium Emerald
                            600: '#059669', // Deep Organic Green
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        accent: {
                            amber: '#d97706',
                            gold: '#f59e0b',
                            coral: '#f97316',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Outfit', 'sans-serif'],
                    },
                    boxShadow: {
                        'premium': '0 12px 30px -10px rgba(16, 185, 129, 0.12)',
                        'premium-hover': '0 20px 40px -12px rgba(16, 185, 129, 0.22)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.05)',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    
    {{ $meta ?? '' }}
</head>
<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-green-50/40 via-white to-emerald-50/20 min-h-screen">
    <div class="relative min-h-screen flex flex-col justify-between">
        
        @php
            // Mengecek apakah user sedang berada di halaman utama / katalog
            $isCatalog = request()->routeIs('shop.index') || request()->is('/');
        @endphp

        <header class="{{ $isCatalog ? 'absolute' : 'sticky' }} top-0 z-50 w-full px-4 sm:px-6 lg:px-8 pt-4 pb-2">
            
            <nav class="max-w-7xl mx-auto backdrop-blur-md {{ $isCatalog ? 'bg-white/20 border-white/20' : 'bg-white/80 border-white/60 shadow-glass' }} border rounded-2xl px-4 sm:px-6 lg:px-8 py-3.5 transition-all duration-300">
                <div class="flex items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-500 to-emerald-400 flex items-center justify-center p-1.5 shadow-md shadow-brand-500/10 group-hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-full w-full object-contain filter drop-shadow">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-heading font-extrabold text-lg sm:text-xl tracking-tight {{ $isCatalog ? 'text-white drop-shadow-md' : 'text-gray-800' }} group-hover:text-brand-400 transition-colors">
                                    SetiaBuah
                                </span>
                                <span class="text-[9px] font-bold {{ $isCatalog ? 'text-brand-300 drop-shadow-md' : 'text-brand-500' }} tracking-wider uppercase leading-none">Agribusiness Portal</span>
                            </div>
                        </a>
                    </div>

                    <div class="flex items-center gap-3.5 ml-auto">
                        @if(request()->is('admin*') && auth('web')->check())
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Pegawai</span>
                                <span class="text-xs font-semibold text-gray-600 mt-1 capitalize">Admin</span>
                            </div>
                            <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>
                            <!-- Dropdown Menu Admin -->
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" @click.away="open = false" class="text-xs sm:text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl transition duration-150 flex items-center gap-2 shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/20 active:scale-95 border border-emerald-700/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                    <span>Menu Admin</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                     class="absolute right-0 mt-2.5 w-64 rounded-2xl bg-white border border-gray-150 shadow-premium z-50 p-2 space-y-1 focus:outline-none"
                                     style="display: none;">
                                    
                                    <!-- Utama -->
                                    <div class="px-2.5 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Utama</div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.petani.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        Mitra Petani
                                    </a>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <!-- Supply Chain -->
                                    <div class="px-2.5 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rantai Pasok</div>
                                    <a href="{{ route('admin.procurement.pending') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Estimasi Pending
                                    </a>
                                    <a href="{{ route('admin.procurement.approved') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Estimasi Disetujui
                                    </a>
                                    <a href="{{ route('admin.procurement.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        Pengadaan
                                    </a>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <!-- Gudang & QC -->
                                    <div class="px-2.5 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Logistik & QC</div>
                                    <a href="{{ route('admin.qc.queue') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <span class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                            Quality Control (QC)
                                        </span>
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                                        </span>
                                    </a>
                                    <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Stok Gudang
                                    </a>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <!-- Penjualan -->
                                    <div class="px-2.5 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjualan</div>
                                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Pesanan Masuk
                                    </a>
                                    <a href="{{ route('admin.orders.history') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Riwayat Pesanan
                                    </a>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm text-red-500 hover:text-red-700 font-bold px-3 py-2 transition">Logout</button>
                            </form>

                        @elseif(request()->is('petani*') && auth('petani')->check())
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Mitra</span>
                                <span class="text-xs font-semibold text-gray-600 mt-1 capitalize">Petani</span>
                            </div>
                            <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>
                            <a href="{{ route('petani.dashboard') }}" class="text-xs sm:text-sm font-bold bg-brand-50 hover:bg-brand-100 text-brand-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-brand-200/40">
                                Dashboard Petani
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm text-red-500 hover:text-red-700 font-bold px-3 py-2 transition">Logout</button>
                            </form>

                        @elseif(request()->is('login'))
                            <a href="{{ route('shop.index') }}" class="text-xs sm:text-sm font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-gray-200/50">
                                Kembali ke Toko
                            </a>

                        @else
                            @if(auth('buyer')->check())
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('buyer.dashboard') }}" class="text-xs sm:text-sm font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3.5 py-2 rounded-xl transition border border-emerald-100/50 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('buyer.profile.edit') }}" class="text-xs sm:text-sm font-bold bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition shadow-md flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="truncate max-w-[120px]">{{ auth('buyer')->user()->name }}</span>
                                    </a>
                                    <div class="h-6 w-px {{ $isCatalog ? 'bg-white/30' : 'bg-gray-200' }} hidden md:block"></div>
                                    <form method="POST" action="{{ route('buyer.logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs sm:text-sm {{ $isCatalog ? 'text-rose-400 hover:text-rose-300 drop-shadow-md' : 'text-red-500 hover:text-red-700' }} font-bold px-3 py-2 transition">Logout</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('buyer.login') }}" class="py-2 text-xs sm:text-sm font-extrabold bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl px-5 py-2 flex items-center gap-1.5 shadow-md shadow-brand-600/10 hover:shadow-brand-600/20 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                                    Login / Daftar Pembeli
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-grow pb-16">
            {{ $slot }}
        </main>

        <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
                <p class="font-heading font-extrabold text-white text-lg tracking-wide flex items-center justify-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                    SetiaBuah Portal
                </p>
                <p class="text-xs max-w-md mx-auto leading-relaxed text-gray-500">
                    Sistem Rantai Pasok Agribisnis Terintegrasi & Food Traceability Digital untuk Komoditas Buah Unggul Terpercaya.
                </p>
                <div class="h-px w-20 bg-gray-800 mx-auto my-6"></div>
                <p class="text-[11px] text-gray-600">
                    &copy; {{ date('Y') }} SetiaBuah. Seluruh Hak Cipta Dilindungi.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>