<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta />
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Katalog Buah Segar</h1>
            @if(auth('buyer')->check() || !auth()->check())
                @php
                    $cartCount = auth('buyer')->check() 
                        ? \App\Models\Cart::where('user_id', auth('buyer')->id())->count() 
                        : \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())->count();
                @endphp
                <a href="{{ route('cart.index') }}" class="relative min-h-[44px] inline-flex items-center px-4 py-2 border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-semibold">Keranjang</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full shadow-sm">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @endif
        </div>

        <!-- Filter & Search Sidebar -->
        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 space-y-6">
                <form action="{{ route('shop.index') }}" method="GET" class="space-y-4 bg-white p-4 rounded shadow" x-data="{ busy: false }" @submit="busy = true">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cari Buah</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="mt-1 w-full rounded border-gray-300 min-h-[44px] px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Grade Kualitas</label>
                        <select name="grade" class="mt-1 w-full rounded border-gray-300 min-h-[44px] px-3">
                            <option value="">Semua Grade</option>
                            <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>A (Premium)</option>
                            <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>B (Standar)</option>
                            <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>C (Olahan)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full min-h-[44px] bg-green-600 text-white rounded hover:bg-green-700">
                        <span x-show="!busy">Cari</span>
                        <span x-show="busy">Mencari...</span>
                    </button>
                    @if(request()->anyFilled(['search', 'grade']))
                        <a href="{{ route('shop.index') }}" class="block text-center mt-2 text-sm text-gray-500 hover:text-gray-800">Reset Filter</a>
                    @endif
                </form>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-800 p-4 rounded-lg shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->fruit_type }}" class="w-full h-48 object-contain p-2 bg-gray-50" loading="lazy">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                            @endif
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold text-gray-800">{{ $product->fruit_type }}</h3>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p>⭐ Grade {{ $product->grade }}</p>
                                    <p>📦 Sisa Stok: {{ $product->stock_kg }} Kg</p>
                                </div>
                                <div class="mt-4 mb-4 text-green-700 font-bold text-lg">
                                    Rp {{ number_format($product->price_per_kg, 0, ',', '.') }} / Kg
                                </div>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('shop.show', $product) }}" class="min-h-[44px] block w-full text-center py-2 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition font-medium">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded shadow">
                            <p class="text-lg font-medium">Buah tidak ditemukan.</p>
                            <p class="text-sm">Coba sesuaikan filter atau katakata pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>