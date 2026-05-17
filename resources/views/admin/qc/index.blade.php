<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Antrean Quality Control (QC)</h1>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">{{ $pendingQC }} Pending</span>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Petani</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Buah (Grade)</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estimasi Berat</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendingItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span>{{ $item->fruit_type }}</span>
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded-md text-gray-600 mt-1">Grade {{ $item->grade }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $item->estimated_weight_kg }} Kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <a href="{{ route('admin.qc.create', $item) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800 rounded-md font-semibold transition-colors duration-200">
                                    Lakukan QC
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-500 font-medium">Tidak ada antrean QC saat ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile View QC -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($pendingItems as $item)
                        <div class="p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-base font-bold text-gray-900">{{ $item->user->name }}</p>
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $item->estimated_weight_kg }} Kg</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ $item->fruit_type }} (Grade {{ $item->grade }})</p>
                            <a href="{{ route('admin.qc.create', $item) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition">
                                Lakukan QC
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Tidak ada antrean QC.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6">
            {{ $pendingItems->links() }}
        </div>
    </div>
</x-app-layout>
