<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Lacak Pesanan #{{ $order->id }} - Setia Buah" />
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8">
        
        @if(session('success'))
            <div class="mb-8 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded shadow-sm">
                <p class="font-bold">Yeay!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="bg-indigo-600 px-6 py-4 text-white">
                <h1 class="text-xl font-bold">Status Pesanan Anda</h1>
                <p class="opacity-80">Order ID: #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <div class="mt-2 flex flex-wrap gap-2 text-xs">
                    <span class="px-2 py-1 rounded bg-white/20">Status: {{ strtoupper($order->status) }}</span>
                    <span class="px-2 py-1 rounded bg-white/20">Bayar: {{ strtoupper($order->payment_status) }}</span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Timeline Visual -->
                <div class="relative wrap overflow-hidden p-4 h-full mb-8">
                    <div class="border-2-2 absolute border-opacity-20 border-gray-700 h-full border" style="left: 50%"></div>
                    
                    <div class="mb-8 flex justify-between items-center w-full right-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 bg-green-500 shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto font-semibold text-lg text-white">1</h1>
                        </div>
                        <div class="order-1 bg-gray-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-1 font-bold text-gray-800 text-xl">Dipesan</h3>
                            <p class="text-sm leading-snug tracking-wide text-gray-600 text-opacity-100">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 {{ in_array($order->payment_status, ['paid']) ? 'bg-green-500' : 'bg-gray-300' }} shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto text-white font-semibold text-lg">2</h1>
                        </div>
                        <div class="order-1 bg-gray-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-1 font-bold text-gray-800 text-xl">Dibayar</h3>
                        </div>
                    </div>

                    <div class="mb-8 flex justify-between items-center w-full right-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto font-semibold text-lg text-white">3</h1>
                        </div>
                        <div class="order-1 bg-gray-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-1 font-bold text-gray-800 text-xl">Dikirim</h3>
                            @if($order->tracking_number)
                                <p class="text-sm font-mono mt-1 ring-1 ring-gray-400 px-2 rounded bg-white">Resi: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto text-white font-semibold text-lg">4</h1>
                        </div>
                        <div class="order-1 bg-gray-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-1 font-bold text-gray-800 text-xl">Diterima</h3>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="font-bold text-gray-800 mb-1">Alamat Pengiriman:</p>
                        <p>{{ $order->shipping_address }}</p>
                        @if($order->shipment)
                            <p class="mt-2 text-xs text-gray-600">Kurir: {{ strtoupper($order->shipment->courier_name ?? '-') }} {{ strtoupper($order->shipment->courier_service ?? '') }}</p>
                            <p class="text-xs text-gray-600">Estimasi: {{ $order->shipment->estimated_delivery ?? '-' }}</p>
                        @endif
                    </div>
                    <div class="md:text-right">
                        <p class="font-bold text-gray-800 mb-1">Total Tagihan:</p>
                        <p class="text-xl font-bold text-green-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p class="mt-1">Bayar via: <span class="uppercase font-semibold">{{ str_replace('_', ' ', $order->payment_method) }}</span></p>
                        <p>Status Bayar: <span class="uppercase {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-bold">{{ $order->payment_status }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('shop.index') }}" class="min-h-[44px] inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">← Kembali Belanja</a>
        </div>
    </div>
</x-app-layout>