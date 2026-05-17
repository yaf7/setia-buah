<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta 
            title="{{ $product->fruit_type }} Grade {{ $product->grade }} - Setia Buah"
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

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/2">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->fruit_type }}" class="w-full h-64 md:h-full object-contain p-4 bg-gray-50">
                    @else
                        <div class="w-full h-64 md:h-full bg-gray-200 flex items-center justify-center text-gray-500 text-xl">Tidak ada foto</div>
                    @endif
                </div>
                
                <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col">
                    <div class="uppercase tracking-wide text-sm text-green-600 font-semibold">Grade {{ $product->grade }}</div>
                    <h1 class="mt-1 text-3xl font-bold text-gray-900">{{ $product->fruit_type }}</h1>
                    
                    <div class="mt-4 prose text-gray-600">
                        <p>{{ $product->description ?? 'Kualitas terbaik langsung dari hasil kebun segar pilihan Setia Buah yang telah melewati seleksi Quality Control super ketat.' }}</p>
                    </div>

                    <div class="mt-6 flex items-baseline space-x-2">
                        <span class="text-3xl font-bold text-green-700">Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                        <span class="text-gray-500">/ Kg</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Sisa Stok: <span class="font-bold {{ $product->stock_kg > 0 ? 'text-gray-700' : 'text-red-500' }}">{{ $product->stock_kg }} Kg</span></p>

                    <div class="mt-8 flex-grow">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 text-green-800 p-4 rounded-lg shadow-sm text-sm">
                                {{ session('success') }}
                                <a href="{{ route('cart.index') }}" class="underline ml-2 font-bold">Lihat Keranjang →</a>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded-lg shadow-sm text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col gap-4">
                            @csrf
                            <input type="hidden" name="inventory_id" value="{{ $product->id }}">
                            
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah Beli (Kg)</label>
                                <input type="number" id="quantity" name="quantity_kg" min="0.5" step="0.5" max="{{ $product->stock_kg }}" value="1" class="mt-1 block max-w-[150px] w-full rounded border-gray-300 min-h-[44px] px-3 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                            
                            <button type="submit" class="min-h-[50px] w-full bg-green-600 text-white rounded hover:bg-green-700 font-bold transition shadow" {{ $product->stock_kg <= 0 ? 'disabled' : '' }}>
                                {{ $product->stock_kg > 0 ? 'Masukkan ke Keranjang' : 'Stok Habis' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>