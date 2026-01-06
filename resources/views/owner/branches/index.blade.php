@extends('layouts.owner')

@section('title', 'Cabang')
@section('header', 'Manajemen Cabang')

@section('content')
    <div class="space-y-8">
        <!-- Header/Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Cabang</span>
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-xs"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-800">{{ $branches->count() }}</h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Biaya Addon</span>
                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-xs"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($branchAddonPrice, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-center">
                <button onclick="document.getElementById('createBranchModal').classList.remove('hidden')"
                    class="w-full h-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-2xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                    <i class="fas fa-plus mr-2"></i> Tambah Cabang Baru
                </button>
            </div>
        </div>

        <!-- Active Branch Context Switcher -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-xl font-bold mb-1">Konteks Cabang Aktif</h3>
                    <p class="text-slate-400 text-sm">Pilih cabang untuk mulai mengelola data spesifik cabang tersebut.</p>
                </div>
                <form action="{{ route('owner.branches.switch') }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <select name="branch_id"
                        class="bg-slate-700 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none min-w-[200px]">
                        <option value="">Pusat (Semua Data)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ session('active_branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-white text-slate-900 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-500 hover:text-white transition-all active:scale-95">
                        Ganti
                    </button>
                </form>
            </div>
        </div>

        <!-- Branch List -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Nama Cabang
                            </th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Informasi
                            </th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Masa Aktif
                            </th>
                            <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-widest">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($branches as $branch)
                            <tr class="hover:bg-slate-50/30 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-lg mr-4 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">{{ $branch->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">
                                                ID: {{ substr($branch->id, 0, 8) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 font-medium text-slate-600 text-xs">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-map-marker-alt w-4 text-slate-300"></i>
                                        {{ $branch->address ?? '-' }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-phone w-4 text-slate-300"></i>
                                        {{ $branch->phone ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    @if($branch->expires_at)
                                        <div
                                            class="text-xs font-bold {{ $branch->isSubscriptionActive() ? 'text-slate-700' : 'text-red-500' }}">
                                            {{ $branch->expires_at->format('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            {{ $branch->expires_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Belum diatur</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6">
                                    @if($branch->isSubscriptionActive())
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-full border border-emerald-100">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase rounded-full border border-red-100">
                                            Kadaluarsa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('owner.branches.activate', $branch->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                                                Perpanjang
                                            </button>
                                        </form>
                                        <button onclick='openEditModal(@json($branch))'
                                            class="p-2 bg-slate-100 text-slate-500 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('owner.branches.destroy', $branch->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus cabang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <div
                                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                            <i class="fas fa-store text-4xl"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-800">Belum Ada Cabang</h4>
                                        <p class="text-slate-500 text-sm mt-2">Buka cabang baru untuk memperluas jangkauan
                                            bisnis Anda.</p>
                                        <button
                                            onclick="document.getElementById('createBranchModal').classList.remove('hidden')"
                                            class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase transition-all hover:bg-blue-700">
                                            Tambah Cabang Pertama
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="createBranchModal" class="hidden fixed z-[100] inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all">
                <form action="{{ route('owner.branches.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-store text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Cabang</h3>
                            <p class="text-sm text-slate-500 font-medium">Buka outlet baru di lokasi strategis</p>
                        </div>

                        <div class="space-y-5 text-left">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Nama
                                    Cabang</label>
                                <input type="text" name="name" required placeholder="e.g., SiLondry Cabang Depok"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Alamat
                                    Lengkap</label>
                                <textarea name="address" rows="3" placeholder="Jl. Raya Margonda No. 123..."
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Nomor
                                    Telepon</label>
                                <input type="text" name="phone" placeholder="0821xxxxxxx"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>

                            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 mt-2">
                                <div class="flex items-center text-blue-700 font-bold text-xs mb-1">
                                    <i class="fas fa-info-circle mr-2"></i> Info Biaya Addon
                                </div>
                                <p class="text-[10px] text-blue-600 leading-relaxed font-medium">
                                    Penambahan cabang akan dikenakan biaya sebesar <b>Rp
                                        {{ number_format($branchAddonPrice, 0, ',', '.') }}</b> per bulan.
                                    Cabang yang baru dibuat mendapatkan <b>Trial 7 Hari</b>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-tighter shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all active:scale-95">
                            Konfirmasi & Tambah
                        </button>
                        <button type="button" onclick="document.getElementById('createBranchModal').classList.add('hidden')"
                            class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-sm uppercase tracking-tighter hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editBranchModal" class="hidden fixed z-[100] inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all">
                <form id="editBranchForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-edit text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Cabang</h3>
                            <p class="text-sm text-slate-500 font-medium">Perbarui informasi operasional cabang</p>
                        </div>

                        <div class="space-y-5 text-left">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Nama
                                    Cabang</label>
                                <input type="text" name="name" id="edit_name" required
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Alamat
                                    Lengkap</label>
                                <textarea name="address" id="edit_address" rows="3"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Nomor
                                    Telepon</label>
                                <input type="text" name="phone" id="edit_phone"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Status
                                    Operasional</label>
                                <select name="status" id="edit_status"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                    <option value="active">Buka (Active)</option>
                                    <option value="inactive">Tutup Sementara (Inactive)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-tighter shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="document.getElementById('editBranchModal').classList.add('hidden')"
                            class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-sm uppercase tracking-tighter hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(branch) {
            const modal = document.getElementById('editBranchModal');
            const form = document.getElementById('editBranchForm');

            document.getElementById('edit_name').value = branch.name;
            document.getElementById('edit_address').value = branch.address;
            document.getElementById('edit_phone').value = branch.phone;
            document.getElementById('edit_status').value = branch.status;

            form.action = `/owner/branches/${branch.id}`;
            modal.classList.remove('hidden');
        }
    </script>
@endsection