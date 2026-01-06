@extends('layouts.owner')

@section('title', 'Manajemen Staff')
@section('header', 'Manajemen Staff (Karyawan)')

@section('content')
<<<<<<< HEAD
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600">Kelola akun staff dan karyawan outlet Anda</p>
            </div>
            <a href="{{ route('owner.users.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>Tambah Staff
            </a>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Nama & Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Akses
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Terdaftar
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                        {{ $user->role_display_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">
                                        {{ count($user->permissions ?? []) }} permissions
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('owner.users.edit', $user) }}"
                                            class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-semibold">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('owner.users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus staff ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-semibold">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-user-tag text-3xl text-slate-600"></i>
                                        </div>
                                        <p class="text-slate-500 font-semibold">Belum ada data staff</p>
                                        <p class="text-sm text-slate-600 mt-1">Klik tombol "Tambah Staff" untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
=======
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Manajemen Staff</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Kelola akun staff dan karyawan outlet Anda</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('owner.users.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Staff
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Nama & Email
                        </th>
                        <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Role
                        </th>
                        <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Akses
                        </th>
                        <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Status
                        </th>
                        <th class="px-6 py-5 text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Terdaftar
                        </th>
                        <th class="px-8 py-5 text-right text-[12px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all mr-4">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-[12px] text-slate-500 font-bold tracking-tight">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[12px] font-black uppercase tracking-tighter">
                                    {{ $user->role_display_name }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span
                                    class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter px-2 py-1 bg-slate-100 rounded">
                                    {{ count($user->permissions ?? []) }} izin
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @if($user->is_active)
                                    <span
                                        class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[12px] font-black uppercase tracking-tighter">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-[12px] font-black uppercase tracking-tighter">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-[12px] font-bold text-slate-500 uppercase">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('owner.users.edit', $user) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all"
                                    title="Edit Staff">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </a>
                                <form action="{{ route('owner.users.destroy', $user) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus staff ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all"
                                        title="Hapus Staff">
                                        <i class="fas fa-trash-alt mr-2"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-user-tag text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Staff</h4>
                                    <p class="text-slate-600 text-sm mt-1">Daftar staff atau karyawan Anda akan muncul di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $users->links() }}
            </div>
        @endif
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
    </div>
@endsection