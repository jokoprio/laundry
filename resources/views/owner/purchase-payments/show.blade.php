@extends('layouts.owner')

@section('title', 'Riwayat Pembayaran')
@section('header', 'Detail Pembayaran Supplier')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Purchase Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                        <i class="fas fa-file-invoice-dollar text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800">Detail Tagihan</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">PO: {{ substr($purchase->id, 0, 8) }}</p>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Supplier</p>
                        <p class="text-sm font-black text-slate-800">{{ $purchase->supplier->name }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Pembelian</p>
                        <p class="text-sm font-black text-slate-800">{{ $purchase->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Total Tagihan</p>
                        <p class="text-lg font-black text-blue-800">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Sisa Tagihan</p>
                        <p class="text-lg font-black text-red-800">Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <a href="{{ route('owner.purchase-payments.index') }}" 
                    class="w-full mt-6 block text-center px-6 py-4 bg-slate-100 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-all">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Payment History -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Riwayat Pembayaran</h3>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Daftar cicilan yang telah dibayarkan</p>
                    </div>
                    <div class="px-4 py-2 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $purchase->payments->count() }} Kali Bayar
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Tanggal Bayar</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Jumlah</th>
                                <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($purchase->payments as $payment)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-slate-700">{{ $payment->payment_date->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400 italic">ID: {{ substr($payment->id, 0, 8) }}</div>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <span class="text-sm font-black text-emerald-600 font-mono">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-[11px] font-bold text-slate-600 italic">
                                            {{ $payment->notes ?? 'Tanpa catatan' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic font-bold">
                                        Belum ada riwayat pembayaran untuk tagihan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($purchase->payments->isNotEmpty())
                            <tfoot>
                                <tr class="bg-slate-50/50 font-black">
                                    <td class="px-8 py-6 text-slate-500 uppercase text-[11px]">Total Terbayar</td>
                                    <td class="px-6 py-6 text-right text-emerald-600 text-sm">Rp {{ number_format($purchase->paid_amount, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
