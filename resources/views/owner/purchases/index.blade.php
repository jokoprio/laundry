@extends('layouts.owner')

@section('title', 'Riwayat Belanja')
@section('header', 'Riwayat Belanja Bahan')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Riwayat Belanja Bahan</h3>
                <p class="text-xs text-slate-600 mt-1">Pantau transaksi pengadaan bahan baku laundry</p>
            </div>
            <a href="{{ route('owner.purchases.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Belanja Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Tanggal & Waktu
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Mitra Supplier
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Item yang Dibeli
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Total Belanja (IDR)
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-center text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-700">{{ $purchase->created_at->format('d M Y') }}</div>
                                <div class="text-[12px] font-bold text-slate-600 uppercase tracking-tighter">
                                    {{ $purchase->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mr-2 shadow-sm shadow-blue-500/50"></div>
                                    <span class="text-sm font-extrabold text-slate-800">{{ $purchase->supplier->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-col space-y-1">
                                    @foreach($purchase->items as $item)
                                        <div class="text-[11px] font-bold text-slate-500 flex items-center">
                                            <i class="fas fa-check-circle text-emerald-400 mr-2 text-[12px]"></i>
                                            <span class="text-slate-800 mr-1">{{ $item->qty }}
                                                {{ $item->inventoryItem->unit }}</span>
                                            <span
                                                class="text-slate-600 truncate max-w-[120px]">{{ $item->inventoryItem->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-black text-slate-800">
                                    {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[12px] font-black uppercase tracking-widest shadow-sm shadow-emerald-500/5">
                                    <i class="fas fa-box-open mr-2"></i> Diterima
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-truck-loading text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Belanja</h4>
                                    <p class="text-slate-600 text-sm mt-1">Riwayat pengadaan bahan baku akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection