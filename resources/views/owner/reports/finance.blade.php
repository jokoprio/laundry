@extends('layouts.owner')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')

@section('content')
    <div class="space-y-8">
        <!-- PROFIT & LOSS SECTION -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800">Laporan Laba Rugi</h3>
                    <p class="text-xs text-slate-600 mt-1">Ringkasan pendapatan dan pengeluaran operasional</p>
                </div>
                <form action="{{ route('owner.reports.finance') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2">
                        <input type="date" name="start_date" value="{{ $start_date }}"
                            class="bg-transparent text-xs font-black text-slate-600 outline-none border-none p-0 focus:ring-0">
                        <span class="mx-3 text-slate-300 font-bold">-</span>
                        <input type="date" name="end_date" value="{{ $end_date }}"
                            class="bg-transparent text-xs font-black text-slate-600 outline-none border-none p-0 focus:ring-0">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                        <i class="fas fa-filter mr-2"></i> Filter Data
                    </button>
                </form>
            </div>

            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-50">
                            <!-- Revenue -->
                            <tr class="group">
                                <td class="py-6 pr-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-cash-register"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-800">Total Penjualan</p>
                                            <p class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                                Pendapatan Bruto</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="text-lg font-black text-slate-800">
                                        IDR {{ number_format($revenue, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>

                            <!-- COGS -->
                            <tr class="group">
                                <td class="py-6 pr-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-800">Harga Pokok Penjualan (HPP)</p>
                                            <p class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">Biaya
                                                Bahan Baku</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="text-lg font-black text-red-500">
                                        ( IDR {{ number_format($cogs, 0, ',', '.') }} )
                                    </div>
                                </td>
                            </tr>

                            <!-- Gross Profit -->
                            <tr class="bg-slate-50/50 rounded-2xl">
                                <td class="py-6 pl-8 pr-4">
                                    <p class="text-[11px] font-black text-slate-600 uppercase tracking-[0.2em] mb-1">
                                        Profitabilas</p>
                                    <p class="text-base font-black text-slate-800 tracking-tight">Laba Kotor</p>
                                </td>
                                <td class="py-6 text-right pr-8">
                                    <div class="text-xl font-black text-emerald-600">
                                        IDR {{ number_format($revenue - $cogs, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" class="py-4"></td>
                            </tr>

                            <!-- Salary -->
                            <tr class="group">
                                <td class="py-6 pr-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-users-gear"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-800">Biaya Gaji Karyawan</p>
                                            <p class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                                Payroll & Upah</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="text-lg font-black text-red-500">
                                        ( IDR {{ number_format($salary_expenses, 0, ',', '.') }} )
                                    </div>
                                </td>
                            </tr>

                            <!-- Operational -->
                            <tr class="group">
                                <td class="py-6 pr-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-800">Pengeluaran Operasional</p>
                                            <p class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                                Listrik, Air, Sewa, Dll</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="text-lg font-black text-red-500">
                                        ( IDR {{ number_format($operational_expenses, 0, ',', '.') }} )
                                    </div>
                                </td>
                            </tr>

                            <!-- Net Profit -->
                            <tr class="bg-blue-600 rounded-[2rem] shadow-xl shadow-blue-500/20">
                                <td class="py-8 pl-10 pr-4">
                                    <p class="text-[11px] font-black text-blue-200 uppercase tracking-[0.2em] mb-1">Bottom
                                        Line</p>
                                    <p class="text-2xl font-black text-white tracking-tighter italic">Laba Bersih Akhir</p>
                                </td>
                                <td class="py-8 text-right pr-10">
                                    <div class="text-3xl font-black text-white tracking-tighter">
                                        {{ $net_profit < 0 ? '-' : '' }} IDR
                                        {{ number_format(abs($net_profit), 0, ',', '.') }}
                                    </div>
                                    <p
                                        class="text-[12px] font-bold {{ $net_profit >= 0 ? 'text-emerald-300' : 'text-red-300' }} uppercase mt-1">
                                        {{ $net_profit >= 0 ? 'Status: Surplus untung' : 'Status: Defisit rugi' }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Piutang -->
            <div
                class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 group hover:border-emerald-200 transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/5 transition-transform group-hover:scale-110">
                        <i class="fas fa-hand-holding-dollar text-2xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[12px] font-black uppercase tracking-widest border border-emerald-100">Aset
                        Lancar</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Aset (Piutang)</h3>
                <p class="text-xs text-slate-600 mt-1 mb-6 font-medium">Tagihan layanan yang belum dibayar pelanggan.</p>
                <div class="text-3xl font-black text-emerald-600 tracking-tighter">
                    IDR {{ number_format($receivables, 0, ',', '.') }}
                </div>
            </div>

            <!-- Hutang -->
            <div
                class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 group hover:border-red-200 transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/5 transition-transform group-hover:scale-110">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-[12px] font-black uppercase tracking-widest border border-red-100">Kewajiban</span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Kewajiban (Hutang)</h3>
                <p class="text-xs text-slate-600 mt-1 mb-6 font-medium">Pembelian bahan baku yang belum dilunasi.</p>
                <div class="text-3xl font-black text-red-500 tracking-tighter">
                    IDR {{ number_format($payables, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
@endsection