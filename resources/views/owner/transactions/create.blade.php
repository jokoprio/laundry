@extends('layouts.owner')

@section('title', 'Transaksi Baru')
@section('header', 'Transaksi Baru (Kasir)')

@section('content')
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        /* TomSelect lebih compact */
        .ts-control {
            min-height: 42px !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        .ts-dropdown,
        .ts-control {
            border-color: rgb(209 213 219) !important;
            /* gray-300 */
        }

        .ts-control input {
            font-size: 0.875rem !important;
            /* text-sm */
        }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- LEFT: Input -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Tambah Layanan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-plus-circle text-blue-600"></i>
                    <h3 class="text-sm font-extrabold text-slate-900">Tambah Layanan</h3>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 uppercase tracking-wide mb-1">
                            Pilih Layanan
                        </label>
                        <select id="service_select" placeholder="Cari layanan..." autocomplete="off">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-name="{{ $service->name }}"
                                    data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                    {{ $service->name }} (IDR {{ number_format($service->price, 0) }} /
                                    {{ $service->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-900 uppercase tracking-wide mb-1">
                            Jumlah
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="qty_input" value="1" step="0.1" min="0.1"
                                class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <span id="unit_label" class="text-xs text-slate-500 font-semibold min-w-[2.5rem]"></span>
                        </div>
                    </div>

                    <button type="button" onclick="addToCart()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-xl transition-all shadow-sm active:scale-[0.99]">
                        <i class="fas fa-cart-plus mr-2"></i> Tambah
                    </button>
                </div>
            </div>

            <!-- Customer Info -->
            <div id="customer_info_card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hidden">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Info Pelanggan</h3>
                    <span id="info_member_badge" class="text-xs font-extrabold"></span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Diskon</span>
                        <span id="info_discount" class="font-extrabold text-blue-600">0%</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-wallet text-slate-600"></i>
                            <span class="text-slate-600 text-sm font-semibold">Saldo</span>
                        </div>
                        <span id="info_balance" class="text-lg font-black text-slate-900">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Tip -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <p class="text-sm text-blue-800">
                        Jika menggunakan <b>Saldo</b>, pastikan saldo pelanggan mencukupi untuk total akhir setelah diskon.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT: Cart + Submit -->
        <div class="lg:col-span-2">
            <form action="{{ route('owner.transactions.store') }}" method="POST" id="transactionForm">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 bg-gray-50/50">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shopping-basket text-blue-600"></i>
                                <h3 class="text-sm font-extrabold text-slate-900">Daftar Transaksi</h3>
                            </div>

                            <div class="flex items-center gap-2 flex-grow max-w-md">
                                <div class="flex-grow">
                                    <select name="customer_id" id="customer_select" placeholder="Cari pelanggan..."
                                        autocomplete="off">
                                        <option value="">-- Pelanggan Biasa --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" data-balance="{{ $customer->balance }}"
                                                data-discount="{{ $customer->membershipLevel->discount_percent ?? 0 }}"
                                                data-level="{{ $customer->membershipLevel->name ?? 'Umum' }}">
                                                {{ $customer->name }} ({{ $customer->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <a href="{{ route('owner.customers.index') }}"
                                    class="flex items-center justify-center w-11 h-11 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                                    title="Tambah Pelanggan Baru">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto min-h-[120px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                        Layanan
                                    </th>
                                    <th
                                        class="px-4 py-2 text-right text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th
                                        class="px-4 py-2 text-center text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                        Qty
                                    </th>
                                    <th
                                        class="px-4 py-2 text-right text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                    <th
                                        class="px-4 py-2 text-center text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="cartTableBody" class="bg-white divide-y divide-gray-200">
                                <tr id="emptyCartMessage">
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-600">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-box-open text-3xl mb-2 text-slate-200"></i>
                                            <p class="text-sm italic">Belum ada layanan ditambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- LEFT: Scheme + DP -->
                            <div class="space-y-3">
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest">
                                    Skema Pembayaran
                                </label>

                                <div class="grid grid-cols-3 gap-2">
                                    <!-- LUNAS -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_scheme" value="lunas" checked class="hidden peer"
                                            onchange="handleSchemeChange()">
                                        <div
                                            class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 transition-all hover:brightness-95
                                                       border-emerald-200 bg-emerald-50 text-emerald-800
                                                       peer-checked:border-emerald-600 peer-checked:bg-emerald-100 peer-checked:ring-2 peer-checked:ring-emerald-200">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="text-xs font-extrabold">Lunas</span>
                                        </div>
                                    </label>

                                    <!-- DP -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_scheme" value="dp" class="hidden peer"
                                            onchange="handleSchemeChange()">
                                        <div
                                            class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 transition-all hover:brightness-95
                                                       border-amber-200 bg-amber-50 text-amber-800
                                                       peer-checked:border-amber-600 peer-checked:bg-amber-100 peer-checked:ring-2 peer-checked:ring-amber-200">
                                            <i class="fas fa-coins"></i>
                                            <span class="text-xs font-extrabold">DP</span>
                                        </div>
                                    </label>

                                    <!-- NANTI -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_scheme" value="bayar_nanti" class="hidden peer"
                                            onchange="handleSchemeChange()">
                                        <div
                                            class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 transition-all hover:brightness-95
                                                       border-rose-200 bg-rose-50 text-rose-800
                                                       peer-checked:border-rose-600 peer-checked:bg-rose-100 peer-checked:ring-2 peer-checked:ring-rose-200">
                                            <i class="fas fa-clock"></i>
                                            <span class="text-xs font-extrabold">Nanti</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- DP Amount -->
                                <div id="dp_amount_section" class="hidden">
                                    <label class="block text-xs font-semibold text-slate-900 uppercase tracking-wide mb-1">
                                        Jumlah DP (IDR)
                                    </label>
                                    <input type="number" name="dp_amount" id="dp_amount_input" min="0" step="1000"
                                        placeholder="Masukkan jumlah DP"
                                        class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                        oninput="renderCart()">
                                    <p class="text-[11px] text-slate-500 mt-1">DP harus &gt; 0 dan &lt; total</p>
                                </div>
                            </div>

                            <!-- RIGHT: Payment Method + Totals -->
                            <div class="space-y-3">
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest">
                                    Metode Pembayaran
                                </label>

                                <div class="grid grid-cols-2 gap-2">
                                    <!-- TUNAI -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="cash" checked class="hidden peer">
                                        <div
                                            class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 transition-all hover:brightness-95
                                                       border-sky-200 bg-sky-50 text-sky-800
                                                       peer-checked:border-sky-600 peer-checked:bg-sky-100 peer-checked:ring-2 peer-checked:ring-sky-200">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <span class="text-xs font-extrabold">Tunai</span>
                                        </div>
                                    </label>

                                    <!-- SALDO -->
                                    <label id="balance_payment_option" class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="balance" class="hidden peer">
                                        <div
                                            class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 transition-all hover:brightness-95
                                                       border-teal-200 bg-teal-50 text-teal-800
                                                       peer-checked:border-teal-600 peer-checked:bg-teal-100 peer-checked:ring-2 peer-checked:ring-teal-200">
                                            <i class="fas fa-wallet"></i>
                                            <span class="text-xs font-extrabold">Saldo</span>
                                        </div>
                                    </label>
                                </div>

                                <p id="insufficient_balance_msg" class="text-xs text-red-600 font-semibold hidden italic">
                                    * Saldo tidak cukup untuk pembayaran ini.
                                </p>

                                <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-3">
                                    <div class="flex justify-between items-center text-sm text-slate-600">
                                        <span class="font-semibold">Subtotal</span>
                                        <span id="subtotal_display" class="font-extrabold">IDR 0</span>
                                    </div>

                                    <div id="member_discount_row"
                                        class="flex justify-between items-center text-sm text-blue-600 hidden">
                                        <span class="font-semibold">
                                            Diskon (<span id="discount_label">0</span>%)
                                        </span>
                                        <span id="discount_amount_display" class="font-extrabold">- IDR 0</span>
                                    </div>

                                    <div class="flex justify-between items-end border-t border-gray-200 pt-3">
                                        <span class="text-sm font-extrabold text-slate-800">Total Akhir</span>
                                        <span id="grand_total_display" class="text-2xl font-black text-blue-600">IDR
                                            0</span>
                                    </div>

                                    <button type="submit" id="submitBtn" disabled
                                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-black py-3 rounded-xl shadow-sm transition-all uppercase tracking-widest active:scale-[0.99]">
                                        <i class="fas fa-check-circle mr-2"></i> Proses
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="hiddenInputs"></div>
            </form>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        let cart = [];
        let selectedCustomer = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Customer TS
            const customerTs = new TomSelect('#customer_select', {
                create: false,
                onChange: function (value) {
                    if (value) {
                        const option = this.options[value];
                        selectedCustomer = {
                            id: value,
                            balance: parseFloat(option.balance),
                            discount: parseInt(option.discount),
                            level: option.level
                        };
                        updateCustomerCard();
                    } else {
                        selectedCustomer = null;
                        document.getElementById('customer_info_card').classList.add('hidden');
                    }
                    renderCart();
                }
            });

            // Service TS
            const serviceTs = new TomSelect('#service_select', {
                create: false,
                onChange: function (value) {
                    const option = this.options[value];
                    document.getElementById('unit_label').innerText = option ? option.unit : '';
                }
            });

            window.serviceTs = serviceTs;
            window.customerTs = customerTs;
        });

        function updateCustomerCard() {
            const card = document.getElementById('customer_info_card');
            const badge = document.getElementById('info_member_badge');
            const discount = document.getElementById('info_discount');
            const balance = document.getElementById('info_balance');

            card.classList.remove('hidden');

            badge.innerText = selectedCustomer.level;
            badge.className = selectedCustomer.discount > 0 ?
                'text-xs font-extrabold text-blue-600' :
                'text-xs font-extrabold text-slate-600';

            discount.innerText = selectedCustomer.discount + '%';
            balance.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedCustomer.balance);
        }

        function addToCart() {
            const serviceId = document.getElementById('service_select').value;
            if (!serviceId) {
                alert('Silakan pilih layanan terlebih dahulu');
                return;
            }

            const serviceOption = window.serviceTs.options[serviceId];
            const qty = parseFloat(document.getElementById('qty_input').value) || 0;

            if (qty <= 0) {
                alert('Jumlah harus lebih dari 0');
                return;
            }

            const existingIndex = cart.findIndex(item => item.service_id === serviceId);
            if (existingIndex > -1) {
                cart[existingIndex].qty += qty;
            } else {
                cart.push({
                    service_id: serviceId,
                    name: serviceOption.name,
                    price: parseFloat(serviceOption.price),
                    unit: serviceOption.unit,
                    qty: qty
                });
            }

            window.serviceTs.setValue('');
            document.getElementById('qty_input').value = 1;
            document.getElementById('unit_label').innerText = '';
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const tbody = document.getElementById('cartTableBody');
            const hiddenInputs = document.getElementById('hiddenInputs');
            const submitBtn = document.getElementById('submitBtn');
            const subtotalEl = document.getElementById('subtotal_display');
            const discountRow = document.getElementById('member_discount_row');
            const discountLabel = document.getElementById('discount_label');
            const discountAmtEl = document.getElementById('discount_amount_display');
            const grandTotalEl = document.getElementById('grand_total_display');

            tbody.innerHTML = '';
            hiddenInputs.innerHTML = '';

            if (cart.length === 0) {
                tbody.innerHTML = `
                        <tr id="emptyCartMessage">
                            <td colspan="5" class="px-4 py-10 text-center text-slate-600">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-3xl mb-2 text-slate-200"></i>
                                    <p class="text-sm italic">Belum ada layanan ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                submitBtn.disabled = true;
                subtotalEl.innerText = 'IDR 0';
                discountRow.classList.add('hidden');
                grandTotalEl.innerText = 'IDR 0';
                return;
            }

            let subtotal = 0;

            cart.forEach((item, index) => {
                const itemSubtotal = item.price * item.qty;
                subtotal += itemSubtotal;

                const row = `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-bold text-slate-900">${item.name}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 text-right">
                                IDR ${new Intl.NumberFormat('id-ID').format(item.price)}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-900 text-center">
                                ${item.qty} <span class="text-slate-600 text-xs font-semibold">${item.unit}</span>
                            </td>
                            <td class="px-4 py-3 text-sm font-extrabold text-slate-900 text-right">
                                IDR ${new Intl.NumberFormat('id-ID').format(itemSubtotal)}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" onclick="removeFromCart(${index})"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                    title="Hapus">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                tbody.insertAdjacentHTML('beforeend', row);

                hiddenInputs.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="items[${index}][service_id]" value="${item.service_id}">
                        <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                    `);
            });

            // discount
            const discountPercent = selectedCustomer ? selectedCustomer.discount : 0;
            const discountAmount = (subtotal * discountPercent) / 100;
            const finalTotal = subtotal - discountAmount;

            subtotalEl.innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(subtotal);

            if (discountPercent > 0) {
                discountRow.classList.remove('hidden');
                discountLabel.innerText = discountPercent;
                discountAmtEl.innerText = '- IDR ' + new Intl.NumberFormat('id-ID').format(discountAmount);
            } else {
                discountRow.classList.add('hidden');
            }

            grandTotalEl.innerText = 'IDR ' + new Intl.NumberFormat('id-ID').format(finalTotal);

            // balance validation
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const balanceMsg = document.getElementById('insufficient_balance_msg');

            if (paymentMethod === 'balance') {
                if (!selectedCustomer || selectedCustomer.balance < finalTotal) {
                    submitBtn.disabled = true;
                    balanceMsg.classList.remove('hidden');
                } else {
                    submitBtn.disabled = false;
                    balanceMsg.classList.add('hidden');
                }
            } else {
                submitBtn.disabled = false;
                balanceMsg.classList.add('hidden');
            }
        }

        // Re-calc when payment method changes
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', renderCart);
        });

        function handleSchemeChange() {
            const scheme = document.querySelector('input[name="payment_scheme"]:checked').value;
            const dpSection = document.getElementById('dp_amount_section');
            const dpInput = document.getElementById('dp_amount_input');

            if (scheme === 'dp') {
                dpSection.classList.remove('hidden');
                dpInput.required = true;
            } else {
                dpSection.classList.add('hidden');
                dpInput.required = false;
                dpInput.value = '';
            }

            renderCart();
        }
    </script>
@endsection