<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Setia Buah') }}</title>

    <!-- Google Fonts (Outfit & Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN with Premium Theme Extensions) -->
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
    
    <!-- Alpine.js (UI Logic Support) -->
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
        
        <!-- Floating Glassmorphic Navbar Container -->
        <header class="sticky top-0 z-50 w-full px-4 sm:px-6 lg:px-8 pt-4 pb-2">
            <nav class="max-w-7xl mx-auto backdrop-blur-md bg-white/75 border border-white/60 rounded-2xl shadow-glass px-4 sm:px-6 lg:px-8 py-3.5 transition-all duration-300">
                <div class="flex items-center justify-between gap-4">
                    
                    <!-- Logo / Brand Title -->
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-500 to-emerald-400 flex items-center justify-center p-1.5 shadow-md shadow-brand-500/10 group-hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-full w-full object-contain filter drop-shadow">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-heading font-extrabold text-lg sm:text-xl tracking-tight text-gray-800 group-hover:text-brand-600 transition-colors">
                                    SetiaBuah
                                </span>
                                <span class="text-[9px] font-bold text-brand-500 tracking-wider uppercase leading-none">Agribusiness Portal</span>
                            </div>
                        </a>
                    </div>

                    <!-- Right Navigation Actions -->
                    <div class="flex items-center gap-3.5 ml-auto">
                        @if(request()->is('admin*') && auth('web')->check())
                            <!-- Admin Context -->
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Pegawai</span>
                                <span class="text-xs font-semibold text-gray-600 mt-1 capitalize">Admin</span>
                            </div>
                            <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>
                            <a href="{{ route('admin.dashboard') }}" class="text-xs sm:text-sm font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-gray-200/50">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.petani.index') }}" class="text-xs sm:text-sm font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-emerald-200/40">
                                Mitra Petani
                            </a>
                            <a href="{{ route('admin.procurement.activities') }}" class="text-xs sm:text-sm font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-indigo-200/40">
                                Aktivitas
                            </a>
                            <a href="{{ route('admin.procurement.pending') }}" class="text-xs sm:text-sm font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-amber-200/40">
                                Pengadaan
                            </a>
                            <a href="{{ route('admin.qc.queue') }}" class="text-xs sm:text-sm font-bold bg-brand-50 hover:bg-brand-100 text-brand-700 px-3.5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 border border-brand-200/40">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-500 animate-ping"></span>
                                QC
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm text-red-500 hover:text-red-700 font-bold px-3 py-2 transition">Logout</button>
                            </form>

                        @elseif(request()->is('petani*') && auth('petani')->check())
                            <!-- Petani Context -->
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
                            <!-- Buyer / Shop Context -->
                            @if(auth('buyer')->check())
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('buyer.dashboard') }}" class="text-xs sm:text-sm font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3.5 py-2 rounded-xl transition border border-emerald-100/50 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Pesanan Saya
                                    </a>
                                    <div class="hidden md:flex flex-col text-right">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Pembeli</span>
                                        <span class="text-xs font-semibold text-gray-700 mt-1 truncate max-w-[120px]">{{ auth('buyer')->user()->name }}</span>
                                    </div>
                                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>
                                    <form method="POST" action="{{ route('buyer.logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs sm:text-sm text-red-500 hover:text-red-700 font-bold px-3 py-2 transition">Logout</button>
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

        <!-- Main Page Layout Content -->
        <main class="flex-grow pb-16">
            {{ $slot }}
        </main>

        <!-- Footer -->
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
