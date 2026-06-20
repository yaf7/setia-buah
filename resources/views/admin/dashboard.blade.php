<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Premium Welcome Banner & Admin Title bar -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-brand-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-10 relative overflow-hidden border border-slate-800">
            <!-- Decorative background blur/svg grids -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-500/20 via-transparent to-transparent"></div>
            <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none translate-x-12 translate-y-12">
                <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 100 100">
                    <path d="M50 15 L80 35 L80 65 L50 85 L20 65 L20 35 Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    <circle cx="50" cy="50" r="15" fill="none" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </div>
            <div class="relative z-10 space-y-2.5">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-500/25 border border-indigo-400/35 text-[9px] font-extrabold tracking-widest uppercase text-indigo-200">
                    Agribusiness Supply Chain Portal
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-black tracking-tight leading-tight">
                    Selamat Bekerja, {{ Auth::user()->name ?? 'Administrator' }}!
                </h1>
                <p class="text-xs sm:text-sm text-indigo-200/90 max-w-xl font-medium leading-relaxed">
                    Pantau alur rantai pasok agribisnis SetiaBuah, lakukan penimbangan dan verifikasi kualitas buah (QC), serta kelola pesanan pembeli secara terpadu.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-850 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-xs font-semibold pl-1 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <!-- ═══════════════════════════════════════════════════════ -->
        <!-- SUPPLY CHAIN PIPELINE VISUAL                           -->
        <!-- ═══════════════════════════════════════════════════════ -->
        <div class="mb-10">
            <h2 class="font-heading font-extrabold text-gray-800 text-base mb-4 flex items-center gap-2">
                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                Alur Rantai Pasok (Supply Chain Pipeline)
            </h2>
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-6 overflow-x-auto">
                <div class="flex items-center justify-between min-w-[800px] gap-2">
                    @php
                    $steps = [
                        [
                            'label' => 'Estimasi Panen', 
                            'count' => $pendingEstimates, 
                            'desc' => 'Verifikasi estimasi tani',
                            'bg' => 'bg-amber-50/50 hover:bg-amber-50 hover:border-amber-300/80', 
                            'border' => 'border-amber-200/50', 
                            'iconBg' => 'bg-amber-100 text-amber-700',
                            'text' => 'text-amber-800',
                            'badge' => 'text-amber-600',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>', 
                            'route' => route('admin.procurement.pending')
                        ],
                        [
                            'label' => 'Disetujui', 
                            'count' => $approvedEstimates, 
                            'desc' => 'Siap kontrak pengadaan',
                            'bg' => 'bg-blue-50/50 hover:bg-blue-50 hover:border-blue-300/80', 
                            'border' => 'border-blue-200/50', 
                            'iconBg' => 'bg-blue-100 text-blue-700',
                            'text' => 'text-blue-800',
                            'badge' => 'text-blue-600',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>', 
                            'route' => route('admin.procurement.approved')
                        ],
                        [
                            'label' => 'Pengadaan', 
                            'count' => $activeProcurements, 
                            'desc' => 'Transaksi & penjemputan',
                            'bg' => 'bg-indigo-50/50 hover:bg-indigo-50 hover:border-indigo-300/80', 
                            'border' => 'border-indigo-200/50', 
                            'iconBg' => 'bg-indigo-100 text-indigo-700',
                            'text' => 'text-indigo-800',
                            'badge' => 'text-indigo-600',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>', 
                            'route' => route('admin.procurement.index')
                        ],
                        [
                            'label' => 'Gudang & QC', 
                            'count' => $receivedAtWarehouse, 
                            'desc' => 'Pemeriksaan mutu & grading',
                            'bg' => 'bg-purple-50/50 hover:bg-purple-50 hover:border-purple-300/80', 
                            'border' => 'border-purple-200/50', 
                            'iconBg' => 'bg-purple-100 text-purple-700',
                            'text' => 'text-purple-800',
                            'badge' => 'text-purple-600',
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>', 
                            'route' => route('admin.qc.queue')
                        ],
                    ];
                    @endphp
                    @foreach($steps as $i => $step)
                        <a href="{{ $step['route'] }}" class="flex-1 group">
                            <div class="{{ $step['bg'] }} {{ $step['border'] }} border rounded-2xl p-5 text-center transition-all duration-300 relative shadow-sm hover:shadow-md hover:-translate-y-1">
                                <div class="w-10 h-10 rounded-xl {{ $step['iconBg'] }} flex items-center justify-center mx-auto mb-3 shadow-inner">
                                    {!! $step['icon'] !!}
                                </div>
                                <h3 class="text-xs font-bold text-gray-800 leading-tight mb-0.5">{{ $step['label'] }}</h3>
                                <p class="text-[9px] text-gray-400 font-semibold mb-2">{{ $step['desc'] }}</p>
                                <div class="inline-flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-gray-900 leading-none">{{ $step['count'] }}</span>
                                    <span class="text-[9px] uppercase tracking-widest font-extrabold {{ $step['badge'] }}">Berkas</span>
                                </div>
                            </div>
                        </a>
                        @if($i < count($steps) - 1)
                            <div class="flex-shrink-0 text-gray-300 px-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Metric Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-10">
            <!-- Shipped Orders -->
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="block group">
                <div class="bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 text-white p-6 rounded-3xl shadow-xl hover:shadow-orange-500/20 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full border border-orange-400/20">
                    <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.124V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.09-1.443a1.875 1.875 0 00-1.853-1.758H18V10.5m-1.25 9.75h-1.5M16.5 18.75h-1.5m1.5-5.25H9.75v-10.5A1.125 1.125 0 0110.875 2.25h9.75c.621 0 1.125.504 1.125 1.125V9M16.5 13.5v3.75" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[9px] font-extrabold tracking-wider uppercase text-amber-100 mb-4">
                            Pesanan Transit
                        </span>
                        <p class="text-xs text-orange-100 font-medium">Dalam Pengiriman</p>
                        <p class="text-3xl font-heading font-black mt-1 leading-none">
                            {{ $ordersShipped }} 
                            <span class="text-sm font-extrabold text-orange-200">Pesanan</span>
                        </p>
                        <p class="text-[10px] text-orange-150 mt-3 font-semibold flex items-center gap-1">
                            Lacak pengiriman kurir &rarr;
                        </p>
                    </div>
                </div>
            </a>
            
            <!-- Warehouse Stock -->
            <a href="{{ route('admin.inventory.index', ['status' => 'gudang']) }}" class="block group">
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-800 text-white p-6 rounded-3xl shadow-xl hover:shadow-indigo-500/20 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full border border-indigo-500/20">
                    <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18m-18 0V6.75A2.25 2.25 0 014.5 4.5h15a2.25 2.25 0 012.25 2.25V13.5m-18 0v5.25A2.25 2.25 0 004.5 21h15a2.25 2.25 0 002.25-2.25V13.5m-10.5-6h5.25m-5.25 3h5.25" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[9px] font-extrabold tracking-wider uppercase text-indigo-200 mb-4">
                            Stok internal
                        </span>
                        <p class="text-xs text-indigo-150 font-medium">Stok Fisik Gudang</p>
                        <p class="text-3xl font-heading font-black mt-1 leading-none">
                            {{ number_format($warehouseStock, 1, ',', '.') }} 
                            <span class="text-sm font-extrabold text-indigo-200">Kg</span>
                        </p>
                        <p class="text-[10px] text-indigo-200/80 mt-3 font-semibold flex items-center gap-1">
                            Kelola ketersediaan fisik &rarr;
                        </p>
                    </div>
                </div>
            </a>
            
            <!-- Catalog Stock -->
            <a href="{{ route('admin.inventory.index', ['status' => 'katalog']) }}" class="block group">
                <div class="bg-gradient-to-br from-brand-600 via-brand-700 to-emerald-600 text-white p-6 rounded-3xl shadow-xl hover:shadow-brand-500/20 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full border border-brand-500/20">
                    <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.2 0 .75.75 0 011.2 0zm12.75 0a.75.75 0 11-1.2 0 .75.75 0 011.2 0z" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[9px] font-extrabold tracking-wider uppercase text-brand-100 mb-4">
                            Komersial
                        </span>
                        <p class="text-xs text-brand-100 font-medium">Stok Katalog Aktif</p>
                        <p class="text-3xl font-heading font-black mt-1 leading-none">
                            {{ number_format($catalogStock, 1, ',', '.') }} 
                            <span class="text-sm font-extrabold text-brand-200">Kg</span>
                        </p>
                        <p class="text-[10px] text-brand-150 mt-3 font-semibold flex items-center gap-1">
                            Atur publish & harga katalog &rarr;
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Paid Orders Table Section -->
        <div class="space-y-8">
            <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-brand-50/30 via-white to-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                            <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                            Pesanan Pembeli (Akan Dikemas)
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5 font-medium">Transaksi lunas dari konsumen yang siap dikemas dan dikirim.</p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 flex-wrap">
                        <span class="bg-brand-50 border border-brand-200/50 text-brand-850 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-xl">
                            {{ $totalPaidOrders }} Pesanan Lunas
                        </span>
                        <a href="{{ route('admin.orders.history') }}" class="text-xs font-bold px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition border border-indigo-200/50">
                            Riwayat
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
                            Semua
                        </a>
                    </div>
                </div>
                <div class="p-0 overflow-x-auto">
                    @if($paidOrders->isEmpty())
                        <div class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-brand-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 mb-1">Semua Pesanan Selesai Diproses</h3>
                            <p class="text-xs text-gray-400 max-w-xs mx-auto font-medium">Bagus sekali! Semua pesanan yang masuk dan lunas saat ini sudah dikemas & terkirim ke kurir.</p>
                        </div>
                    @else
                        <table class="w-full text-sm divide-y divide-gray-100">
                            <thead class="bg-gray-50/80 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order ID</th>
                                    <th class="px-6 py-4 text-left">Nama Pembeli</th>
                                    <th class="px-6 py-4 text-left">Total Pembayaran</th>
                                    <th class="px-6 py-4 text-left">Tanggal Lunas</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                                @foreach($paidOrders as $order)
                                    @php
                                        $words = explode(' ', $order->customer_name);
                                        $initials = count($words) >= 2 
                                            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                                            : strtoupper(substr($order->customer_name, 0, 2));
                                    @endphp
                                    <tr class="hover:bg-gray-50/30 transition">
                                        <td class="px-6 py-4 font-heading">
                                            <span class="px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100/50 text-xs font-black text-indigo-700">
                                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-xl bg-brand-50 border border-brand-100 text-brand-850 font-extrabold text-xs flex items-center justify-center">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <p class="text-gray-800 font-extrabold text-sm">{{ $order->customer_name }}</p>
                                                    <span class="text-[10px] text-gray-400 font-medium">Konsumen Terverifikasi</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-brand-700 font-black text-sm">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-400 font-medium">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="py-2 px-5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-brand-500/10 flex items-center gap-1 justify-center w-max mx-auto">
                                                <span>Proses</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>