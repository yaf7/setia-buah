<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Riwayat Pembelian - Admin Dashboard" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-premium">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-brand-900 to-brand-700 bg-clip-text text-transparent">
                    Riwayat Pembelian
                </h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Lihat dan filter seluruh riwayat transaksi pesanan lunas</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-semibold text-sm transition-all duration-300 shadow-sm border border-gray-200/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Filter Form -->
        <div class="mb-6 bg-white p-5 rounded-2xl border border-gray-150 shadow-sm">
            <form method="GET" action="{{ route('admin.orders.history') }}" class="flex flex-col sm:flex-row items-end gap-4">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Bulan</label>
                    <select name="month" class="w-full sm:w-48 rounded-xl border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 py-2.5 px-3">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tahun</label>
                    <select name="year" class="w-full sm:w-32 rounded-xl border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 py-2.5 px-3">
                        <option value="">Semua</option>
                        @php $currentYear = now()->year; @endphp
                        @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold transition">
                    Terapkan Filter
                </button>
                
                @if(request()->filled('month') || request()->filled('year'))
                    <a href="{{ route('admin.orders.history') }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl p-12 text-center shadow-premium">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4 border border-gray-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Data Riwayat Pembelian</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1">Tidak ada data transaksi lunas pada filter yang Anda pilih.</p>
            </div>
        @else
            <!-- Table list -->
            <div class="bg-white/80 backdrop-blur-md border border-white/60 rounded-2xl shadow-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-600">
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Pembeli</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Total Transaksi</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4.5 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4.5 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-bold text-indigo-700">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                            #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-brand-700">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border
                                            @if($order->status === 'delivered') bg-teal-50 text-teal-700 border-teal-200
                                            @elseif($order->status === 'shipped') bg-indigo-50 text-indigo-700 border-indigo-200
                                            @elseif($order->status === 'processing') bg-emerald-50 text-emerald-700 border-emerald-200
                                            @else bg-gray-50 text-gray-700 border-gray-200 @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
