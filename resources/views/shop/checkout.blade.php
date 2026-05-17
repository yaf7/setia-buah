<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Checkout Pembayaran - Setia Buah" />
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout & Pengiriman</h1>

        <div class="flex flex-col-reverse lg:flex-row gap-8">
            <!-- Form -->
            <div class="flex-1 bg-white p-6 rounded-lg shadow">
                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">1. Data Penerima</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                                <input type="text" name="customer_name" value="{{ auth('buyer')->user()->name ?? '' }}" class="mt-1 block w-full rounded border-gray-300 min-h-[44px] px-3" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" name="customer_phone" class="mt-1 block w-full rounded border-gray-300 min-h-[44px] px-3" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea name="shipping_address" rows="3" class="mt-1 block w-full rounded border-gray-300 p-3" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" name="shipping_province" class="mt-1 block w-full rounded border-gray-300 min-h-[44px] px-3" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kota</label>
                                <input type="text" name="shipping_city" class="mt-1 block w-full rounded border-gray-300 min-h-[44px] px-3" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="mt-1 block w-full rounded border-gray-300 min-h-[44px] px-3" required>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">2. Pilih Kurir</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="jne" checked>
                                <span class="text-sm">JNE</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="jnt" checked>
                                <span class="text-sm">J&T</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="sicepat" checked>
                                <span class="text-sm">SiCepat</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="anteraja" checked>
                                <span class="text-sm">Anteraja</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="ninja" checked>
                                <span class="text-sm">Ninja</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="tiki" checked>
                                <span class="text-sm">TIKI</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="pos" checked>
                                <span class="text-sm">POS</span>
                            </label>
                            <label class="flex items-center gap-2 min-h-[44px] rounded border border-gray-200 px-3">
                                <input type="checkbox" class="courier-option" value="lion" checked>
                                <span class="text-sm">Lion</span>
                            </label>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="btn-check-rates" class="min-h-[44px] px-5 bg-indigo-600 text-white rounded hover:bg-indigo-700">Cek Ongkir</button>
                        </div>

                        <div id="courier-list" class="mt-4 space-y-3"></div>
                    </div>

                    <input type="hidden" name="couriers" id="couriers" value="jne,jnt,sicepat,anteraja,ninja,tiki,pos,lion">

                    <input type="hidden" name="courier_name" id="courier_name">
                    <input type="hidden" name="courier_service" id="courier_service">
                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                    <input type="hidden" name="payment_method" value="midtrans">

                    <button type="submit" id="btn-pay" class="w-full min-h-[50px] bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-lg mt-4">
                        Bayar Sekarang
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-2">Pembayaran menggunakan Midtrans Snap (Sandbox).</p>
                </form>
            </div>

            <!-- Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                    <ul class="space-y-3 mb-4">
                        @foreach($cartItems as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item->quantity_kg }}x {{ $item->inventory->fruit_type ?? 'Produk' }}</span>
                                <span class="font-medium">Rp {{ number_format(($item->quantity_kg * ($item->inventory->price_per_kg ?? 0)), 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-gray-300 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-semibold" id="summary-shipping">Rp 0</span>
                        </div>
                    </div>
                    <div class="border-t border-gray-300 pt-4 flex justify-between">
                        <span class="font-bold text-gray-800">Grand Total</span>
                        <span class="font-bold text-green-700" id="summary-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        const form = document.getElementById('checkout-form');
        const courierList = document.getElementById('courier-list');
        const btnCheckRates = document.getElementById('btn-check-rates');
        const shippingCostInput = document.getElementById('shipping_cost');
        const courierNameInput = document.getElementById('courier_name');
        const courierServiceInput = document.getElementById('courier_service');
        const summaryShipping = document.getElementById('summary-shipping');
        const summaryTotal = document.getElementById('summary-total');

        const subtotalAmount = {{ (int) round($total, 0) }};

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function updateSummary() {
            const shipping = parseInt(shippingCostInput.value || '0', 10);
            const grandTotal = subtotalAmount + shipping;
            summaryShipping.textContent = `Rp ${formatRupiah(shipping)}`;
            summaryTotal.textContent = `Rp ${formatRupiah(grandTotal)}`;
        }

        function renderCouriers(rates) {
            courierList.innerHTML = '';

            if (!rates.length) {
                courierList.innerHTML = '<p class="text-sm text-gray-500">Ongkir tidak ditemukan. Coba kurir lain.</p>';
                return;
            }

            rates.forEach((rate) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-full text-left border rounded p-4 hover:border-green-500 hover:bg-green-50 transition';
                button.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">${rate.courier_name} - ${rate.courier_service_name}</p>
                            <p class="text-xs text-gray-500">ETD: ${rate.shipment_duration_range || '-'} hari</p>
                        </div>
                        <div class="font-bold text-green-700">Rp ${formatRupiah(rate.price)}</div>
                    </div>
                `;

                button.addEventListener('click', () => {
                    courierNameInput.value = rate.courier_code || rate.courier_name;
                    courierServiceInput.value = rate.courier_service_code || rate.courier_service_name;
                    shippingCostInput.value = rate.price;
                    updateSummary();

                    [...courierList.children].forEach((child) => {
                        child.classList.remove('border-green-600', 'bg-green-50');
                    });
                    button.classList.add('border-green-600', 'bg-green-50');
                });

                courierList.appendChild(button);
            });
        }

        btnCheckRates.addEventListener('click', async () => {
            const postalCode = document.getElementById('shipping_postal_code').value.trim();
            if (!postalCode) {
                alert('Isi kode pos terlebih dahulu.');
                return;
            }

            const courierOptions = Array.from(document.querySelectorAll('.courier-option:checked'))
                .map((input) => input.value);
            const couriers = courierOptions.length ? courierOptions.join(',') : 'jne,jnt,sicepat,anteraja,ninja,tiki,pos,lion';
            document.getElementById('couriers').value = couriers;

            btnCheckRates.disabled = true;
            btnCheckRates.textContent = 'Memuat...';

            try {
                const response = await fetch('{{ route('checkout.shipping-rates') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        shipping_postal_code: postalCode,
                        couriers,
                    }),
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal mengambil ongkir.');
                }
                const rates = data?.pricing || data?.prices || data?.data?.pricing || data?.data?.prices || [];
                renderCouriers(rates);
            } catch (error) {
                alert(error.message || 'Gagal mengambil ongkir. Coba lagi.');
            } finally {
                btnCheckRates.disabled = false;
                btnCheckRates.textContent = 'Cek Ongkir';
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!courierNameInput.value || !courierServiceInput.value) {
                alert('Pilih kurir terlebih dahulu.');
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Checkout gagal.');
                }

                snap.pay(data.snap_token, {
                    onSuccess: () => {
                        window.location.href = `{{ url('/track') }}/${data.order_id}`;
                    },
                    onPending: () => {
                        window.location.href = `{{ url('/track') }}/${data.order_id}`;
                    },
                    onError: () => {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                });
            } catch (error) {
                alert(error.message);
            }
        });
    </script>
</x-app-layout>