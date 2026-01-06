@extends('layouts.owner')

@section('title', 'Mutasi Stok')
@section('header', 'Mutasi & Perpindahan Stok')

@section('content')
    <div class="space-y-8">
        <!-- Header/Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Riwayat Mutasi</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Pantau perpindahan barang antar cabang Anda</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <button onclick="document.getElementById('createMutationModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-exchange-alt mr-2"></i> Buat Mutasi Baru
            </button>
        </div>

        <!-- Mutation Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Waktu</th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Item</th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Dari</th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Ke</th>
                            <th
                                class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest text-center">
                                Jumlah</th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($mutations as $mutation)
                            <tr class="hover:bg-slate-50/30 transition-all">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-700">{{ $mutation->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">
                                        {{ $mutation->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                            <i class="fas fa-flask text-xs"></i>
                                        </div>
                                        <span
                                            class="text-sm font-extrabold text-slate-800">{{ $mutation->inventoryItem->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-slate-500">
                                    {{ $mutation->fromBranch->name ?? 'Pusat (External)' }}
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-blue-600">
                                    {{ $mutation->toBranch->name }}
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-black rounded-full border border-slate-200">
                                        {{ number_format($mutation->quantity, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-xs text-slate-500 italic max-w-xs truncate">
                                    {{ $mutation->note ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="max-w-xs mx-auto text-center">
                                        <div
                                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                            <i class="fas fa-exchange-alt text-4xl"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-800">Tidak Ada Riwayat</h4>
                                        <p class="text-slate-500 text-sm mt-2">Data mutasi barang antar cabang akan muncul di
                                            sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($mutations->hasPages())
                <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $mutations->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Simple Create Modal -->
    <div id="createMutationModal" class="hidden fixed z-[100] inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all">
                <form action="{{ route('owner.stock-mutations.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-exchange-alt text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Kirim Barang</h3>
                            <p class="text-sm text-slate-500 font-medium">Pindahkan stok dari satu lokasi ke lokasi lain</p>
                        </div>

                        <div class="space-y-5 text-left">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Barang</label>
                                <select name="inventory_item_id" required
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                    <option value="">Pilih Member...</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} (Stok: {{ $item->stock }}
                                            {{ $item->unit }}) - {{ $item->branch->name ?? 'Pusat' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Dari
                                        Lokasi</label>
                                    <select name="from_branch_id"
                                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                        <option value="">Eksternal / Pusat</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Ke
                                        Cabang</label>
                                    <select name="to_branch_id" required
                                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                        <option value="">Pilih Cabang Tujuan...</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Jumlah</label>
                                <input type="number" step="0.01" name="quantity" required placeholder="0.00"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Catatan</label>
                                <textarea name="note" rows="2" placeholder="Alasan perpindahan..."
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-tighter shadow-xl shadow-blue-500/31 hover:bg-blue-700 transition-all active:scale-95">
                            Konfirmasi Kirim
                        </button>
                        <button type="button"
                            onclick="this.parentElement.parentElement.parentElement.parentElement.classList.add('hidden')"
                            class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-sm uppercase tracking-tighter hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection