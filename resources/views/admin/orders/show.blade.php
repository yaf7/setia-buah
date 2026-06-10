<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Detail Pesanan #{{ $order->id }} - Admin" />
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-premium">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-100 text-brand-800 mb-2">
                    ID Pesanan: #{{ $order->id }}
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Detail Transaksi</h1>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-semibold text-sm transition-all duration-300 border border-gray-200/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 text-sm">✓</div>
                <p class="text-emerald-800 text-sm font-medium mt-0.5">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center shrink-0 text-sm">🛈</div>
                <p class="text-brand-800 text-sm font-medium mt-0.5">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Action Card -->
                <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-6 shadow-premium">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Status & Tindakan Administrasi
                    </h2>
                    
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 mb-6">
                        <div class="text-3xl p-2.5 bg-white rounded-xl shadow-sm border border-gray-200/50">
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
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Pesanan Saat Ini:</p>
                            <p class="text-xl font-black text-gray-900">
                                @if($order->status === 'processing')
                                    Dibayar (Sedang Dikemas)
                                @else
                                    {{ ucfirst($order->status) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Payment Pending Actions -->
                    @if($order->payment_status !== 'paid')
                        <div class="p-5 bg-amber-50/50 border border-amber-200/60 rounded-xl mb-6 space-y-3.5">
                            <div class="flex items-start gap-2.5">
                                <span class="text-amber-500 font-bold text-base mt-0.5">⚠️</span>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-900">Menunggu Pembayaran</h4>
                                    <p class="text-xs text-amber-700/80 font-medium mt-0.5">Pembayaran pembeli belum terkonfirmasi otomatis dari Midtrans. Anda dapat memverifikasi status secara instan.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                <form action="{{ route('admin.orders.check-payment-status', $order) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold text-xs transition-colors shadow-sm">
                                        🔍 Cek Status Midtrans
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.payment-success', $order) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs transition-colors shadow-sm">
                                        ✓ Konfirmasi Manual
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Shipping Action for Paid Orders -->
                    @if($order->status === 'pending' && $order->payment_status === 'paid')
                        <div class="space-y-3 p-5 bg-emerald-50/50 border border-emerald-200/60 rounded-xl">
                            <h4 class="text-sm font-bold text-emerald-900">Persetujuan Pesanan</h4>
                            <p class="text-xs text-emerald-700/80 font-medium mb-3">Pesanan telah lunas. Tekan tombol di bawah untuk menyetujui dan memproses panen buah.</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <form action="{{ route('admin.orders.approve', $order) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                                        ✓ Setujui & Siapkan Pengiriman
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.reject', $order) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="w-full py-2.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-sm transition-colors border border-rose-200">
                                        ✗ Tolak & Batalkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($order->status === 'processing')
                        <div class="p-5 bg-brand-50/40 border border-brand-200/50 rounded-xl">
                            <h4 class="text-sm font-bold text-brand-900 mb-2">Input Logistik & Pengiriman</h4>
                            <p class="text-xs text-brand-700/80 font-medium mb-4">Buah siap dikirim. Harap masukkan nomor resi ekspedisi resmi (Biteship/Kurir Pilihan) untuk memicu timeline pelacakan pembeli.</p>
                            
                            <form action="{{ route('admin.orders.shipped', $order) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nomor Resi / AWB</label>
                                    <input type="text" name="tracking_number" placeholder="Contoh: SI123456789AWB" 
                                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 hover:border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 rounded-xl text-sm font-medium transition-all outline-none" required>
                                </div>
                                <button type="submit" class="w-full py-2.5 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                                    🚀 Kirim & Beritahu Pembeli
                                </button>
                            </form>
                        </div>
                    @elseif($order->status === 'shipped')
                        <div class="p-5 bg-emerald-50/50 border border-emerald-200/60 rounded-xl space-y-3">
                            <h4 class="text-sm font-bold text-emerald-900 flex items-center gap-2">
                                🚚 Pesanan Sedang Dikirim
                            </h4>
                            <p class="text-xs text-emerald-700/80 font-medium">Buah sedang dalam perjalanan dengan nomor resi <strong class="font-mono text-emerald-800">{{ $order->tracking_number }}</strong>.</p>
                            <div class="w-full py-2.5 px-5 py-2.5 bg-gray-200 text-gray-700 rounded-xl font-bold text-sm shadow-sm text-center">
                                ⏳ Menunggu Konfirmasi Penerimaan dari Pembeli
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Items Card -->
                <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-6 shadow-premium">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Item Buah Segar
                    </h2>
                    
                    <div class="divide-y divide-gray-100">
                        @forelse($items as $item)
                            <div class="flex justify-between items-center py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-100 flex items-center justify-center text-lg font-bold">
                                        🍊
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 leading-snug">{{ $item->inventory->fruit_type ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500 mt-1 font-medium">
                                            {{ $item->quantity_kg }} Kg x Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}/Kg
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    @if($item->inventory && $item->inventory->grade)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 mt-1">
                                            Grade {{ $item->inventory->grade }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-6 text-sm">Tidak ada item belanja.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-6 shadow-premium">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Detail Alamat Pengiriman
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-medium text-gray-700">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Penerima</span>
                            <p class="font-bold text-gray-900 text-base">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Alamat Lengkap</span>
                            <p class="text-xs text-gray-900 font-bold leading-relaxed">{{ $order->shipping_address }}</p>
                            <p class="text-[11px] text-gray-500 mt-1">
                                {{ $order->shipping_city }}, Prov. {{ $order->shipping_province }} ({{ $order->shipping_postal_code }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="space-y-6">
                <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-6 shadow-premium sticky top-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-3.5 text-sm font-medium text-gray-600 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal Belanja</span>
                            <span class="text-gray-900 font-bold">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="text-gray-900 font-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3.5 flex justify-between font-extrabold text-base">
                            <span class="text-gray-900">Total Akhir</span>
                            <span class="text-brand-700 text-lg font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 pt-4 font-semibold text-sm">
                        <div>
                            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Status Bayar</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border
                                @if($order->payment_status === 'paid')
                                    bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($order->payment_status === 'unpaid')
                                    bg-amber-50 text-amber-700 border-amber-200
                                @else
                                    bg-rose-50 text-rose-700 border-rose-200
                                @endif
                            ">
                                {{ $order->payment_status === 'paid' ? 'LUNAS (Midtrans)' : 'BELUM DIBAYAR' }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kurir & Layanan</span>
                            <p class="text-gray-900 font-bold text-sm uppercase">🚚 {{ $order->courier_name }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ $order->courier_service }}</p>
                        </div>

                        @if($order->tracking_number)
                            <div>
                                <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">No. Resi AWB</span>
                                <span class="inline-flex px-3 py-1 bg-brand-50 text-brand-800 text-xs font-bold rounded-lg border border-brand-100 font-mono">
                                    {{ $order->tracking_number }}
                                </span>
                            </div>
                        @endif

                        <div class="border-t border-gray-100 pt-3.5 flex justify-between items-center">
                            <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                🖨️ Cetak Resi Pembelian
                            </a>
                        </div>

                        <div class="text-[11px] text-gray-400 text-center font-medium pt-2">
                            Dibuat: {{ $order->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
