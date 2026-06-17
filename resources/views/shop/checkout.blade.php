<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Formulir Checkout & Pengiriman - SetiaBuah" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="max-w-3xl mx-auto mb-12">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-gray-200 -z-10"></div>
                <div class="absolute left-0 right-1/2 top-1/2 -translate-y-1/2 h-0.5 bg-brand-500 -z-10 transition-all duration-300"></div>

                <div class="flex flex-col items-center gap-1 bg-gradient-to-br from-green-50/40 via-white to-emerald-50/20 px-2">
                    <div class="h-9 w-9 rounded-full bg-brand-600 border-4 border-brand-100 flex items-center justify-center text-white text-xs font-black shadow-md shadow-brand-500/10">
                        ✓
                    </div>
                    <span class="text-[10px] font-extrabold text-brand-600 uppercase tracking-wider">Keranjang</span>
                </div>

                <div class="flex flex-col items-center gap-1 bg-gradient-to-br from-green-50/40 via-white to-emerald-50/20 px-2">
                    <div class="h-9 w-9 rounded-full bg-brand-500 border-4 border-brand-100 flex items-center justify-center text-white text-xs font-black shadow-md shadow-brand-500/20 animate-pulse">
                        2
                    </div>
                    <span class="text-[10px] font-extrabold text-brand-600 uppercase tracking-wider">Pengiriman</span>
                </div>

                <div class="flex flex-col items-center gap-1 bg-gradient-to-br from-green-50/40 via-white to-emerald-50/20 px-2">
                    <div class="h-9 w-9 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-500 text-xs font-black">
                        3
                    </div>
                    <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse lg:flex-row gap-8 items-start">
            
            <div class="flex-grow w-full lg:max-w-[620px] xl:max-w-none">
                <div class="bg-white border border-gray-150 rounded-3xl shadow-premium p-6 sm:p-8">
                    
                    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="space-y-5">
                            <div class="flex items-center gap-2.5 pb-2 border-b border-gray-100">
                                <span class="h-6 w-6 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs font-black">1</span>
                                <h2 class="font-heading font-extrabold text-gray-800 text-base">Informasi Penerima Paket</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                                    <input type="text" name="customer_name" value="{{ auth('buyer')->user()->name ?? '' }}" placeholder="Contoh: Budi Santoso" class="mt-1 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nomor Handphone (HP)</label>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth('buyer')->user()->phone ?? '') }}" placeholder="Contoh: 08123456789" class="mt-1 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition" required>
                                </div>
                            </div>

                            <div class="space-y-1" x-data="{ isReadonly: {{ (auth('buyer')->check() && auth('buyer')->user()->address) ? 'true' : 'false' }} }">
                                <div class="flex justify-between items-center">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Alamat Pengiriman Lengkap</label>
                                    @if(auth('buyer')->check() && auth('buyer')->user()->address)
                                        <button type="button" @click="isReadonly = false; $refs.addressInput.focus(); $refs.addressInput.select()" x-show="isReadonly" class="text-[10px] text-brand-600 hover:text-brand-800 font-bold underline cursor-pointer">
                                            + Tambah Alamat Lain
                                        </button>
                                    @endif
                                </div>
                                <textarea x-ref="addressInput" name="shipping_address" rows="3" :readonly="isReadonly" :class="isReadonly ? 'bg-gray-100 cursor-not-allowed text-gray-600' : 'bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500'" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, dan kecamatan..." class="mt-1 block w-full rounded-xl border border-gray-200 p-3.5 text-sm transition" required>{{ auth('buyer')->user()->address ?? '' }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Provinsi</label>
                                    <input type="text" name="shipping_province" value="{{ old('shipping_province', auth('buyer')->user()?->province ?? '') }}" placeholder="Contoh: Jawa Barat" class="mt-1 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kota / Kabupaten</label>
                                    <input type="text" name="shipping_city" value="{{ old('shipping_city', auth('buyer')->user()?->city ?? '') }}" placeholder="Contoh: Bogor" class="mt-1 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kode Pos</label>
                                    <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code', auth('buyer')->user()?->postal_code ?? '') }}" placeholder="Contoh: 16115" class="mt-1 block w-full rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 py-2.5 px-3.5 text-sm transition font-bold text-center" required>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2.5 pb-2 border-b border-gray-100">
                                <span class="h-6 w-6 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs font-black">2</span>
                                <h2 class="font-heading font-extrabold text-gray-800 text-base">Pilihan Jasa Ekspedisi (Kurir)</h2>
                            </div>
                            
                            <div class="pt-1">
                                <button type="button" id="btn-check-rates" class="py-2.5 inline-flex items-center justify-center px-6 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-bold text-sm shadow shadow-brand-500/10 active:scale-98 transition flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    Cek Tarif Ongkir
                                </button>
                                <p class="text-[10px] text-gray-400 font-medium mt-2">Pastikan kode pos sudah diisi, lalu klik tombol di atas untuk melihat semua tarif kurir.</p>
                            </div>

                            <div id="courier-list" class="mt-4 space-y-3.5"></div>
                        </div>

                        <input type="hidden" name="couriers" id="couriers" value="jne,jnt,sicepat,anteraja,ninja,tiki,pos,lion">
                        <input type="hidden" name="courier_name" id="courier_name">
                        <input type="hidden" name="courier_service" id="courier_service">
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                        <input type="hidden" name="payment_method" value="midtrans">

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" id="btn-pay" class="w-full py-3 bg-gradient-to-tr from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white rounded-xl font-extrabold text-sm sm:text-base shadow-md shadow-brand-500/10 active:scale-98 transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Selesaikan & Bayar
                            </button>
                            <p class="text-[10px] text-center text-gray-400 mt-2.5 font-medium">
                                Pembayaran diproses secara aman menggunakan **Midtrans Snap Secure Gateway** (Sandbox).
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="w-full lg:w-80 lg:sticky lg:top-28">
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 space-y-6">
                    <h3 class="font-heading font-extrabold text-gray-800 text-base pb-3 border-b border-gray-200/50">Tinjau Pesanan</h3>
                    
                    <ul class="space-y-4">
                        @foreach($cartItems as $item)
                            <li class="flex justify-between items-start gap-4 text-xs">
                                <div class="space-y-1">
                                    <span class="block font-bold text-gray-700 line-clamp-1">{{ $item->inventory->fruit_type ?? 'Produk' }}</span>
                                    <span class="block text-gray-400 font-extrabold">({{ $item->quantity_kg }} Kg x Rp {{ number_format(optional($item->inventory)->price_per_kg ?? 0, 0, ',', '.') }})</span>
                                </div>
                                <span class="font-black text-gray-800 shrink-0">
                                    Rp {{ number_format(($item->quantity_kg * (optional($item->inventory)->price_per_kg ?? 0)), 0, ',', '.') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="border-t border-gray-200/80 pt-4 space-y-3 text-xs font-semibold text-gray-500">
                        <div class="flex justify-between">
                            <span>Subtotal Buah</span>
                            <span class="text-gray-700 font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="text-gray-700 font-bold" id="summary-shipping">Rp 0</span>
                        </div>
                    </div>

                    <div class="border-t border-brand-200/60 pt-4 flex flex-col gap-1">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Total Keseluruhan</span>
                        <span class="font-heading font-black text-xl sm:text-2xl text-brand-700 mt-1" id="summary-total">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="h-px bg-gray-200 my-2"></div>
                    <div class="flex items-center gap-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <div class="text-[9px] leading-tight font-medium">
                            <p class="font-bold text-gray-500">Secure Payment</p>
                            <p>Pembayaran terenkripsi aman.</p>
                        </div>
                    </div>
                </div>
            </aside>

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
                courierList.innerHTML = '<div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-xs font-bold text-amber-700 text-center">Ongkos kirim tidak ditemukan untuk kode pos ini. Silakan coba kombinasi kurir lain.</div>';
                return;
            }

            rates.forEach((rate) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-full text-left border border-gray-150 rounded-xl p-4 hover:border-brand-500 hover:bg-brand-50/20 active:scale-99 transition flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3';
                button.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center font-black text-[10px] text-gray-500 uppercase">${rate.courier_code || 'EXP'}</div>
                        <div>
                            <p class="font-heading font-extrabold text-sm text-gray-800">${rate.courier_name} - ${rate.courier_service_name}</p>
                            <p class="text-[10px] font-bold text-gray-400">Estimasi Durasi: ${rate.shipment_duration_range || '-'} Hari</p>
                        </div>
                    </div>
                    <div class="font-heading font-black text-brand-700 text-base text-right sm:shrink-0">Rp ${formatRupiah(rate.price)}</div>
                `;

                button.addEventListener('click', () => {
                    courierNameInput.value = rate.courier_code || rate.courier_name;
                    courierServiceInput.value = rate.courier_service_code || rate.courier_service_name;
                    shippingCostInput.value = rate.price;
                    updateSummary();

                    [...courierList.children].forEach((child) => {
                        child.classList.remove('border-brand-600', 'bg-brand-50/30', 'ring-2', 'ring-brand-500/20');
                    });
                    button.classList.add('border-brand-600', 'bg-brand-50/30', 'ring-2', 'ring-brand-500/20');
                });

                courierList.appendChild(button);
            });
        }

        btnCheckRates.addEventListener('click', async () => {
            const postalCode = document.getElementById('shipping_postal_code').value.trim();
            if (!postalCode) {
                alert('Silakan isi kode pos pengiriman terlebih dahulu.');
                return;
            }

            const couriers = document.getElementById('couriers').value;

            btnCheckRates.disabled = true;
            btnCheckRates.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Menghubungkan Biteship...
            `;

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
                alert(error.message || 'Gagal terhubung dengan layanan logistik Biteship. Pastikan internet Anda aktif.');
            } finally {
                btnCheckRates.disabled = false;
                btnCheckRates.textContent = 'Cek Tarif Ongkir';
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!courierNameInput.value || !courierServiceInput.value) {
                alert('Harap pilih salah satu tarif kurir yang tersedia terlebih dahulu.');
                return;
            }

            const formData = new FormData(form);
            const btnPay = document.getElementById('btn-pay');
            
            btnPay.disabled = true;
            btnPay.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Menghubungkan Midtrans Snap...
            `;

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
                        alert('Pembayaran dibatalkan atau terjadi kegagalan. Silakan coba kembali.');
                        btnPay.disabled = false;
                        btnPay.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Selesaikan & Bayar
                        `;
                    },
                });
            } catch (error) {
                alert(error.message);
                btnPay.disabled = false;
                btnPay.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    Selesaikan & Bayar
                `;
            }
        });
    </script>
</x-app-layout>