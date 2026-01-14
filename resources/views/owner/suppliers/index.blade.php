@extends('layouts.owner')

@section('title', 'Supplier')
@section('header', 'Manajemen Supplier')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Daftar Supplier</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Kelola mitra penyedia bahan baku laundry Anda</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <button onclick="openModal('addModal')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Supplier
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Nama Supplier
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Alamat
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="text-sm font-extrabold text-slate-800">{{ $supplier->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ $supplier->phone ?: '-' }}
                            </td>
                            <td class="px-6 py-6 text-sm font-bold text-slate-600 truncate max-w-xs uppercase tracking-tighter">
                                {{ $supplier->address ?: '-' }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <button onclick="editSupplier({{ json_encode($supplier) }})"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <form action="{{ route('owner.suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus supplier ini?')">
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
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-shipping-fast text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Supplier Kosong</h4>
                                    <p class="text-slate-600 text-sm mt-1">Daftar mitra supplier laundry Anda akan muncul di
                                        sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                        <i class="fas fa-truck-loading text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Supplier</h3>
                    <p class="text-sm text-slate-600 font-medium tracking-tight">Daftarkan mitra penyedia baru</p>
                </div>

                <form action="{{ route('owner.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Supplier</label>
                            <input type="text" name="name" required
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nomor
                                Telepon</label>
                            <input type="text" name="phone"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Alamat
                                Kantor/Toko</label>
                            <textarea name="address" rows="3"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Supplier</button>
                        <button type="button" onclick="closeModal('addModal')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Supplier</h3>
                    <p class="text-sm text-slate-600 font-medium tracking-tight">Perbarui informasi kontak mitra</p>
                </div>

                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Supplier</label>
                            <input type="text" name="name" id="edit_name" required
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nomor
                                Telepon</label>
                            <input type="text" name="phone" id="edit_phone"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Alamat
                                Kantor/Toko</label>
                            <textarea name="address" id="edit_address" rows="3"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Perbarui
                            Supplier</button>
                        <button type="button" onclick="closeModal('editModal')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function editSupplier(supplier) {
            document.getElementById('editForm').action = `/owner/suppliers/${supplier.id}`;
            document.getElementById('edit_name').value = supplier.name;
            document.getElementById('edit_phone').value = supplier.phone || '';
            document.getElementById('edit_address').value = supplier.address || '';
            openModal('editModal');
        }
    </script>
@endsection