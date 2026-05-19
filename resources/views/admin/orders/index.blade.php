<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Pesanan Pembeli - Admin Dashboard" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section with melayang glassmorphic style -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-premium">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-brand-900 to-brand-700 bg-clip-text text-transparent">
                    Pesanan Pembeli
                </h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola seluruh transaksi masuk dan proses logistik</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-semibold text-sm transition-all duration-300 shadow-sm border border-gray-200/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        @if($orders->isEmpty())
            <!-- Empty state with beautiful card -->
            <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-12 text-center shadow-premium">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-50 text-amber-500 mb-4 border border-amber-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4.5m15 0a2 2 0 01-2 2H6a2 2 0 01-2-2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Pesanan Baru</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1">Saat ini tidak ada pesanan yang sudah dibayar dan memerlukan tindakan proses Anda.</p>
            </div>
        @else
            <!-- Responsive Premium Card-based Table list -->
            <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl shadow-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-brand-50/50 border-b border-brand-100 text-brand-900">
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Nama Pembeli</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Ekspedisi / Kurir</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Total Transaksi</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Status Pesanan</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="px-6 py-4.5 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-brand-50/20 transition-colors duration-200">
                                    <td class="px-6 py-4 font-bold text-brand-900">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 hover:text-brand-600 transition-colors">
                                            <span class="text-gray-400 font-medium">#</span>{{ $order->id }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->customer_phone }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200 uppercase">
                                                🚚 {{ $order->courier_name }}
                                            </span>
                                            <p class="text-[10px] text-gray-500 mt-1 pl-1 font-medium">{{ $order->courier_service }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-brand-800">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border transition-all duration-300
                                            @if($order->status === 'pending')
                                                bg-amber-50 text-amber-700 border-amber-200
                                            @elseif($order->status === 'processing')
                                                bg-emerald-50 text-emerald-700 border-emerald-200
                                            @elseif($order->status === 'shipped')
                                                bg-indigo-50 text-indigo-700 border-indigo-200
                                            @elseif($order->status === 'delivered')
                                                bg-teal-50 text-teal-700 border-teal-200
                                            @else
                                                bg-rose-50 text-rose-700 border-rose-200
                                            @endif
                                        ">
                                            @if($order->status === 'processing')
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                                Dibayar
                                            @elseif($order->status === 'pending')
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                Pending
                                            @elseif($order->status === 'shipped')
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                                                Dikirim
                                            @elseif($order->status === 'delivered')
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 mr-1.5"></span>
                                                Diterima
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 hover:text-brand-900 border border-brand-200 rounded-lg text-xs font-bold transition-all duration-300">
                                            Kelola
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Custom Premium Pagination Style -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
