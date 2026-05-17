<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Setia Buah') }}</title>

    <!-- Tailwind CSS (via CDN untuk testing cepat) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js (wajib untuk form draft dan logic UI) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar Sederhana -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 w-full">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold text-xl text-green-600">
                                <img src="{{ asset('logo.png') }}" alt="Logo Setia Alpukat Miki" style="height: 40px; display: inline-block;">
                                <span>Setia Buah</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 ml-auto">
                        @if(auth()->check())
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'petani')
                                <!-- Tampilan khusus untuk Admin/Petani yang sedang melihat halaman toko -->
                                <span class="hidden md:inline text-sm font-medium text-gray-400">
                                    Masuk sebagai {{ ucfirst(auth()->user()->role) }}
                                </span>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded font-bold">
                                        Dashboard Admin
                                    </a>
                                    <a href="{{ route('admin.qc.queue') }}" class="text-sm bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded font-bold">
                                        Antrean QC
                                    </a>
                                @else
                                    <a href="{{ route('petani.dashboard') }}" class="text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded font-bold">
                                        Dashboard Petani
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-bold">Logout</button>
                                </form>
                            @else
                                <!-- Tampilan fallback jika user non-admin/petani di guard web -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-bold">Logout</button>
                                </form>
                            @endif
                        @elseif(auth('buyer')->check())
                            <!-- Tampilan untuk Pembeli (Buyer) -->
                            <div class="flex items-center gap-3">
                                <a href="{{ route('buyer.dashboard') }}" class="text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded font-bold">
                                    Riwayat Belanja
                                </a>
                                <span class="text-sm font-medium text-gray-700">
                                    Hallo, {{ auth('buyer')->user()->name }}
                                </span>
                                <form method="POST" action="{{ route('buyer.logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-bold px-3 py-1">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('buyer.login') }}" class="text-sm bg-green-600 text-white hover:bg-green-700 rounded font-bold px-4 py-2 transition shadow">Login / Daftar Pembeli</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
