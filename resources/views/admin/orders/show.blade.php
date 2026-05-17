<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Detail Pesanan #{{ $order->id }} - Admin" />
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Detail Pesanan #{{ $order->id }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Kembali ke Daftar
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Status Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Status Pesanan</h2>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="text-4xl">
                            @if($order->status === 'pending')
                                ⏳
                            @elseif($order->status === 'processing')
                                🔄
                            @elseif($order->status === 'shipped')
                                🚚
                            @elseif($order->status === 'delivered')
                                ✓
                            @else
                                ✗
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status Saat Ini:</p>
                            <p class="text-2xl font-bold">{{ $order->status === 'processing' ? 'Dibayar' : ucfirst($order->status) }}</p>
                        </div>
                    </div>

                    @if($order->payment_status !== 'paid')
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-6 space-y-3">
                            <p class="text-sm text-yellow-800 mb-3">⚠️ Pembayaran belum ditandai berhasil. Silakan verifikasi pembayaran terlebih dahulu.</p>
                            <form action="{{ route('admin.orders.check-payment-status', $order) }}" method="POST" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                                    🔍 Cek Status Pembayaran ke Midtrans
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.payment-success', $order) }}" method="POST" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 font-medium">
                                    ✓ Tandai Pembayaran Berhasil (Manual)
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($order->status === 'pending' && $order->payment_status === 'paid')
                        <div class="space-y-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <form action="{{ route('admin.orders.approve', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                                    ✓ Setujui & Proses Pesanan
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.reject', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Batalkan pesanan ini?')" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-medium">
                                    ✗ Tolak & Batalkan
                                </button>
                            </form>
                        </div>
                    @elseif($order->status === 'processing')
                        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <form action="{{ route('admin.orders.shipped', $order) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nomor Resi Pengiriman</label>
                                    <input type="text" name="tracking_number" placeholder="Misal: JNE123456789" 
                                        class="w-full px-3 py-2 border rounded" required>
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-medium">
                                    🚚 Tandai Sudah Dikirim
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Items Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Item Pesanan</h2>
                    <div class="space-y-3">
                        @forelse($items as $item)
                            <div class="flex justify-between items-start p-3 border rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $item->inventory->fruit_type ?? 'Produk' }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->quantity_kg }} Kg @ Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}/Kg</p>
                                </div>
                                <p class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">Tidak ada item.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Address Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Alamat Pengiriman</h2>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Nama:</span> {{ $order->customer_name }}</p>
                        <p><span class="font-medium">Telepon:</span> {{ $order->customer_phone }}</p>
                        <p><span class="font-medium">Alamat:</span> {{ $order->shipping_address }}</p>
                        <p><span class="font-medium">Kota/Kab:</span> {{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                        <p><span class="font-medium">Kode Pos:</span> {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Summary Card -->
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h3 class="text-lg font-bold mb-4">Ringkasan</h3>
                    
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-green-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm border-t pt-4">
                        <p>
                            <span class="font-medium">Pembayaran:</span><br>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($order->payment_status === 'paid')
                                    bg-green-100 text-green-800
                                @elseif($order->payment_status === 'unpaid')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ $order->payment_status === 'paid' ? 'Dibayar' : ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        <p>
                            <span class="font-medium">Kurir:</span> {{ $order->courier_name }}<br>
                            <span class="text-xs text-gray-600">{{ $order->courier_service }}</span>
                        </p>
                        @if($order->tracking_number)
                            <p>
                                <span class="font-medium">No. Resi:</span> {{ $order->tracking_number }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-600 pt-2">
                            Dibuat: {{ $order->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
