<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-5 mb-10 border-b border-gray-150 pb-6">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-200/50 px-2.5 py-1 rounded-md">Tahap 1: Persetujuan</span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Estimasi Panen Masuk</h1>
                <p class="text-xs text-gray-500">Daftar penawaran hasil panen dari petani yang menunggu persetujuan admin.</p>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <span class="bg-amber-50 border border-amber-200/50 text-amber-700 text-xs font-extrabold px-3 py-1.5 rounded-xl">{{ $pendingEstimates->total() }} Penawaran</span>
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
            @if($pendingEstimates->isEmpty())
                <div class="px-6 py-16 text-center text-gray-400">
                    <div class="text-5xl mb-4">🌾</div>
                    <p class="text-sm font-bold">Tidak ada estimasi panen yang menunggu persetujuan.</p>
                </div>
            @else
                <!-- Desktop Table -->
                <table class="w-full text-sm divide-y divide-gray-100 hidden md:table">
                    <thead class="bg-gray-50/80 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">Petani</th>
                            <th class="px-6 py-4 text-left">Komoditas</th>
                            <th class="px-6 py-4 text-left">Estimasi Berat</th>
                            <th class="px-6 py-4 text-left">Harga/Kg</th>
                            <th class="px-6 py-4 text-left">Tgl Panen</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-semibold text-gray-600">
                        @foreach($pendingEstimates as $estimate)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-500 text-white flex items-center justify-center font-extrabold text-sm">{{ strtoupper(substr($estimate->user->name, 0, 1)) }}</div>
                                    <div>
                                        <p class="text-gray-800 font-extrabold">{{ $estimate->user->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $estimate->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($estimate->image)
                                        <img src="{{ Storage::url($estimate->image) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100">
                                    @else
                                        <span class="text-xl">🥑</span>
                                    @endif
                                    <span class="text-gray-800 font-extrabold">{{ $estimate->fruit_type }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $estimate->estimated_weight_kg }} <span class="text-xs text-gray-400">Kg</span></td>
                            <td class="px-6 py-4 text-brand-700 font-bold">Rp {{ number_format($estimate->price_per_kg, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $estimate->harvest_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.procurement.approve', $estimate) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="py-1.5 px-4 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-xl text-xs font-bold transition border border-brand-200/50">✓ Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.procurement.reject', $estimate) }}" method="POST" onsubmit="return confirm('Tolak estimasi panen ini?')">
                                        @csrf
                                        <button type="submit" class="py-1.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition border border-rose-200/50">✕ Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($pendingEstimates as $estimate)
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-extrabold text-gray-800">{{ $estimate->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $estimate->fruit_type }} · {{ $estimate->estimated_weight_kg }} Kg</p>
                            </div>
                            <span class="text-brand-700 font-bold text-sm">Rp {{ number_format($estimate->price_per_kg, 0, ',', '.') }}/Kg</span>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.procurement.approve', $estimate) }}" method="POST" class="flex-1">@csrf
                                <button type="submit" class="w-full py-2 bg-brand-600 text-white rounded-xl text-xs font-bold">Setujui</button>
                            </form>
                            <form action="{{ route('admin.procurement.reject', $estimate) }}" method="POST" class="flex-1" onsubmit="return confirm('Tolak?')">@csrf
                                <button type="submit" class="w-full py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold border border-rose-200">Tolak</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="mt-6">{{ $pendingEstimates->links() }}</div>
    </div>
</x-app-layout>
