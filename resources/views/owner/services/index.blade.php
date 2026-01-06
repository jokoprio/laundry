@extends('layouts.owner')

@section('title', 'Services')
@section('header', 'Layanan & Resep')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Daftar Layanan</h3>
                <p class="text-xs text-slate-600 mt-1">Kelola jenis layanan dan resep bahan laundry</p>
            </div>
            <button onclick="document.getElementById('createServiceModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Layanan
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Nama Layanan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Harga per Unit
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Resep (Bahan Baku)
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($services as $service)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div class="text-sm font-extrabold text-slate-800">{{ $service->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-black text-slate-700">IDR
                                    {{ number_format($service->price, 0, ',', '.') }}</div>
                                <div class="text-[12px] font-bold text-slate-600 uppercase tracking-tight">PER
                                    {{ $service->unit }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($service->materials as $mat)
                                        <span
                                            class="inline-flex items-center px-2 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[12px] font-bold text-slate-500">
                                            {{ $mat->inventoryItem->name }} ({{ (float) $mat->quantity }}
                                            {{ $mat->inventoryItem->unit }})
                                        </span>
                                    @empty
                                        <span class="text-[11px] italic text-slate-300">Belum ada bahan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all"
                                    onclick='openEditServiceModal(@json($service))'>
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <form action="{{ route('owner.services.destroy', $service->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus layanan ini?');">
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
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-concierge-bell text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Layanan Kosong</h4>
                                    <p class="text-slate-600 text-sm mt-1">Layanan yang Anda buat akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createServiceModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createServiceModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                <form action="{{ route('owner.services.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-concierge-bell text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Layanan</h3>
                            <p class="text-sm text-slate-600 font-medium">Buat jenis paket layanan laundry baru</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-5">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                        Layanan</label>
                                    <input type="text" name="name" placeholder="e.g., Cuci Lipat" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Satuan</label>
                                        <select name="unit"
                                            class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                            <option value="kg">kg</option>
                                            <option value="pc">pc</option>
                                            <option value="meter">meter</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Harga
                                            (IDR)</label>
                                        <input type="number" name="price" required
                                            class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-0 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-[12px] font-black text-slate-600 uppercase tracking-widest">Resep /
                                        Bahan Baku</h4>
                                    <button type="button" onclick="addMaterialRow('create-materials-container')"
                                        class="text-[12px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-tighter bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all active:scale-95">+
                                        Bahan</button>
                                </div>
                                <div id="create-materials-container"
                                    class="space-y-3 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">
                                    <div class="flex space-x-2">
                                        <select name="materials[0][inventory_item_id]"
                                            class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                            <option value="">Pilih...</option>
                                            @foreach($inventoryItems as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                                            @endforeach
                                        </select>
                                        <input type="number" step="0.01" name="materials[0][quantity]" placeholder="Qty"
                                            class="w-20 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="text-red-400 hover:text-red-600 px-1">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Simpan Layanan
                        </button>
                        <button type="button"
                            onclick="document.getElementById('createServiceModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editServiceModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('editServiceModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                <form id="editServiceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-edit text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Layanan</h3>
                            <p class="text-sm text-slate-600 font-medium">Perbarui parameter layanan dan resep</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-5">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                        Layanan</label>
                                    <input type="text" name="name" id="edit_name" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Satuan</label>
                                        <select name="unit" id="edit_unit"
                                            class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                            <option value="kg">kg</option>
                                            <option value="pc">pc</option>
                                            <option value="meter">meter</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Harga
                                            (IDR)</label>
                                        <input type="number" name="price" id="edit_price" required
                                            class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-[12px] font-black text-slate-600 uppercase tracking-widest">Resep /
                                        Bahan Baku</h4>
                                    <button type="button" onclick="addMaterialRow('edit-materials-container')"
                                        class="text-[12px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-tighter bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all active:scale-95">+
                                        Bahan</button>
                                </div>
                                <div id="edit-materials-container"
                                    class="space-y-3 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">
                                    <!-- Populated via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Perbarui Layanan
                        </button>
                        <button type="button" onclick="document.getElementById('editServiceModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const inventoryItems = @json($inventoryItems);

        function addMaterialRow(containerId) {
            const container = document.getElementById(containerId);
            let options = '<option value="">Pilih Bahan...</option>';
            inventoryItems.forEach(item => {
                options += `<option value="${item.id}">${item.name} (${item.unit})</option>`;
            });

            const row = `
                    <div class="flex space-x-2">
                        <select name="materials[${Date.now()}][inventory_item_id]" class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                            ${options}
                        </select>
                        <input type="number" step="0.01" name="materials[${Date.now()}][quantity]" placeholder="Qty" class="w-20 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-1">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                `;
            container.insertAdjacentHTML('beforeend', row);
        }

        function openEditServiceModal(service) {
            document.getElementById('editServiceModal').classList.remove('hidden');
            document.getElementById('edit_name').value = service.name;
            document.getElementById('edit_unit').value = service.unit;
            document.getElementById('edit_price').value = Math.floor(service.price);
            document.getElementById('editServiceForm').action = "{{ url('owner/services') }}/" + service.id;

            const container = document.getElementById('edit-materials-container');
            container.innerHTML = '';

            if (service.materials && service.materials.length > 0) {
                service.materials.forEach((mat, idx) => {
                    let options = '<option value="">Pilih Bahan...</option>';
                    inventoryItems.forEach(item => {
                        const selected = item.id === mat.inventory_item_id ? 'selected' : '';
                        options += `<option value="${item.id}" ${selected}>${item.name} (${item.unit})</option>`;
                    });

                    const qty = parseFloat(mat.quantity);

                    const row = `
                            <div class="flex space-x-2">
                                <select name="materials[${idx}][inventory_item_id]" class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                    ${options}
                                </select>
                                <input type="number" step="0.01" name="materials[${idx}][quantity]" value="${qty}" placeholder="Qty" class="w-20 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-1">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        `;
                    container.insertAdjacentHTML('beforeend', row);
                });
            } else {
                addMaterialRow('edit-materials-container');
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
@endsection