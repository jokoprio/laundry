@extends('layouts.owner')

@section('title', 'Edit Pembelian')
@section('header', 'Edit Data Pembelian')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Form Input -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cart-plus text-blue-600 mr-2"></i> Tambah Item Belanja
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang/Bahan</label>
                        <select id="item_select" placeholder="Cari barang..." autocomplete="off">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-name="{{ $item->name }}" data-unit="{{ $item->unit }}"
                                    data-cost="{{ $item->avg_cost }}">
                                    {{ $item->name }} ({{ $item->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <div class="relative">
                                <input type="number" id="qty_input" step="0.01" value="1"
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <span id="unit_label" class="absolute right-3 top-2 text-gray-400 text-sm"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli / Unit</label>
                            <input type="number" id="cost_input" step="1" value="0"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <button onclick="addToCart()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-md transition-all active:scale-95">
                        Tambahkan ke Daftar
                    </button>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-start">
                <i class="fas fa-exclamation-triangle text-amber-500 mt-1 mr-3"></i>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Mengubah pembelian akan menyesuaikan stok barang. Pastikan data yang diinput sudah benar.
                </p>
            </div>
        </div>

        <!-- Right: Cart & Summary -->
        <div class="lg:col-span-2">
            <form action="{{ route('owner.purchases.update', $purchase) }}" method="POST" id="purchaseForm">
                @csrf
                @method('PUT')
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px] flex flex-col">
                    <!-- Header -->
                    <div
                        class="p-6 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-receipt text-blue-600 mr-2"></i> Daftar Belanja
                        </h3>
                        <div class="w-full md:w-64">
                            <select name="supplier_id" id="supplier_select" required placeholder="Pilih Supplier...">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="p-6 bg-blue-50 border-b border-blue-100">
                        <h4 class="text-sm font-bold text-slate-700 mb-3">Metode Pembayaran</h4>
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                :class="paymentMethod === 'cash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white'">
                                <input type="radio" name="payment_method" value="cash" class="sr-only" 
                                    {{ $purchase->payment_method == 'cash' ? 'checked' : '' }}
                                    @click="paymentMethod = 'cash'">
                                <div class="text-center">
                                    <i class="fas fa-money-bill-wave text-green-500 text-xl mb-1"></i>
                                    <div class="text-xs font-bold">Lunas</div>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                :class="paymentMethod === 'debt' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white'">
                                <input type="radio" name="payment_method" value="debt" class="sr-only"
                                    {{ $purchase->payment_method == 'debt' ? 'checked' : '' }}
                                    @click="paymentMethod = 'debt'">
                                <div class="text-center">
                                    <i class="fas fa-hand-holding-usd text-red-500 text-xl mb-1"></i>
                                    <div class="text-xs font-bold">Hutang</div>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-blue-400"
                                :class="paymentMethod === 'installment' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white'">
                                <input type="radio" name="payment_method" value="installment" class="sr-only"
                                    {{ $purchase->payment_method == 'installment' ? 'checked' : '' }}
                                    @click="paymentMethod = 'installment'">
                                <div class="text-center">
                                    <i class="fas fa-calendar-alt text-blue-500 text-xl mb-1"></i>
                                    <div class="text-xs font-bold">Cicilan</div>
                                </div>
                            </label>
                        </div>
                        <div x-show="paymentMethod !== 'cash'" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Dibayar Sekarang</label>
                                <input type="number" name="paid_amount" step="1" 
                                    value="{{ $purchase->paid_amount }}"
                                    class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-base font-black text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm"
                                    :required="paymentMethod !== 'cash'">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Jatuh Tempo</label>
                                <input type="date" name="due_date" 
                                    value="{{ $purchase->due_date ? $purchase->due_date->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-base font-black text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="flex-grow overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Harga/Unit
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Subtotal</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase"></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody" class="divide-y divide-gray-100 bg-white">
                                <tr id="emptyRow">
                                    <td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">
                                        Belum ada item yang ditambahkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                            <div class="flex items-center space-x-4">
                                <div class="text-sm text-gray-500">Total Item: <span id="totalItems"
                                        class="font-bold text-gray-800">0</span></div>
                                <div class="h-4 w-px bg-gray-300"></div>
                                <div class="text-sm text-gray-500">Total Tagihan:</div>
                            </div>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-sm font-bold text-blue-600">Rp</span>
                                <span id="grandTotal" class="text-4xl font-black text-blue-600">0</span>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('owner.purchases.index') }}"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-black py-4 rounded-xl shadow-lg transition-all text-center text-lg uppercase tracking-widest active:scale-95">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn" disabled
                                class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-200 disabled:text-gray-400 text-white font-black py-4 rounded-xl shadow-lg transition-all text-lg uppercase tracking-widest active:scale-95">
                                Update Pembelian
                            </button>
                        </div>
                    </div>
                </div>
                <div id="hiddenInputs"></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        let cart = [];
        let paymentMethod = '{{ $purchase->payment_method }}';

        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#supplier_select', { create: false });

            window.itemTs = new TomSelect('#item_select', {
                create: false,
                onChange: function (val) {
                    const opt = this.options[val];
                    if (opt) {
                        document.getElementById('unit_label').innerText = opt.unit;
                        document.getElementById('cost_input').value = Math.round(opt.cost);
                    }
                }
            });

            // Load existing items
            @foreach($purchase->items as $item)
                cart.push({
                    id: '{{ $item->inventory_item_id }}',
                    name: '{{ $item->inventoryItem->name }}',
                    unit: '{{ $item->inventoryItem->unit }}',
                    qty: {{ $item->qty }},
                    cost: {{ $item->cost }}
                });
            @endforeach

            renderCart();
        });

        function addToCart() {
            const itemId = document.getElementById('item_select').value;
            if (!itemId) return alert('Pilih barang dulu');

            const opt = window.itemTs.options[itemId];
            const qty = parseFloat(document.getElementById('qty_input').value);
            const cost = parseFloat(document.getElementById('cost_input').value);

            if (qty <= 0) return alert('Jumlah harus lebih dari 0');

            const existing = cart.findIndex(i => i.id === itemId);
            if (existing > -1) {
                cart[existing].qty += qty;
                cart[existing].cost = cost;
            } else {
                cart.push({
                    id: itemId,
                    name: opt.name,
                    unit: opt.unit,
                    qty: qty,
                    cost: cost
                });
            }

            renderCart();
            window.itemTs.setValue('');
            document.getElementById('qty_input').value = 1;
            document.getElementById('cost_input').value = 0;
        }

        function remove(idx) {
            cart.splice(idx, 1);
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById('cartBody');
            const hidden = document.getElementById('hiddenInputs');
            const submitBtn = document.getElementById('submitBtn');

            body.innerHTML = '';
            hidden.innerHTML = '';

            if (cart.length === 0) {
                body.innerHTML = `<tr id="emptyRow"><td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">Belum ada item ditambahkan.</td></tr>`;
                submitBtn.disabled = true;
                document.getElementById('grandTotal').innerText = '0';
                document.getElementById('totalItems').innerText = '0';
                return;
            }

            submitBtn.disabled = false;
            let grandTotal = 0;

            cart.forEach((item, i) => {
                const sub = item.qty * item.cost;
                grandTotal += sub;

                body.insertAdjacentHTML('beforeend', `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">${item.name}</div>
                            <div class="text-xs text-gray-400">ID: ${item.id.substring(0, 8)}</div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(item.cost)}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-medium">${item.qty} ${item.unit}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(sub)}</td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" onclick="remove(${i})" class="text-red-400 hover:text-red-600 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);

                hidden.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${i}][inventory_item_id]" value="${item.id}">
                    <input type="hidden" name="items[${i}][qty]" value="${item.qty}">
                    <input type="hidden" name="items[${i}][cost]" value="${item.cost}">
                `);
            });

            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
            document.getElementById('totalItems').innerText = cart.length;
        }
    </script>
@endsection
