<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-200/50 px-2.5 py-1 rounded-md">Manajemen Pengadaan</span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Transaksi Pengadaan</h1>
                <p class="text-xs text-gray-500">Daftar seluruh transaksi pengadaan hasil panen dari petani.</p>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('admin.procurement.pending') }}" class="text-xs font-extrabold px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 transition border border-amber-200/50">Estimasi Masuk</a>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl shadow-premium flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white border border-gray-150 rounded-3xl shadow-premium overflow-hidden">
            @if($procurements->isEmpty())
                <div class="px-6 py-16 text-center text-gray-400">
                    <div class="text-5xl mb-4">📋</div>
                    <p class="text-sm font-bold">Belum ada transaksi pengadaan.</p>
                </div>
            @else
                <table class="w-full text-sm divide-y divide-gray-100 hidden md:table">
                    <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">No. Pengadaan</th>
                            <th class="px-6 py-4 text-left">Petani</th>
                            <th class="px-6 py-4 text-left">Komoditas</th>
                            <th class="px-6 py-4 text-left">Total Biaya</th>
                            <th class="px-6 py-4 text-left">Metode</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                        @foreach($procurements as $proc)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-heading font-extrabold text-indigo-600 text-xs">{{ $proc->procurement_number }}</td>
                            <td class="px-6 py-4 font-extrabold text-gray-800">{{ $proc->harvestEstimate->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $proc->harvestEstimate->fruit_type ?? '-' }} <span class="text-xs text-gray-400">· {{ $proc->agreed_weight_kg }} Kg</span></td>
                            <td class="px-6 py-4 text-brand-700 font-bold">Rp {{ number_format($proc->total_cost, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[9px] font-extrabold rounded-md uppercase {{ $proc->pickup_method === 'pickup' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $proc->pickup_method === 'pickup' ? 'Jemput' : 'Diantar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($proc->status === 'pending_pickup')
                                    <span class="px-2.5 py-1 text-[9px] font-extrabold rounded-md uppercase bg-amber-100 text-amber-800 border border-amber-200/50">Menunggu</span>
                                @elseif($proc->status === 'in_transit')
                                    <span class="px-2.5 py-1 text-[9px] font-extrabold rounded-md uppercase bg-blue-100 text-blue-800 border border-blue-200/50">Dalam Perjalanan</span>
                                @else
                                    <span class="px-2.5 py-1 text-[9px] font-extrabold rounded-md uppercase bg-emerald-100 text-emerald-800 border border-emerald-200/50">Diterima</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($proc->status === 'pending_pickup')
                                        <form action="{{ route('admin.procurement.ship', $proc) }}" method="POST">@csrf
                                            <button class="py-1.5 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition border border-blue-200/50">🚛 Kirim</button>
                                        </form>
                                    @elseif($proc->status === 'in_transit')
                                        <form action="{{ route('admin.procurement.receive', $proc) }}" method="POST">@csrf
                                            <button class="py-1.5 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-bold transition border border-emerald-200/50">📦 Terima</button>
                                        </form>
                                    @elseif($proc->status === 'received')
                                        @if($proc->harvestEstimate && $proc->harvestEstimate->status === 'received')
                                            <a href="{{ route('admin.qc.create', $proc->harvestEstimate) }}" class="py-1.5 px-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-bold transition border border-purple-200/50">🔍 QC</a>
                                        @else
                                            <span class="text-[10px] text-gray-400 font-bold">Selesai</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($procurements as $proc)
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-extrabold text-indigo-600">{{ $proc->procurement_number }}</p>
                                <p class="font-extrabold text-gray-800 text-sm">{{ $proc->harvestEstimate->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $proc->harvestEstimate->fruit_type ?? '-' }} · {{ $proc->agreed_weight_kg }} Kg</p>
                            </div>
                            <p class="text-brand-700 font-bold text-sm">Rp {{ number_format($proc->total_cost, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex gap-2">
                            @if($proc->status === 'pending_pickup')
                                <form action="{{ route('admin.procurement.ship', $proc) }}" method="POST" class="flex-1">@csrf
                                    <button class="w-full py-2 bg-blue-600 text-white rounded-xl text-xs font-bold">🚛 Kirim</button>
                                </form>
                            @elseif($proc->status === 'in_transit')
                                <form action="{{ route('admin.procurement.receive', $proc) }}" method="POST" class="flex-1">@csrf
                                    <button class="w-full py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">📦 Terima di Gudang</button>
                                </form>
                            @elseif($proc->status === 'received' && $proc->harvestEstimate && $proc->harvestEstimate->status === 'received')
                                <a href="{{ route('admin.qc.create', $proc->harvestEstimate) }}" class="flex-1 text-center py-2 bg-purple-600 text-white rounded-xl text-xs font-bold">🔍 Lakukan QC</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="mt-6">{{ $procurements->links() }}</div>
    </div>
</x-app-layout>
