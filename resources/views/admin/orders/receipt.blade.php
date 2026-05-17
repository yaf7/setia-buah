<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pesanan #{{ $order->id }}</title>
    <!-- Tailwind form print setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { font-size: 12pt; color: #000; background: #fff; }
            .no-print { display: none !important; }
            .print-container { width: 100%; border: none; shadow: none; padding: 0; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow print-container">
        
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div>
                <h1 class="text-3xl font-bold text-green-700">Setia Buah</h1>
                <p class="text-gray-600">Jl. Pertanian No. 1, Kota Subur</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">RESI PESANAN</h2>
                <p class="text-gray-600">No: #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="font-bold text-gray-700 mb-2">Informasi Pelanggan:</h3>
            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p><strong>Status Pesanan:</strong> <span class="uppercase font-semibold">{{ $order->status }}</span></p>
        </div>

        <table class="w-full text-left mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b-2 border-gray-300">
                    <th class="py-2 px-4">Deskripsi</th>
                    <th class="py-2 px-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-200">
                    <td class="py-4 px-4">Paket Pembelian Buah Segar (Sesuai Invoice Detail)</td>
                    <td class="py-4 px-4 text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-bold text-lg">
                    <td class="py-4 px-4 text-right">TOTAL KESELURUHAN</td>
                    <td class="py-4 px-4 text-right text-green-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-12 text-center text-gray-600 text-sm">
            <p>Terima kasih telah berbelanja di Setia Buah.</p>
            <p>Kepercayaan Anda adalah kualitas kami.</p>
        </div>

        <!-- Print Button -->
        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="min-h-[44px] px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold shadow">
                🖨️ Cetak Resi
            </button>
            <a href="{{ url()->previous() }}" class="min-h-[44px] px-6 py-2 text-indigo-600 ml-4 hover:underline">Kembali</a>
        </div>
    </div>
</body>
</html>