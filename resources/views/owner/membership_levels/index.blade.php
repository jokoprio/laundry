@extends('layouts.owner')

@section('title', 'Level Membership')
@section('header', 'Level Membership')

@section('content')
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Member</h3>
                <p class="text-sm text-slate-600 mt-1">Konfigurasi diskon otomatis dan ambang poin untuk loyalitas
                    pelanggan.</p>
            </div>
            <button onclick="openModal('addLevelModal')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Level
            </button>
        </div>

        <!-- Levels Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($levels as $level)
                <div
                    class="group relative bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-400 to-indigo-500 group-hover:h-2 transition-all">
                    </div>

                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/5 group-hover:scale-110 transition-transform">
                                <i class="fas fa-crown text-2xl"></i>
                            </div>
                            <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editLevel({{ json_encode($level) }})"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('owner.membership-levels.destroy', $level) }}" method="POST"
                                    onsubmit="return confirm('Hapus level member ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h4 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">{{ $level->name }}</h4>

                        <div class="flex flex-wrap gap-2 mb-6">
                            <span
                                class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[11px] font-black uppercase tracking-tighter">
                                <i class="fas fa-percent mr-2"></i> {{ $level->discount_percent }}% OFF
                            </span>
                            <span
                                class="inline-flex items-center px-4 py-1.5 bg-slate-50 text-slate-500 rounded-full text-[11px] font-black uppercase tracking-tighter border border-slate-100">
                                <i class="fas fa-star mr-2 text-yellow-400"></i> {{ number_format($level->min_points) }} PTS
                            </span>
                        </div>

                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8 min-h-[44px]">
                            {{ $level->description ?: 'Beri hak eksklusif untuk tingkat member ini.' }}
                        </p>

                        <div class="pt-6 border-t border-slate-50 flex justify-between items-center">
                            <div>
                                <p class="text-[12px] font-black text-slate-600 uppercase tracking-widest mb-1">Total Member</p>
                                <p class="text-xl font-black text-slate-800">{{ $level->customers->count() }} <span
                                        class="text-xs text-slate-600 font-bold uppercase tracking-tighter ml-1">Orang</span>
                                </p>
                            </div>
                            <div class="px-4 py-2 bg-slate-50 rounded-xl group-hover:bg-blue-600 transition-colors">
                                <i class="fas fa-chevron-right text-slate-300 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($levels->isEmpty())
                <div
                    class="col-span-full bg-slate-50/50 p-20 rounded-[3rem] border-4 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-24 h-24 bg-white rounded-[2rem] shadow-xl flex items-center justify-center mb-6 text-slate-200">
                        <i class="fas fa-id-card text-5xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-800 mb-2">Belum Ada Tingkatan Member</h4>
                    <p class="text-slate-600 max-w-xs mb-8">Buat kategori member seperti Silver, Gold, atau Platinum untuk
                        strategi marketing Anda.</p>
                    <button onclick="openModal('addLevelModal')"
                        class="inline-flex py-4 px-10 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all uppercase tracking-widest text-xs">Mulai
                        Sekarang</button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addLevelModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                        <i class="fas fa-crown text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Level Member Baru</h3>
                    <p class="text-sm text-slate-600 font-medium">Buat kategori benefit untuk pelanggan</p>
                </div>

                <form action="{{ route('owner.membership-levels.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Level</label>
                            <input type="text" name="name" required placeholder="Contoh: Platinum Class"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Diskon
                                    (%)</label>
                                <input type="number" name="discount_percent" required min="0" max="100" placeholder="0"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Ambang
                                    Poin</label>
                                <input type="number" name="min_points" required min="0" placeholder="0"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Deskripsi
                                & Benefit</label>
                            <textarea name="description" rows="3"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                                placeholder="Jelaskan keuntungan kategori ini..."></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Level</button>
                        <button type="button" onclick="closeModal('addLevelModal')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editLevelModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Level Member</h3>
                    <p class="text-sm text-slate-600 font-medium">Perbarui konfigurasi tingkat loyalitas</p>
                </div>

                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                Level</label>
                            <input type="text" name="name" id="edit_name" required
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Diskon
                                    (%)</label>
                                <input type="number" name="discount_percent" id="edit_discount" required min="0" max="100"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Ambang
                                    Poin</label>
                                <input type="number" name="min_points" id="edit_points" required min="0"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Deskripsi
                                & Benefit</label>
                            <textarea name="description" id="edit_description" rows="3"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row-reverse gap-3 mt-8">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Perubahan</button>
                        <button type="button" onclick="closeModal('editLevelModal')"
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
        function editLevel(level) {
            document.getElementById('editForm').action = `/owner/membership-levels/${level.id}`;
            document.getElementById('edit_name').value = level.name;
            document.getElementById('edit_discount').value = level.discount_percent;
            document.getElementById('edit_points').value = level.min_points;
            document.getElementById('edit_description').value = level.description || '';
            openModal('editLevelModal');
        }
    </script>
@endsection