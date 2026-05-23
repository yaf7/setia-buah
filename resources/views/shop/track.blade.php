<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Lacak Progress Pesanan #{{ $order->id }} - SetiaBuah" />
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        @if(session('success'))
            <div class="mb-8 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <p class="text-sm font-bold">Terima Kasih!</p>
                    <p class="text-xs text-brand-600 font-medium mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Tracking Board Container -->
        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden mb-8">
            
            <!-- Dynamic Status Header -->
            <div class="bg-gradient-to-tr from-brand-700 via-brand-600 to-emerald-600 px-6 sm:px-8 py-6 text-white relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.1),transparent_40%)]"></div>
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                    <div class="space-y-1">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand-200 bg-brand-500/20 px-2.5 py-1 rounded-md border border-brand-400/20">
                            Status Pengiriman Aktif
                        </span>
                        <h1 class="text-lg sm:text-xl font-heading font-extrabold mt-1.5">ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
                        <p class="text-xs text-brand-100 font-medium">Pesanan dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-white/10 text-[10px] font-extrabold uppercase border border-white/20">
                            Logistik: {{ strtoupper($order->status === 'processing' ? 'diproses' : $order->status) }}
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-white/10 text-[10px] font-extrabold uppercase border border-white/20">
                            Pembayaran: {{ strtoupper($order->payment_status === 'paid' ? 'lunas' : $order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content Area split-grid -->
            <div class="p-6 sm:p-8 flex flex-col md:flex-row gap-8 items-stretch">
                
                <!-- Left Hand: Clean Left-Aligned Vertical Responsive Timeline -->
                <div class="flex-grow w-full md:w-3/5 space-y-6">
                    <h2 class="font-heading font-extrabold text-gray-800 text-base mb-6 flex items-center gap-2">
                        <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                        Alur Status Pengiriman
                    </h2>

                    <!-- Vertical Timeline Root -->
                    <div class="relative pl-6 sm:pl-8 border-l border-gray-150 space-y-8 ml-3 py-1">
                        
                        <!-- Step 1: Dipesan -->
                        <div class="relative">
                            <!-- Bullet Indicator Pin -->
                            <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-brand-600 border-4 border-brand-100 flex items-center justify-center text-white text-[9px] font-black shadow shadow-brand-500/20">
                                ✓
                            </div>
                            <!-- Card body -->
                            <div class="bg-gray-50/50 border border-gray-100 p-4 rounded-2xl space-y-1">
                                <h3 class="font-heading font-extrabold text-gray-800 text-sm">Pesanan Diterima Sistem</h3>
                                <p class="text-xs text-gray-400 font-medium">Record pesanan berhasil disimpan, menanti proses pembayaran.</p>
                                <span class="inline-block text-[10px] font-extrabold text-gray-400 mt-1 bg-white border border-gray-150 px-2 py-0.5 rounded-lg">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>

                        <!-- Step 2: Dibayar -->
                        <div class="relative">
                            <!-- Bullet Indicator Pin -->
                            @if(in_array($order->payment_status, ['paid']))
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-brand-600 border-4 border-brand-100 flex items-center justify-center text-white text-[9px] font-black shadow shadow-brand-500/20">
                                    ✓
                                </div>
                            @else
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-gray-400 text-[10px] font-extrabold">
                                    2
                                </div>
                            @endif
                            <!-- Card body -->
                            <div class="p-4 rounded-2xl border transition duration-150 {{ in_array($order->payment_status, ['paid']) ? 'bg-gray-50/50 border-gray-100' : 'bg-white border-dashed border-gray-200 opacity-60' }}">
                                <h3 class="font-heading font-extrabold text-gray-800 text-sm">Pembayaran Berhasil</h3>
                                <p class="text-xs text-gray-400 font-medium">Dana terverifikasi otomatis oleh Midtrans. Buah siap disiapkan di gudang.</p>
                            </div>
                        </div>

                        <!-- Step 3: Dikirim -->
                        <div class="relative">
                            <!-- Bullet Indicator Pin -->
                            @if(in_array($order->status, ['shipped', 'delivered']))
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-brand-600 border-4 border-brand-100 flex items-center justify-center text-white text-[9px] font-black shadow shadow-brand-500/20">
                                    ✓
                                </div>
                            @else
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-gray-400 text-[10px] font-extrabold">
                                    3
                                </div>
                            @endif
                            <!-- Card body -->
                            <div class="p-4 rounded-2xl border transition duration-150 {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-gray-50/50 border-gray-100' : 'bg-white border-dashed border-gray-200 opacity-60' }}">
                                <h3 class="font-heading font-extrabold text-gray-800 text-sm">Pesanan Dikirim</h3>
                                <p class="text-xs text-gray-400 font-medium">Kurir logistik telah menjemput paket di gudang SetiaBuah.</p>
                                
                                @if($order->tracking_number)
                                    <div class="mt-2.5 flex items-center gap-1.5 text-[11px] font-bold text-gray-500 bg-white border border-gray-150 px-2.5 py-1.5 rounded-xl w-max shadow-sm">
                                        <span>Nomor Resi:</span>
                                        <span class="font-mono text-brand-700 select-all">{{ $order->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Step 4: Diterima -->
                        <div class="relative">
                            <!-- Bullet Indicator Pin -->
                            @if($order->status === 'delivered')
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-brand-600 border-4 border-brand-100 flex items-center justify-center text-white text-[9px] font-black shadow shadow-brand-500/20">
                                    ✓
                                </div>
                            @else
                                <div class="absolute -left-[35px] sm:-left-[43px] top-0 h-6 w-6 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-gray-400 text-[10px] font-extrabold">
                                    4
                                </div>
                            @endif
                            <!-- Card body -->
                            <div class="p-4 rounded-2xl border transition duration-150 {{ $order->status === 'delivered' ? 'bg-gray-50/50 border-gray-100' : 'bg-white border-dashed border-gray-200 opacity-60' }}">
                                <h3 class="font-heading font-extrabold text-gray-800 text-sm">Paket Diterima</h3>
                                <p class="text-xs text-gray-400 font-medium">Paket buah segar telah sampai di tangan penerima dengan aman.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Hand: Shipping Summary Details card -->
                <div class="w-full md:w-2/5 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-8 flex flex-col justify-between">
                    <div class="space-y-6">
                        <!-- Shipping Info -->
                        <div class="space-y-3">
                            <h3 class="font-heading font-extrabold text-gray-800 text-sm">Detail Pengiriman</h3>
                            <div class="bg-gray-50/60 border border-gray-100 p-4 rounded-2xl space-y-2 text-xs">
                                <div>
                                    <span class="block font-bold text-gray-400">Penerima:</span>
                                    <span class="block font-extrabold text-gray-700 mt-0.5">{{ $order->customer_name ?? auth('buyer')->user()->name }}</span>
                                </div>
                                <div class="pt-1.5">
                                    <span class="block font-bold text-gray-400">Alamat:</span>
                                    <span class="block font-medium text-gray-600 mt-0.5 leading-relaxed">{{ $order->shipping_address }}</span>
                                </div>
                                
                                @if($order->shipment)
                                    <div class="pt-2 border-t border-gray-200/50 mt-2 space-y-1 text-gray-500 font-bold">
                                        <p>Kurir: <span class="text-gray-700 uppercase">{{ $order->shipment->courier_name ?? '-' }} {{ $order->shipment->courier_service ?? '' }}</span></p>
                                        <p>Estimasi: <span class="text-gray-700">{{ $order->shipment->estimated_delivery ?? '-' }} Hari</span></p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Final Transaction Bills -->
                        <div class="space-y-3">
                            <h3 class="font-heading font-extrabold text-gray-800 text-sm">Rincian Pembayaran</h3>
                            <div class="bg-gray-50/60 border border-gray-100 p-4 rounded-2xl space-y-3.5 text-xs font-bold">
                                <div class="flex justify-between text-gray-400">
                                    <span>Metode Bayar</span>
                                    <span class="text-gray-700 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Status Bayar</span>
                                    <span class="uppercase px-2 py-0.5 rounded {{ $order->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $order->payment_status == 'paid' ? 'lunas' : $order->payment_status }}
                                    </span>
                                </div>
                                <div class="h-px bg-gray-200 my-1"></div>
                                <div class="flex justify-between text-gray-500 text-sm">
                                    <span>Total Tagihan</span>
                                    <span class="text-brand-700 font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Shield badge -->
                    <div class="mt-8 md:mt-0 pt-6 border-t border-gray-100 flex items-center gap-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <span class="text-[9px] font-bold text-gray-400 leading-tight">Keaslian & kesegaran buah terverifikasi oleh para ahli di Quality Control.</span>
                    </div>

                </div>
            </div>

        </div>
        
        <!-- Back Navigation Button bottom -->
        <div class="text-center mt-10">
            <a href="{{ route('shop.index') }}" class="py-2.5 inline-flex items-center gap-1.5 px-6 py-2 border border-gray-200 hover:border-brand-200 rounded-xl bg-white hover:bg-brand-50/20 text-xs sm:text-sm font-extrabold text-gray-500 hover:text-brand-600 transition shadow-sm">
                ← Kembali Belanja Buah Segar
            </a>
        </div>
    </div>
</x-app-layout>