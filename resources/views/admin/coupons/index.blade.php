@extends('layouts.admin')

@section('title', 'Kupon')
@section('header', 'Manajemen Kupon')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Kupon Aktif</h3>
                <p class="text-xs text-slate-500 mt-1">Kelola kode diskon promosi sistem</p>
            </div>
            <button onclick="document.getElementById('createCouponModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-admin-primary hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Buat Kupon
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Kode Kupon
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Nilai Diskon
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Penggunaan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Kadaluarsa
                        </th>
                        <th scope="col" class="px-8 py-5 text-right text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="font-mono text-sm text-admin-primary font-black bg-blue-50 px-3 py-1.5 rounded-xl border border-blue-100">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-extrabold text-slate-800">
                                @if($coupon->type == 'percent')
                                    <div class="flex items-center text-emerald-600">
                                        <i class="fas fa-percentage mr-2 text-xs"></i>
                                        DISKON {{ $coupon->value }}%
                                    </div>
                                @else
                                    <div class="flex items-center text-blue-600">
                                        <i class="fas fa-tag mr-2 text-xs"></i>
                                        DISKON IDR {{ number_format($coupon->value) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <div class="w-24 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        @php 
                                            $usage = $coupon->max_uses ? ($coupon->times_used / $coupon->max_uses) * 100 : 0;
                                        @endphp
                                        <div class="bg-admin-primary h-full transition-all duration-1000" style="width: {{ $usage }}%"></div>
                                    </div>
                                    <span class="text-[12px] font-bold text-slate-600 uppercase">
                                        {{ $coupon->times_used }} / {{ $coupon->max_uses ?? '∞' }} TERPAKAI
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-500">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Tidak Terbatas' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus kupon ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all">
                                        <i class="fas fa-trash-alt mr-2"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-ticket-alt text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Kupon</h4>
                                    <p class="text-slate-500 text-sm mt-1">Kupon diskon yang Anda buat akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Coupon Modal -->
    <div id="createCouponModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createCouponModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-admin-primary shadow-lg shadow-blue-500/10">
                                <i class="fas fa-ticket-alt text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Buat Kupon Baru</h3>
                            <p class="text-sm text-slate-600 font-medium">Tentukan parameter diskon untuk promosi</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Kode Kupon</label>
                                <input type="text" name="code" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none uppercase"
                                    placeholder="CONTOH: PROMO2025">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Tipe Diskon</label>
                                    <select name="type"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none">
                                        <option value="fixed">Jumlah Tetap (IDR)</option>
                                        <option value="percent">Persentase (%)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nilai</label>
                                    <input type="number" name="value" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                        placeholder="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Maksimal Penggunaan</label>
                                <input type="number" name="max_uses"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                    placeholder="Kosongkan untuk tanpa batas">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-admin-primary text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Simpan Kupon
                        </button>
                        <button type="button" onclick="document.getElementById('createCouponModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection