<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-purple-600 bg-purple-50 border border-purple-200/50 px-2.5 py-1 rounded-md">Tahap 5: Quality Control</span>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-gray-800 mt-2">Antrean QC Gudang</h1>
                <p class="text-xs text-gray-500 mt-1">Barang yang sudah diterima di gudang dan siap untuk proses Quality Control.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">{{ $pendingQC }} Siap QC</span>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition">Kembali</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Petani</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Komoditas</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Berat Estimasi</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">No. Pengadaan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendingItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span>{{ $item->fruit_type }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $item->estimated_weight_kg }} Kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-indigo-600 font-bold text-center">{{ $item->procurement->procurement_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <a href="{{ route('admin.qc.create', $item) }}" class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-md font-semibold transition-colors duration-200">
                                    🔍 Lakukan QC
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-4xl mb-3">🏭</div>
                                <p class="text-gray-500 font-medium">Tidak ada barang menunggu QC.<br>Barang akan muncul setelah diterima di gudang.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($pendingItems as $item)
                        <div class="p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-base font-bold text-gray-900">{{ $item->user->name }}</p>
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $item->estimated_weight_kg }} Kg</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">{{ $item->fruit_type }}</p>
                            <p class="text-xs text-indigo-600 font-bold mb-4">{{ $item->procurement->procurement_number ?? '-' }}</p>
                            <a href="{{ route('admin.qc.create', $item) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 text-white rounded-md font-medium hover:bg-purple-700 transition">
                                🔍 Lakukan QC
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Tidak ada antrean QC.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="mt-6">{{ $pendingItems->links() }}</div>
    </div>
</x-app-layout>
