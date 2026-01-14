@extends('layouts.owner')

@section('title', 'Lacak Pembayaran')
@section('header', 'Lacak Pembayaran Supplier')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Pembayaran Hutang & Cicilan</h3>
                <p class="text-xs text-slate-600 mt-1">Kelola sisa pembayaran untuk pengadaan bahan baku</p>
            </div>
            <div class="flex space-x-3">
                <div class="px-6 py-3 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Total Hutang</p>
                    <p class="text-lg font-black text-red-700">Rp
                        {{ number_format($purchases->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Tanggal & Supplier
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Item
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Total Tagihan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Sisa Bayar
                        </th>
                        <th scope="col"
                            class="px-6 py-5 text-center text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Jatuh Tempo
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-center text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-700">{{ $purchase->created_at->format('d M Y') }}</div>
                                <div class="flex items-center mt-1">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mr-2 shadow-sm shadow-blue-500/50"></div>
                                    <span
                                        class="text-[12px] font-extrabold text-slate-800 uppercase tracking-tighter">{{ $purchase->supplier->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-[11px] font-bold text-slate-500">
                                    {{ $purchase->items->count() }} Jenis Item
                                </div>
                                <div class="text-[10px] text-slate-400 italic">
                                    PO: {{ substr($purchase->id, 0, 8) }}
                                </div>
                            </td>
                            <td class="px-6 py-6 font-bold text-slate-800 text-sm">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-6 font-black text-red-600 text-sm">
                                Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}
                                <div class="text-[10px] text-emerald-600">Terbayar:
                                    {{ number_format($purchase->paid_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($purchase->due_date)
                                    <div
                                        class="text-[11px] font-black {{ $purchase->due_date->isPast() ? 'text-red-500' : 'text-slate-600' }} uppercase">
                                        {{ $purchase->due_date->format('d M Y') }}
                                    </div>
                                    @if($purchase->due_date->isPast())
                                        <span
                                            class="inline-flex px-1.5 py-0.5 rounded bg-red-100 text-red-600 text-[9px] font-black uppercase">Terlewat</span>
                                    @endif
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center space-x-2">
                                <button
                                    onclick="openPaymentModal('{{ $purchase->id }}', '{{ $purchase->supplier->name }}', {{ $purchase->remaining_amount }})"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-hand-holding-usd mr-2"></i> Bayar
                                </button>
                                <a href="{{ route('owner.purchase-payments.show', $purchase) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 text-[11px] font-black uppercase tracking-tighter rounded-xl border border-blue-100 active:scale-95 transition-all">
                                    <i class="fas fa-history mr-2"></i> Riwayat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-check-double text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Semua Lunas!</h4>
                                    <p class="text-slate-600 text-sm mt-1">Tidak ada hutang atau cicilan yang perlu dibayar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-600 shadow-lg shadow-emerald-500/10">
                                <i class="fas fa-money-check-alt text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800">Catat Pembayaran</h3>
                            <p class="text-slate-500 text-sm mt-1">Supplier: <span id="modalSupplierName"
                                    class="font-bold text-slate-700"></span></p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Jumlah
                                    Bayar</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="amount" id="modalAmount" required step="1"
                                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                    <div class="mt-2 flex justify-between px-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Sisa Tagihan:</span>
                                        <span id="modalRemainingText"
                                            class="text-[10px] font-black text-red-500 uppercase"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Tanggal
                                    Pembayaran</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-slate-400"></i>
                                    </div>
                                    <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Catatan
                                    (Opsional)</label>
                                <textarea name="notes" rows="2"
                                    class="block w-full px-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                                    placeholder="Contoh: Transfer Bank BCA"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/50 p-8 flex space-x-4">
                        <button type="button" onclick="closePaymentModal()"
                            class="flex-1 px-6 py-4 bg-white border border-slate-200 text-slate-600 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-slate-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-emerald-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 active:scale-95 transition-all">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPaymentModal(purchaseId, supplierName, remaining) {
            document.getElementById('modalSupplierName').innerText = supplierName;
            document.getElementById('modalRemainingText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(remaining);
            document.getElementById('modalAmount').value = remaining;
            document.getElementById('modalAmount').max = remaining;
            document.getElementById('paymentForm').action = "{{ url('owner/purchase-payments') }}/" + purchaseId;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
@endsection