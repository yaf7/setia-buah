<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Riwayat Pembelian - Setia Buah" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-emerald-50/40 via-white to-white">
        <div class="max-w-6xl mx-auto px-4 py-10">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6 pb-6 border-b border-gray-100">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black tracking-widest uppercase bg-brand-500/10 border border-brand-200/50 text-brand-700">
                        Konsumen Portal
                    </span>
                    <h1 class="text-3xl font-heading font-black text-gray-800 mt-2">Riwayat Pembelian</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 font-medium">
                        Halo, <span class="font-extrabold text-gray-800">{{ Auth::guard('buyer')->user()->name }}</span>! Pantau status pengiriman pesanan buah segar Anda.
                    </p>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <a href="{{ route('shop.index') }}" class="flex-1 md:flex-initial py-2.5 px-5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md shadow-brand-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>Belanja</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex-1 md:flex-initial py-2.5 px-5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/50 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span>Keranjang</span>
                    </a>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white rounded-3xl shadow-premium p-12 text-center border border-gray-100 max-w-lg mx-auto">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Pembelian</h2>
                    <p class="text-xs text-gray-550 mb-6 font-medium">Anda belum melakukan transaksi pembelian buah segar di katalog kami.</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex py-2.5 px-6 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-brand-500/10">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="space-y-5">
                    @foreach($orders as $order)
                        @php
                            // Dynamic color mapping for left accent bar
                            if($order->status === 'delivered') {
                                $barColor = 'border-l-emerald-500';
                                $statusBadgeClass = 'bg-emerald-50 border border-emerald-100 text-emerald-800';
                                $statusText = 'Selesai';
                            } elseif($order->status === 'shipped') {
                                $barColor = 'border-l-blue-500';
                                $statusBadgeClass = 'bg-blue-50 border border-blue-100 text-blue-800';
                                $statusText = 'Dikirim';
                            } elseif($order->status === 'processing') {
                                $barColor = 'border-l-amber-500';
                                $statusBadgeClass = 'bg-amber-50 border border-amber-100 text-amber-800';
                                $statusText = 'Diproses';
                            } elseif($order->status === 'pending') {
                                $barColor = 'border-l-slate-400';
                                $statusBadgeClass = 'bg-slate-50 border border-slate-200 text-slate-700';
                                $statusText = 'Menunggu';
                            } else {
                                $barColor = 'border-l-rose-500';
                                $statusBadgeClass = 'bg-rose-50 border border-rose-100 text-rose-800';
                                $statusText = 'Dibatalkan';
                            }
                        @endphp
                        <div x-data="{ detailOpen: false }" class="bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-150/70 border-l-4 {{ $barColor }} transition-all duration-300 overflow-hidden">
                            
                            <!-- Closed Card Header -->
                            <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex-1 w-full flex justify-between md:block">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-heading font-black text-gray-800">
                                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                            </h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $statusBadgeClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 font-medium mt-1 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="text-right w-full md:w-auto flex justify-between md:flex-col items-center md:items-end gap-2">
                                    <p class="text-lg sm:text-xl font-heading font-black text-brand-700 mb-0">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                    <button @click="detailOpen = !detailOpen" class="px-4 py-2 border border-indigo-200 hover:border-indigo-400 text-indigo-700 hover:bg-indigo-50/50 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                        <span x-text="detailOpen ? 'Tutup Detail' : 'Lihat Detail'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform duration-200" :class="detailOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Expanded Card Details -->
                            <div x-show="detailOpen" 
                                 x-transition:enter="transition ease-out duration-250"
                                 x-transition:enter-start="opacity-0 -translate-y-3"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-3"
                                 class="border-t border-gray-100 bg-gray-50/50 p-6"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <!-- Left details column (Items & Tracking) -->
                                    <div class="space-y-4">
                                        @if($order->status === 'shipped' && $order->tracking_number)
                                            <div class="p-4 bg-white rounded-2xl border border-blue-100 shadow-sm">
                                                <div class="flex items-center gap-1.5 text-blue-700 font-bold mb-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                    <span class="text-[10px] uppercase tracking-widest font-black">Informasi Pengiriman:</span>
                                                </div>
                                                <p class="text-xs text-gray-500 font-medium">Nomor Resi Resmi:</p>
                                                <p class="font-mono text-xl font-black text-gray-800 leading-tight mt-0.5">{{ $order->tracking_number }}</p>
                                                @if($order->courier_name)
                                                    <p class="text-[10px] text-gray-400 font-medium mt-1">Ekspedisi: <strong class="text-gray-700">{{ $order->courier_name }}</strong></p>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="space-y-3">
                                            <h4 class="text-[10px] uppercase tracking-widest text-gray-400 font-black">Detail Barang Belanjaan:</h4>
                                            <div class="space-y-2">
                                                @foreach($order->orderItems as $item)
                                                    <div class="flex justify-between items-center p-3.5 bg-white shadow-sm border border-gray-150/70 rounded-2xl transition hover:border-gray-300">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-10 w-10 bg-brand-50 border border-brand-100 rounded-xl flex items-center justify-center text-lg">
                                                                🥑
                                                            </div>
                                                            <div>
                                                                <p class="font-bold text-gray-850 text-sm">
                                                                    {{ $item->inventory->fruit_type ?? 'Produk Buah' }}
                                                                </p>
                                                                <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">
                                                                    Grade: {{ $item->inventory->grade ?? '-' }} &bull; {{ $item->quantity_kg }} Kg
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <p class="font-black text-gray-800 text-sm">
                                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right details column (Address & Payment) -->
                                    <div class="space-y-4">
                                        <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-150/70">
                                            <h4 class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-3">Alamat Tujuan Pengiriman:</h4>
                                            <div class="space-y-1.5 text-xs text-gray-650 font-semibold leading-relaxed">
                                                <p class="text-sm text-gray-800 font-black">{{ $order->customer_name }}</p>
                                                <p class="mt-1">{{ $order->shipping_address }}</p>
                                                <p>{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                                                <div class="pt-2 border-t border-gray-100 mt-2 text-gray-500 flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                                    <span>{{ $order->customer_phone }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-150/70 flex items-center justify-between gap-4">
                                            <div>
                                                <h4 class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1">Status Pembayaran:</h4>
                                                <span class="text-xs font-bold text-gray-700">Verifikasi Transaksi</span>
                                            </div>
                                            @php
                                                if($order->payment_status === 'paid') {
                                                    $paymentBadgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-100';
                                                    $paymentLabel = '✓ Berhasil Dibayar';
                                                } elseif($order->payment_status === 'pending') {
                                                    $paymentBadgeClass = 'bg-amber-50 text-amber-800 border-amber-100';
                                                    $paymentLabel = '⏳ Menunggu Pembayaran';
                                                } else {
                                                    $paymentBadgeClass = 'bg-rose-50 text-rose-800 border-rose-100';
                                                    $paymentLabel = '✗ Pembayaran Gagal';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-xl border {{ $paymentBadgeClass }}">
                                                {{ $paymentLabel }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons Footer -->
                                <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap justify-end gap-3">
                                    <a href="{{ route('orders.track', $order) }}" class="px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/50 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span>Lacak Paket</span>
                                    </a>
                                    
                                    @if($order->status === 'shipped')
                                        <form action="{{ route('buyer.orders.receive', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin telah menerima pesanan ini dengan baik?');">
                                            @csrf
                                            <button type="submit" class="px-5 py-2.5 bg-brand-650 hover:bg-brand-750 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/10 transition flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span>Pesanan Diterima</span>
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