@extends('layouts.owner')

@section('title', 'Riwayat Gaji')
@section('header', 'Riwayat Gaji')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Catatan Gaji Karyawan</h3>
<<<<<<< HEAD
                <p class="text-xs text-slate-600 mt-1">Monitor riwayat dan status pembayaran upah staf</p>
=======
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Monitor riwayat dan status pembayaran upah staf</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
            </div>
            <a href="{{ route('owner.payroll.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Buat Gaji Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Karyawan & Jabatan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Periode Kerja
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Total Gaji
                        </th>
                        <th scope="col"
                            class="px-6 py-5 text-center text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Status
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-800">{{ $payroll->employee->name }}</div>
                                        <div class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                            {{ $payroll->employee->position }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ $payroll->period_start->format('d M') }} - {{ $payroll->period_end->format('d M Y') }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-black text-slate-800">
                                IDR {{ number_format($payroll->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-[12px] font-black uppercase tracking-tighter border {{ $payroll->status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                    {{ $payroll->status === 'paid' ? 'Paid' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('owner.payroll.show', $payroll->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-slate-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-eye mr-2"></i> Slip
                                </a>
                                @if($payroll->status === 'draft')
                                    <form action="{{ route('owner.payroll.update', $payroll->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Tandai sudah dibayar?');">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-check-circle mr-2"></i> Bayar
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.payroll.destroy', $payroll->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus catatan?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-file-invoice-dollar text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Data Gaji Nihil</h4>
                                    <p class="text-slate-600 text-sm mt-1">Riwayat pembayaran gaji staf akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection