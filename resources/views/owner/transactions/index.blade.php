@extends('layouts.owner')

@section('title', 'Transaksi')
@section('header', 'Riwayat Transaksi')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Daftar Transaksi</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Pantau riwayat pesanan dan status pembayaran</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('owner.transactions.create') }}"
                class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Transaksi Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col"
                            class="w-[140px] px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Tanggal
                        </th>
                        <th scope="col"
                            class="w-[190px] px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Pelanggan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Layanan
                        </th>
                        <th scope="col"
                            class="w-[170px] px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Total Bayar
                        </th>
                        <th scope="col"
                            class="w-[150px] px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em] text-center">
                            Pembayaran
                        </th>
                        <th scope="col"
                            class="w-[150px] px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em] text-center">
                            Status
                        </th>
                        <th scope="col"
                            class="w-[260px] px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $transaction)
                                <tr class="hover:bg-slate-50/30 transition-colors group align-top">
                                    <!-- Tanggal -->
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-700">
                                            {{ $transaction->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-[12px] font-bold text-slate-600 uppercase tracking-tighter">
                                            {{ $transaction->created_at->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    <!-- Pelanggan -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center mr-3 font-black text-xs shrink-0">
                                                {{ substr($transaction->customer->name ?? 'P', 0, 1) }}
                                            </div>
                                            <div class="text-sm font-extrabold text-slate-800 truncate">
                                                {{ $transaction->customer->name ?? 'Pelanggan Biasa' }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Layanan -->
                                    <td class="px-6 py-6">
                                        <div class="flex flex-col space-y-1">
                                            @foreach($transaction->items as $item)
                                                <div class="text-[11px] font-bold text-slate-600 flex items-center">
                                                    <span class="w-1 h-1 bg-slate-300 rounded-full mr-2"></span>
                                                    {{ $item->service->name }} ({{ number_format($item->qty, 0) }}
                                                    {{ $item->service->unit }})
                                                </div>
                                            @endforeach
                                            @if($transaction->items->isEmpty())
                                                <span class="text-xs italic text-slate-600">Layanan nihil</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Total Bayar -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-slate-800">
                                            IDR {{ number_format($transaction->total_price, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <!-- Pembayaran (PAID/UNPAID) -->
                                    <td class="px-6 py-6 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-black uppercase tracking-tighter
                                                                                                    {{ $transaction->payment_status === 'paid'
                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
                        : 'bg-red-50 text-red-700 border border-red-100' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full mr-2
                                                                                                        {{ $transaction->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            {{ $transaction->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </td>

                                    <!-- Status (PROSES/SELESAI) -->
                                    <td class="px-6 py-6 whitespace-nowrap text-center">
                                        @if($transaction->status === 'done')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                                Selesai
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 anim-pulse-amber"></span>
                                                Proses
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                        @if($transaction->payment_status !== 'paid')
                                            <form action="{{ route('owner.transactions.update', $transaction->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Tandai sudah dibayar? Ini akan menambahkan Poin Loyalitas ke Pelanggan.');">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="payment_status" value="paid">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                                    <i class="fas fa-check-circle mr-2"></i> Bayar
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('owner.transactions.receipt', $transaction->id) }}" target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-[11px] font-black uppercase tracking-tighter rounded-xl border border-indigo-200 shadow-sm active:scale-95 transition-all">
                                            <i class="fas fa-print mr-2"></i> Cetak
                                        </a>

                                        <form action="{{ route('owner.transactions.destroy', $transaction->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Hapus transaksi ini secara permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all">
                                                <i class="fas fa-trash-alt mr-2"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-receipt text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Transaksi</h4>
                                    <p class="text-slate-600 text-sm mt-1">Mulai buat transaksi baru untuk melihat riwayat.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @keyframes pulse-amber {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .anim-pulse-amber {
            animation: pulse-amber 2s infinite;
        }
    </style>
@endsection