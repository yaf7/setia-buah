<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Pesanan Pembeli - Admin Dashboard" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Pesanan Pembeli</h1>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Kembali ke Dashboard
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <p class="text-yellow-800">Tidak ada pesanan yang sudah dibayar dan perlu diproses.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">No. Pesanan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Pembeli</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium">{{ $order->customer_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing')
                                            bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped')
                                            bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ $order->status === 'processing' ? 'Dibayar' : ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm font-medium">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
