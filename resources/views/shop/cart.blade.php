<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Keranjang Belanja Anda - SetiaBuah" />
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Cart Header -->
        <div class="flex items-center gap-3 mb-8 border-b border-gray-100 pb-5">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-500 flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 tracking-tight">Keranjang Belanja</h1>
                <p class="text-xs text-gray-500">Tinjau kembali barang pilihan Anda sebelum melanjutkan ke formulir pengiriman</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <!-- Empty Cart Screen -->
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-12 text-center max-w-lg mx-auto">
                <div class="h-20 w-20 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h3 class="font-heading font-extrabold text-gray-800 text-lg">Keranjang Belanja Kosong</h3>
                <p class="text-sm text-gray-500 mt-2 mb-8 leading-relaxed">
                    Anda belum memasukkan buah segar pilihan ke keranjang. Mari mulai berbelanja komoditas unggulan langsung dari para petani tepercaya kami!
                </p>
                <a href="{{ route('shop.index') }}" class="py-3 inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-95 transition duration-150">
                    Mulai Belanja Sekarang
                </a>
            </div>
        @else
            <!-- Premium Active Cart Panel layout -->
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- Left-hand side List Items -->
                <div class="flex-grow w-full lg:max-w-[640px] xl:max-w-none">
                    <div class="bg-white border border-gray-150 rounded-2xl shadow-premium overflow-hidden">
                        <ul class="divide-y divide-gray-100">
                            @foreach($cartItems as $item)
                                <li class="p-5 sm:p-6 flex flex-col sm:flex-row items-center sm:justify-between gap-5 hover:bg-gray-50/50 transition duration-150">
                                    
                                    <!-- Image and Product Brand -->
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="h-20 w-20 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-2 shrink-0">
                                            @if($item->inventory && $item->inventory->image)
                                                <img src="{{ Storage::url($item->inventory->image) }}" class="max-h-full max-w-full object-contain filter drop-shadow">
                                            @else
                                                <div class="text-[10px] font-bold text-gray-400">Tanpa Foto</div>
                                            @endif
                                        </div>
                                        
                                        <div class="space-y-1">
                                            <h3 class="font-heading font-extrabold text-gray-800 text-sm sm:text-base leading-tight">
                                                {{ $item->inventory->fruit_type ?? 'Produk Tidak Tersedia' }}
                                            </h3>
                                            
                                            <div class="flex items-center gap-2">
                                                @if(optional($item->inventory)->grade === 'A')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-200/50">
                                                        Grade A
                                                    </span>
                                                @elseif(optional($item->inventory)->grade === 'B')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-brand-100 text-brand-800 border border-brand-200/50">
                                                        Grade B
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-indigo-100 text-indigo-800 border border-indigo-200/50">
                                                        Grade C
                                                    </span>
                                                @endif
                                                
                                                <span class="text-xs text-gray-400 font-medium">
                                                    Rp {{ number_format(optional($item->inventory)->price_per_kg ?? 0, 0, ',', '.') }} / Kg
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Price / Action Buttons -->
                                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-5 border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                                        <div class="flex items-center gap-4">
                                            <!-- Update Qty Form -->
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex flex-col items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                                    <input type="number" name="quantity_kg" value="{{ $item->quantity_kg }}" min="0.5" step="0.5" class="w-16 py-1.5 px-2 text-center text-sm font-bold text-gray-700 border-none focus:ring-0">
                                                    <span class="text-[10px] text-gray-500 font-extrabold pr-2 uppercase">Kg</span>
                                                </div>
                                                <button type="submit" class="text-[10px] font-extrabold text-brand-600 hover:text-brand-700 underline tracking-wide">Ubah Jumlah</button>
                                            </form>

                                            <!-- Subtotal -->
                                            <div class="text-right w-24">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider leading-none">Subtotal</span>
                                                <span class="block font-heading font-black text-brand-700 text-sm sm:text-base mt-1">
                                                    Rp {{ number_format(($item->quantity_kg * (optional($item->inventory)->price_per_kg ?? 0)), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Trash Action icon button -->
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="py-2 min-w-[40px] flex items-center justify-center rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 hover:scale-105 active:scale-95 transition-all duration-150" title="Hapus Item">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Right-hand side summary card -->
                <aside class="w-full lg:w-80 lg:sticky lg:top-28">
                    <div class="bg-white border border-gray-150 rounded-2xl shadow-premium p-6 space-y-6">
                        <h2 class="font-heading font-extrabold text-gray-800 text-base pb-3 border-b border-gray-100">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3.5 text-sm">
                            <div class="flex justify-between font-medium text-gray-500">
                                <span>Total Item</span>
                                <span class="font-bold text-gray-700">{{ $cartItems->count() }} Jenis</span>
                            </div>
                            <div class="flex justify-between font-medium text-gray-500">
                                <span>Total Berat Logistik</span>
                                <span class="font-bold text-gray-700">{{ $cartItems->sum('quantity_kg') }} Kg</span>
                            </div>
                            
                            <div class="h-px bg-gray-100 my-2"></div>
                            
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Total Tagihan Buah</span>
                                <span class="font-heading font-black text-2xl text-brand-700 mt-1">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Proceed To Checkout Button -->
                        @php
                            $hasInvalidItem = $cartItems->contains(function ($item) {
                                return $item->quantity_kg < 5;
                            });
                        @endphp
                        @if($hasInvalidItem)
                            <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-xl flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>Minimal pemesanan adalah 5 Kg untuk setiap jenis buah. Silakan ubah jumlah produk di atas.</span>
                            </div>
                            <button type="button" disabled class="py-3 w-full bg-gray-100 text-gray-400 border border-gray-200 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                <span>Lanjut ke Pengiriman</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        @else
                            <a href="{{ route('checkout.index') }}" class="py-3 w-full bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 active:scale-98 transition flex items-center justify-center gap-2">
                                <span>Lanjut ke Pengiriman</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        @endif
                    </div>
                </aside>

            </div>
        @endif
    </div>
</x-app-layout>