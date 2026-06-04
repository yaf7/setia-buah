<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-200/50 px-2.5 py-1 rounded-md">Detail Pengadaan</span>
            <h1 class="text-2xl font-heading font-extrabold text-gray-800 mt-2">{{ $procurement->procurement_number }}</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-brand-50 border-l-4 border-brand-500 text-brand-800 p-4 rounded-xl">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl">
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl shadow-premium border border-gray-150 space-y-6">
            <!-- Status Timeline -->
            <div class="flex items-center justify-between gap-2 mb-6">
                @php
                $timelineSteps = [
                    ['label' => 'Pengadaan', 'done' => true],
                    ['label' => 'Dalam Perjalanan', 'done' => in_array($procurement->status, ['in_transit', 'received'])],
                    ['label' => 'Diterima Gudang', 'done' => $procurement->status === 'received'],
                ];
                @endphp
                @foreach($timelineSteps as $i => $step)
                    <div class="flex-1 text-center">
                        <div class="h-2 rounded-full {{ $step['done'] ? 'bg-brand-500' : 'bg-gray-200' }} mb-2"></div>
                        <p class="text-[9px] font-extrabold uppercase {{ $step['done'] ? 'text-brand-700' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500 text-xs font-bold">Petani:</span><p class="font-extrabold text-gray-800">{{ $procurement->harvestEstimate->user->name ?? '-' }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Komoditas:</span><p class="font-extrabold text-gray-800">{{ $procurement->harvestEstimate->fruit_type ?? '-' }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Harga/Kg:</span><p class="font-extrabold text-brand-700">Rp {{ number_format($procurement->agreed_price_per_kg, 0, ',', '.') }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Berat:</span><p class="font-extrabold text-gray-800">{{ $procurement->agreed_weight_kg }} Kg</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Total Biaya:</span><p class="font-extrabold text-indigo-700 text-lg">Rp {{ number_format($procurement->total_cost, 0, ',', '.') }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Metode:</span><p class="font-extrabold text-gray-800">{{ $procurement->pickup_method === 'pickup' ? '🚛 Dijemput' : '📦 Diantar' }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Tanggal:</span><p class="font-extrabold text-gray-800">{{ $procurement->procurement_date->format('d M Y') }}</p></div>
                <div><span class="text-gray-500 text-xs font-bold">Admin:</span><p class="font-extrabold text-gray-800">{{ $procurement->admin->name }}</p></div>
            </div>

            @if($procurement->notes)
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 mb-1">Catatan:</p>
                    <p class="text-sm text-gray-700">{{ $procurement->notes }}</p>
                </div>
            @endif

            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <a href="{{ route('admin.procurement.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-bold">← Kembali</a>
                <div class="flex gap-2">
                    @if($procurement->status === 'pending_pickup')
                        <form action="{{ route('admin.procurement.ship', $procurement) }}" method="POST">@csrf
                            <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow transition">🚛 Mulai Pengiriman</button>
                        </form>
                    @elseif($procurement->status === 'in_transit')
                        <form action="{{ route('admin.procurement.receive', $procurement) }}" method="POST">@csrf
                            <button class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow transition">📦 Terima di Gudang</button>
                        </form>
                    @elseif($procurement->status === 'received' && $procurement->harvestEstimate && $procurement->harvestEstimate->status === 'received')
                        <a href="{{ route('admin.qc.create', $procurement->harvestEstimate) }}" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-bold shadow transition">🔍 Lakukan QC</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
