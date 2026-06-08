<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Riwayat Pembelian - Setia Buah" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Riwayat Pembelian</h1>
                <p class="text-gray-600 mt-2">Halo, {{ Auth::guard('buyer')->user()->name }}! Lihat paket Anda dan status pengiriman.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('shop.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    🛒 Belanja
                </a>
                <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                    🛒 Keranjang
                </a>
                <a href="{{ route('buyer.profile.edit') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                    👤 Profil
                </a>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-5xl mb-4">📦</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pembelian</h2>
                <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pembelian.</p>
                <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border-l-4 @if($order->status === 'delivered') border-l-green-500 @elseif($order->status === 'shipped') border-l-blue-500 @elseif($order->status === 'processing') border-l-yellow-500 @elseif($order->status === 'pending') border-l-gray-400 @else border-l-red-500 @endif">
                        
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Pesanan #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                                <div class="text-4xl">
                                    @if($order->status === 'pending')
                                        ⏳
                                    @elseif($order->status === 'processing')
                                        🔄
                                    @elseif($order->status === 'shipped')
                                        🚚
                                    @elseif($order->status === 'delivered')
                                        ✅
                                    @else
                                        ❌
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">Status Pengiriman:</p>
                                    <div class="flex items-center gap-2">
                                        <p class="text-lg font-semibold text-gray-900">
                                            @if($order->status === 'pending')
                                                Menunggu Persetujuan
                                            @elseif($order->status === 'processing')
                                                Dibayar
                                            @elseif($order->status === 'shipped')
                                                Dalam Perjalanan
                                            @elseif($order->status === 'delivered')
                                                Terkirim
                                            @else
                                                Dibatalkan
                                            @endif
                                        </p>
                                    </div>
                                    @if($order->status === 'shipped' && $order->tracking_number)
                                        <p class="text-sm text-gray-600 mt-2">
                                            📍 Resi: <span class="font-mono font-bold">{{ $order->tracking_number }}</span>
                                            @if($order->courier_name)
                                                via <strong>{{ $order->courier_name }}</strong>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 mb-3">Detail Barang:</h4>
                                <div class="space-y-2">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $item->inventory->fruit_type ?? 'Produk' }} (Grade {{ $item->inventory->grade ?? '-' }})</p>
                                                <p class="text-sm text-gray-600">{{ $item->quantity_kg }} Kg</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Shipping Info -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <h4 class="font-bold text-gray-900 mb-2">Alamat Pengiriman:</h4>
                                <p class="text-sm text-gray-700">{{ $order->customer_name }}</p>
                                <p class="text-sm text-gray-700">{{ $order->shipping_address }}</p>
                                <p class="text-sm text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                                <p class="text-sm text-gray-700 mt-2">📞 {{ $order->customer_phone }}</p>
                            </div>

                            <!-- Payment & Action -->
                            <div class="flex justify-between items-center pt-4">
                                <div>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                        @if($order->payment_status === 'paid')
                                            bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        Pembayaran: 
                                        @if($order->payment_status === 'paid')
                                            ✓ Dibayar
                                        @elseif($order->payment_status === 'pending')
                                            ⏳ Menunggu
                                        @else
                                            ✗ Gagal
                                        @endif
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('orders.track', $order) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                        Lacak Paket
                                    </a>
                                    @if($order->status === 'shipped')
                                        <form action="{{ route('buyer.orders.receive', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin telah menerima pesanan ini dengan baik?');">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium shadow-md">
                                                ✅ Pesanan Diterima
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
