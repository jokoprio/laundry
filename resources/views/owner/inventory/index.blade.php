@extends('layouts.owner')

@section('title', 'Inventory')
@section('header', 'Manajemen Inventaris')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Item Stok</h3>
<<<<<<< HEAD
                <p class="text-xs text-slate-600 mt-1">Kelola persediaan dan aset laundry Anda</p>
=======
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Kelola persediaan dan aset laundry Anda</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
            </div>
            <button onclick="document.getElementById('createItemModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Nama Item
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Stok Tersedia
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Biaya Satuan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Total Nilai
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div class="text-sm font-extrabold text-slate-800">{{ $item->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full {{ $item->stock <= 10 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                    @if($item->stock <= 10)<span
                                    class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2 inline-block"></span>@else<span
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 inline-block"></span>@endif
                                    {{ number_format($item->stock, 0, ',', '.') }} {{ $item->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ number_format($item->avg_cost, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-extrabold text-slate-800">
                                {{ number_format($item->stock * $item->avg_cost, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <button onclick='openEditModal(@json($item))'
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <form action="{{ route('owner.inventory.destroy', $item->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus item ini?');">
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
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-flask text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Stok Kosong</h4>
                                    <p class="text-slate-600 text-sm mt-1">Item yang Anda tambahkan akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createItemModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createItemModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('owner.inventory.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-flask text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Item</h3>
                            <p class="text-sm text-slate-600 font-medium">Tambah perlengkapan laundry baru</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                    Item</label>
                                <input type="text" name="name" placeholder="e.g., Deterjen Cair" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Satuan</label>
                                    <select name="unit"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                        <option value="ml">ml (Milliliters)</option>
                                        <option value="L">L (Liters)</option>
                                        <option value="kg">kg (Kilograms)</option>
                                        <option value="pcs">pcs (Pieces)</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Stok
                                        Awal</label>
                                    <input type="number" step="0.01" name="stock" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-0 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Biaya
                                    per Unit (Est.)</label>
                                <input type="number" step="0.01" name="avg_cost" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-0 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Simpan Item
                        </button>
                        <button type="button" onclick="document.getElementById('createItemModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editItemModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('editItemModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="editItemForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-edit text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Item</h3>
                            <p class="text-sm text-slate-600 font-medium">Perbarui informasi stok inventaris</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                    Item</label>
                                <input type="text" name="name" id="edit_name" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Satuan</label>
                                    <select name="unit" id="edit_unit"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                        <option value="ml">ml (Milliliters)</option>
                                        <option value="L">L (Liters)</option>
                                        <option value="kg">kg (Kilograms)</option>
                                        <option value="pcs">pcs (Pieces)</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Stok
                                        Saat Ini</label>
                                    <input type="number" step="0.01" name="stock" id="edit_stock" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Cost
                                    per Unit (Est.)</label>
                                <input type="number" step="0.01" name="avg_cost" id="edit_avg_cost" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Perbarui Item
                        </button>
                        <button type="button" onclick="document.getElementById('editItemModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(item) {
            document.getElementById('editItemModal').classList.remove('hidden');
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_unit').value = item.unit;
            document.getElementById('edit_stock').value = item.stock;
            document.getElementById('edit_avg_cost').value = item.avg_cost;
            let form = document.getElementById('editItemForm');
            form.action = "{{ url('owner/inventory') }}/" + item.id;
        }
    </script>
@endsection