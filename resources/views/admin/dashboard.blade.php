<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Admin Title bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-200/50 px-2.5 py-1 rounded-md">
                    Konsol Administrasi & Rantai Pasok
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Admin Dashboard</h1>
                <p class="text-xs text-gray-500">Kelola alur rantai pasok buah: Estimasi Panen → Pengadaan → Gudang → QC → Katalog → Penjualan.</p>
            </div>
            
            <div class="shrink-0 flex items-center gap-2">
                <!-- Button removed as per user request -->
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
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
                <div class="flex items-center justify-between min-w-[700px] gap-1">
                    @php
                    $steps = [
                        ['label' => 'Estimasi Panen', 'count' => $pendingEstimates, 'color' => 'amber', 'icon' => '🌾', 'route' => route('admin.procurement.pending')],
                        ['label' => 'Disetujui', 'count' => $approvedEstimates, 'color' => 'blue', 'icon' => '✅', 'route' => route('admin.procurement.approved')],
                        ['label' => 'Pengadaan', 'count' => $activeProcurements, 'color' => 'indigo', 'icon' => '📋', 'route' => route('admin.procurement.index')],
                        ['label' => 'Gudang & QC', 'count' => $receivedAtWarehouse, 'color' => 'purple', 'icon' => '🏭', 'route' => route('admin.qc.queue')],
                    ];
                    @endphp
                    @foreach($steps as $i => $step)
                        <a href="{{ $step['route'] }}" class="flex-1 group">
                            <div class="bg-{{ $step['color'] }}-50 border border-{{ $step['color'] }}-200/50 rounded-2xl p-4 text-center hover:shadow-md hover:scale-[1.03] transition-all duration-200 relative">
                                <div class="text-2xl mb-2">{{ $step['icon'] }}</div>
                                <p class="text-[9px] font-extrabold uppercase tracking-widest text-{{ $step['color'] }}-600 mb-1">{{ $step['label'] }}</p>
                                <p class="text-xl font-heading font-black text-{{ $step['color'] }}-800">{{ $step['count'] }}</p>
                            </div>
                        </a>
                        @if($i < count($steps) - 1)
                            <div class="flex-shrink-0 text-gray-300 font-bold text-lg">→</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Metric Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-10">
            <a href="{{ route('admin.procurement.pending') }}" class="block group">
                <div class="bg-gradient-to-tr from-amber-600 to-orange-500 text-white p-6 rounded-2xl shadow-md group-hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative overflow-hidden h-full">
                    <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-amber-100">Estimasi Panen Masuk</p>
                    <p class="text-4xl font-heading font-black mt-3 leading-none">{{ $pendingEstimates }} <span class="text-xs font-extrabold text-amber-200">Penawaran</span></p>
                </div>
            </a>
            
            <a href="{{ route('admin.procurement.index') }}" class="block group">
                <div class="bg-gradient-to-tr from-indigo-700 via-indigo-600 to-indigo-500 text-white p-6 rounded-2xl shadow-md group-hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative overflow-hidden h-full">
                    <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-200">Transaksi Pengadaan Aktif</p>
                    <p class="text-4xl font-heading font-black mt-3 leading-none">{{ $activeProcurements }} <span class="text-xs font-extrabold text-indigo-200">Proses</span></p>
                </div>
            </a>
            
            <a href="{{ route('admin.inventory.index') }}" class="block group">
                <div class="bg-gradient-to-tr from-brand-600 to-emerald-500 text-white p-6 rounded-2xl shadow-md group-hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative overflow-hidden h-full">
                    <div class="absolute -right-5 -bottom-5 h-20 w-20 bg-white/10 rounded-full blur-xl"></div>
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-brand-100">Total Stok Gudang</p>
                    <p class="text-4xl font-heading font-black mt-3 leading-none">{{ number_format($totalStock, 1, ',', '.') }} <span class="text-xs font-extrabold text-brand-100">Kg</span></p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Column 1 & 2 -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Paid Orders Table -->
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-brand-50/50 to-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-heading font-extrabold text-gray-800 text-base flex items-center gap-2">
                                <span class="h-1.5 w-3 rounded bg-brand-500"></span>
                                Pesanan Pembeli (Akan Dikemas)
                            </h2>
                            <p class="text-xs text-gray-400 mt-0.5">Transaksi lunas dari konsumen yang siap dikemas dan dikirim.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-3">
                            <span class="bg-brand-50 border border-brand-200/50 text-brand-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">{{ $totalPaidOrders }} Pesanan Lunas</span>
                            <a href="{{ route('admin.orders.history') }}" class="text-xs font-extrabold px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition border border-indigo-200/50">Riwayat</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-extrabold px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition">Semua</a>
                        </div>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        @if($paidOrders->isEmpty())
                            <div class="px-6 py-12 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-xs font-bold">Semua pesanan lunas sudah terkirim!</p>
                            </div>
                        @else
                            <table class="w-full text-sm divide-y divide-gray-100">
                                <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Order ID</th>
                                        <th class="px-6 py-4 text-left">Nama Pembeli</th>
                                        <th class="px-6 py-4 text-left">Total</th>
                                        <th class="px-6 py-4 text-left">Tanggal</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                                    @foreach($paidOrders as $order)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 font-heading font-extrabold text-indigo-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-6 py-4"><p class="text-gray-800 font-extrabold">{{ $order->customer_name }}</p></td>
                                            <td class="px-6 py-4 text-brand-700 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-xs text-gray-400">{{ $order->created_at->format('d M, H:i') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="py-1 inline-flex items-center px-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition">Proses</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>




</x-app-layout>