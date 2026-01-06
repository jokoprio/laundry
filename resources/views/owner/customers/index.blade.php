@extends('layouts.owner')

@section('title', 'Pelanggan')
@section('header', 'Manajemen Pelanggan')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Data Pelanggan</h3>
                <p class="text-xs text-slate-600 mt-1">Kelola database dan saldo member laundry Anda</p>
            </div>
            <button onclick="openModal('addModal')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Pelanggan
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Nama & Alamat
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Level Member
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Saldo
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-800">{{ $customer->name }}</div>
                                        <div
                                            class="text-[12px] text-slate-600 font-bold truncate max-w-[150px] uppercase tracking-tighter">
                                            {{ $customer->address ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ $customer->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @if($customer->membershipLevel)
                                    <span
                                        class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[12px] font-black uppercase tracking-tighter">
                                        {{ $customer->membershipLevel->name }} ({{ $customer->membershipLevel->discount_percent }}%)
                                    </span>
                                @else
                                    <span class="text-slate-600 text-[12px] font-bold uppercase tracking-tighter italic">Umum</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div
                                    class="text-sm font-black {{ $customer->balance > 0 ? 'text-emerald-600' : 'text-slate-600' }}">
                                    Rp {{ number_format($customer->balance, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <button onclick="openTopupModal({{ json_encode($customer) }})"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all"
                                    title="Top Up Saldo">
                                    <i class="fas fa-wallet mr-2"></i> Top Up
                                </button>
                                <button onclick="editCustomer({{ json_encode($customer) }})"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all"
                                    title="Edit Pelanggan">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <form action="{{ route('owner.customers.destroy', $customer->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Hapus pelanggan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all"
                                        title="Hapus Pelanggan">
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
                                        <i class="fas fa-users text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Pelanggan Kosong</h4>
                                    <p class="text-slate-600 text-sm mt-1">Daftar pelanggan laundry Anda akan muncul di sini.
                                    </p>
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
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Pelanggan</h3>
                    <p class="text-sm text-slate-600 font-medium">Daftarkan pelanggan atau member baru</p>
                </div>

                <form action="{{ route('owner.customers.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Lengkap</label>
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
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Level
                                Member</label>
                            <select name="membership_level_id"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                <option value="">-- Umum (Bukan Member) --</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }} ({{ $level->discount_percent }}% Off)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Alamat</label>
                            <textarea name="address" rows="2"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Pelanggan</button>
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
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Pelanggan</h3>
                    <p class="text-sm text-slate-600 font-medium">Perbarui profil member laundry</p>
                </div>

                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Lengkap</label>
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
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Level
                                Member</label>
                            <select name="membership_level_id" id="edit_level"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                <option value="">-- Umum (Bukan Member) --</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }} ({{ $level->discount_percent }}% Off)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Alamat</label>
                            <textarea name="address" id="edit_address" rows="2"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Perbarui
                            Profil</button>
                        <button type="button" onclick="closeModal('editModal')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Topup Modal -->
    <div id="topupModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-600 shadow-lg shadow-emerald-500/10">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Top Up Saldo</h3>
                    <p class="text-sm text-slate-600 font-medium tracking-tight">Member: <span id="topup_customer_name"
                            class="text-emerald-500 font-black"></span></p>
                </div>

                <form id="topupForm" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Jumlah
                                Top Up (Rp)</label>
                            <input type="number" name="amount" required min="1000" step="500" placeholder="Rp 0"
                                class="block w-full px-5 py-6 bg-slate-50 border border-slate-200 rounded-3xl text-2xl font-black text-slate-800 placeholder-slate-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none text-center">
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Catatan
                                Transaksi</label>
                            <input type="text" name="description" placeholder="E.g. Pembayaran Tunai"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none">
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 mt-8">
                        <button type="submit"
                            class="inline-flex justify-center items-center rounded-2xl px-6 py-5 bg-emerald-600 text-sm font-black text-white hover:bg-emerald-700 shadow-xl shadow-emerald-600/20 active:scale-[0.98] transition-all uppercase tracking-widest">Tambah
                            Saldo Sekarang</button>
                        <button type="button" onclick="closeModal('topupModal')"
                            class="inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batalkan</button>
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

        function editCustomer(customer) {
            document.getElementById('edit_name').value = customer.name;
            document.getElementById('edit_phone').value = customer.phone || '';
            document.getElementById('edit_address').value = customer.address || '';
            document.getElementById('edit_level').value = customer.membership_level_id || '';
            let url = "{{ route('owner.customers.update', ':id') }}";
            document.getElementById('editForm').action = url.replace(':id', customer.id);
            openModal('editModal');
        }

        function openTopupModal(customer) {
            document.getElementById('topup_customer_name').innerText = customer.name;
            let url = "{{ route('owner.customers.topup', ':id') }}";
            document.getElementById('topupForm').action = url.replace(':id', customer.id);
            openModal('topupModal');
        }
    </script>
@endsection