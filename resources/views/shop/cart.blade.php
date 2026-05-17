<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Keranjang Belanja - Setia Buah" />
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white p-8 text-center rounded-lg shadow">
                <p class="text-gray-500 mb-4">Keranjang Anda masih kosong.</p>
                <a href="{{ route('shop.index') }}" class="min-h-[44px] inline-flex items-center px-6 bg-green-600 text-white rounded hover:bg-green-700">Belanja Sekarang</a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-0 sm:p-6">
                    <ul class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <li class="py-4 px-4 sm:px-0 flex flex-col sm:flex-row items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-4 w-full sm:w-auto">
                                    @if($item->inventory && $item->inventory->image)
                                        <img src="{{ Storage::url($item->inventory->image) }}" class="h-16 w-16 object-cover rounded">
                                    @else
                                        <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                                    @endif
                                    
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $item->inventory->fruit_type ?? 'Produk Tidak Tersedia' }}</h3>
                                        <p class="text-sm text-gray-500">Grade {{ $item->inventory->grade ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between w-full sm:w-auto mt-2 sm:mt-0 gap-4">
                                    <div class="text-gray-700">
                                        {{ $item->quantity_kg }} Kg x Rp {{ number_format($item->inventory->price_per_kg ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="font-bold text-gray-900">
                                        Rp {{ number_format(($item->quantity_kg * ($item->inventory->price_per_kg ?? 0)), 0, ',', '.') }}
                                    </div>
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2 min-h-[44px] min-w-[44px]">🗑️</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-gray-50 p-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <span class="text-lg text-gray-600">Total Keseluruhan:</span>
                        <span class="text-2xl font-bold text-green-700 ml-2">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="min-h-[44px] w-full sm:w-auto px-8 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-center font-bold">
                        Lanjut Checkout →
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>