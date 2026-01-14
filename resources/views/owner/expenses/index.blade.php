@extends('layouts.owner')

@section('title', 'Pengeluaran Operasional')
@section('header', 'Pengeluaran Operasional')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Riwayat Pengeluaran</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Pantau biaya operasional non-inventaris laundry Anda</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <button onclick="document.getElementById('createExpenseModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Pengeluaran
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Keterangan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Jumlah (IDR)
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-700">{{ $expense->date->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 bg-slate-50 text-slate-600 border border-slate-100 rounded-lg text-[12px] font-black uppercase tracking-tighter">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-sm font-bold text-slate-600 truncate max-w-xs">
                                {{ $expense->description ?? '-' }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-black text-slate-800">
                                    {{ number_format($expense->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <form action="{{ route('owner.expenses.destroy', $expense->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus pengeluaran ini?');">
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
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-money-bill-wave text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Pengeluaran</h4>
                                    <p class="text-slate-600 text-sm mt-1">Semua catatan operasional akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createExpenseModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createExpenseModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('owner.expenses.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-receipt text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Catat Pengeluaran</h3>
                            <p class="text-sm text-slate-600 font-medium">Rekam biaya operasional laundry Anda</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Kategori
                                    Pengeluaran</label>
                                <select name="category" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                    <option value="electricity">Listrik</option>
                                    <option value="water">Air</option>
                                    <option value="rent">Sewa</option>
                                    <option value="maintenance">Pemeliharaan</option>
                                    <option value="supplies">Perlengkapan (Non-Inventaris)</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Jumlah
                                    (IDR)</label>
                                <input type="number" name="amount" required step="0.01"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Tanggal</label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Keterangan</label>
                                <textarea name="description" rows="3"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Biaya</button>
                        <button type="button"
                            onclick="document.getElementById('createExpenseModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection