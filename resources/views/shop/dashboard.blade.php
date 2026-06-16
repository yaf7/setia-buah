<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Riwayat Pembelian - Setia Buah" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Riwayat Pembelian</h1>
                    <p class="text-gray-600 mt-2">Halo, {{ Auth::guard('buyer')->user()->name }}! Lihat paket Anda dan status pengiriman.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('shop.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2 transition">
                        🛒 Belanja
                    </a>
                    <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium flex items-center gap-2 transition">
                        🛒 Keranjang
                    </a>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-8 text-center border border-gray-100">
                    <div class="text-5xl mb-4">📦</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pembelian</h2>
                    <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pembelian.</p>
                    <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div x-data="{ detailOpen: false }" class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 border-l-4 @if($order->status === 'delivered') border-l-green-500 @elseif($order->status === 'shipped') border-l-blue-500 @elseif($order->status === 'processing') border-l-yellow-500 @elseif($order->status === 'pending') border-l-gray-400 @else border-l-red-500 @endif">
                            
                            <div class="p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex-1 w-full flex justify-between md:block">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Pesanan #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="mt-2 md:mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($order->status === 'pending') Menunggu Persetujuan
                                            @elseif($order->status === 'processing') Diproses
                                            @elseif($order->status === 'shipped') Dikirim
                                            @elseif($order->status === 'delivered') Selesai
                                            @else Dibatalkan @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="text-right w-full md:w-auto flex justify-between md:flex-col items-center md:items-end">
                                    <p class="text-xl font-bold text-green-600 mb-0 md:mb-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <button @click="detailOpen = !detailOpen" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 text-sm font-medium transition focus:outline-none">
                                        <span x-text="detailOpen ? 'Tutup Detail' : 'Lihat Detail'"></span>
                                    </button>
                                </div>
                            </div>

                            <div x-show="detailOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="border-t border-gray-100 bg-gray-50 p-6"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        @if($order->status === 'shipped' && $order->tracking_number)
                                            <div class="mb-4 p-3 bg-white rounded border border-gray-200">
                                                <p class="text-sm text-gray-600">Nomor Resi Pengiriman:</p>
                                                <p class="font-mono text-lg font-bold text-gray-900">{{ $order->tracking_number }}</p>
                                                @if($order->courier_name)
                                                    <p class="text-sm text-gray-500">via <strong>{{ $order->courier_name }}</strong></p>
                                                @endif
                                            </div>
                                        @endif

                                        <h4 class="font-bold text-gray-900 mb-3">Detail Barang:</h4>
                                        <div class="space-y-2">
                                            @foreach($order->orderItems as $item)
                                                <div class="flex justify-between items-center p-3 bg-white shadow-sm border border-gray-100 rounded">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $item->inventory->fruit_type ?? 'Produk' }} <span class="text-sm font-normal text-gray-500">(Grade {{ $item->inventory->grade ?? '-' }})</span></p>
                                                        <p class="text-sm text-gray-600">{{ $item->quantity_kg }} Kg</p>
                                                    </div>
                                                    <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <div class="mb-4 p-4 bg-white rounded shadow-sm border border-gray-100">
                                            <h4 class="font-bold text-gray-900 mb-2">Alamat Pengiriman:</h4>
                                            <p class="text-sm text-gray-800 font-medium">{{ $order->customer_name }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $order->shipping_address }}</p>
                                            <p class="text-sm text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                                            <p class="text-sm text-gray-600 mt-2 flex items-center gap-1">📞 {{ $order->customer_phone }}</p>
                                        </div>

                                        <div class="p-4 bg-white rounded shadow-sm border border-gray-100">
                                            <h4 class="font-bold text-gray-900 mb-2">Status Pembayaran:</h4>
                                            <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($order->payment_status === 'paid') ✓ Berhasil Dibayar
                                                @elseif($order->payment_status === 'pending') ⏳ Menunggu Pembayaran
                                                @else ✗ Pembayaran Gagal @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-200 flex flex-wrap justify-end gap-3">
                                    <a href="{{ route('orders.track', $order) }}" class="px-5 py-2 bg-blue-50 text-blue-700 rounded-lg border border-blue-200 hover:bg-blue-100 text-sm font-medium transition">
                                        📍 Lacak Paket
                                    </a>
                                    
                                    @if($order->status === 'shipped')
                                        <form action="{{ route('buyer.orders.receive', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin telah menerima pesanan ini dengan baik?');">
                                            @csrf
                                            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium shadow-sm transition">
                                                ✅ Pesanan Diterima
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>