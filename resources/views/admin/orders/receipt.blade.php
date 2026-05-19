<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pesanan #{{ $order->id }} - SetiaBuah</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            body { font-size: 11pt; color: #000; background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .print-container { width: 100%; border: none; box-shadow: none; padding: 0; margin: 0; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        }
    </style>
</head>
<body class="bg-slate-50 p-4 sm:p-8 text-slate-800 antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-2xl w-full bg-white p-6 sm:p-10 rounded-3xl border border-slate-100 shadow-xl print-container">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-emerald-600">SetiaBuah</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">E-Commerce & Food Traceability</p>
                <p class="text-xs text-slate-500 mt-1 font-medium">Jl. Agroteknologi Raya No. 12, Subur Lestari</p>
            </div>
            <div class="sm:text-right">
                <h2 class="text-lg font-extrabold text-slate-900 tracking-wider">INVOICE RESI</h2>
                <p class="text-sm font-bold text-slate-600 mt-1">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-slate-400 font-semibold mt-1">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Customer & Logistics details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 bg-slate-50 p-5 rounded-2xl border border-slate-100/50">
            <div class="border-l-2 border-emerald-500 pl-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Penerima Manfaat</h3>
                <p class="text-sm font-bold text-slate-900">{{ $order->customer_name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $order->customer_phone }}</p>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            </div>
            <div class="border-l-2 border-amber-500 pl-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Info Pengiriman</h3>
                <p class="text-sm font-bold text-slate-900 uppercase">🚚 {{ $order->courier_name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $order->courier_service }}</p>
                <p class="text-xs mt-1">
                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                        {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Purchase Table -->
        <table class="w-full text-left mb-8 border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider">
                    <th class="py-3 px-4">Deskripsi Pembelian Buah Segar</th>
                    <th class="py-3 px-4 text-right">Total Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="text-sm">
                    <td class="py-4 px-4 font-medium">
                        Paket Pembelian Buah Pilihan Petani Lokal
                        <p class="text-[11px] text-slate-400 mt-1 font-medium">(Rincian item terlampir pada sistem database e-commerce)</p>
                    </td>
                    <td class="py-4 px-4 text-right font-bold text-slate-900">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="py-4 px-4 text-slate-500 font-medium">Ongkos Kirim Logistik</td>
                    <td class="py-4 px-4 text-right font-bold text-slate-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-extrabold text-base border-t border-slate-200 text-slate-900">
                    <td class="py-4 px-4 text-right">TOTAL PEMBAYARAN</td>
                    <td class="py-4 px-4 text-right text-emerald-600 text-lg font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer memo -->
        <div class="mt-12 text-center text-slate-400 text-xs font-semibold tracking-wide border-t border-slate-100 pt-6">
            <p>Terima kasih telah mendukung kesejahteraan petani lokal melalui SetiaBuah.</p>
            <p class="text-emerald-500 mt-1">Kepercayaan Anda Adalah Kebanggaan Mutu Kami</p>
        </div>

        <!-- Print Action Buttons -->
        <div class="mt-8 flex justify-center gap-3 no-print">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-sm shadow-md transition-colors">
                🖨️ Cetak Resi
            </button>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition-colors border border-slate-200">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>