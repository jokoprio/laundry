@extends('layouts.admin')

@section('title', 'Paket')
@section('header', 'Paket Berlangganan')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Semua Paket</h3>
                <p class="text-xs text-slate-500 mt-1">Kelola paket langganan untuk penyewa</p>
            </div>

            <!-- Open Modal Button -->
            <button onclick="document.getElementById('createPackageModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-admin-primary hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Paket
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Nama Paket
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Harga (IDR)
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Durasi
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Batasan
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($packages as $package)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-50 text-admin-primary rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-admin-primary group-hover:text-white transition-all mr-4">
                                        {{ substr($package->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-extrabold text-slate-800">{{ $package->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-700">IDR {{ number_format($package->price) }}</div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 text-[11px] font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $package->duration_days }} Hari
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center text-[11px] font-bold text-slate-600">
                                        <i class="fas fa-users w-4 text-slate-300"></i>
                                        <span>User: {{ $package->max_users ?? '∞' }}</span>
                                    </div>
                                    <div class="flex items-center text-[11px] font-bold text-slate-600">
                                        <i class="fas fa-mobile-alt w-4 text-slate-300"></i>
                                        <span>Device: {{ $package->max_devices ?? '∞' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('admin.packages.edit', $package->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
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
                                        <i class="fas fa-box-open text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Paket</h4>
                                    <p class="text-slate-600 text-sm mt-1">Paket langganan yang Anda buat akan muncul di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $packages->links() }}
            </div>
        @endif
    </div>

    <!-- Create Package Modal -->
    <div id="createPackageModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createPackageModal').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-admin-primary shadow-lg shadow-blue-500/10">
                                <i class="fas fa-box-open text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight" id="modal-title">Buat Paket Baru
                            </h3>
                            <p class="text-sm text-slate-600 font-medium">Tentukan parameter paket langganan</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                    Paket</label>
                                <input type="text" name="name" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                    placeholder="Contoh: Paket Premium">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Harga
                                        (IDR)</label>
                                    <input type="number" name="price" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                        placeholder="0">
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Durasi
                                        (Hari)</label>
                                    <input type="number" name="duration_days" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                        placeholder="30">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Maks
                                        Pengguna</label>
                                    <input type="number" name="max_users"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                        placeholder="∞">
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Maks
                                        Perangkat</label>
                                    <input type="number" name="max_devices"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-admin-primary transition-all outline-none"
                                        placeholder="∞">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-admin-primary text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Simpan Paket
                        </button>
                        <button type="button"
                            onclick="document.getElementById('createPackageModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection