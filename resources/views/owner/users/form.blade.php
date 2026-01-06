@extends('layouts.owner')

@section('title', isset($user) ? 'Edit Staff' : 'Tambah Staff')
@section('header', isset($user) ? 'Edit Staff' : 'Tambah Staff')

@section('content')
<div class="max-w-4xl">
    <form action="{{ isset($user) ? route('owner.users.update', $user) : route('owner.users.store') }}" 
          method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-id-card-alt text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-white">
                            {{ isset($user) ? 'Edit Data Staff' : 'Registrasi Staff Baru' }}
                        </h3>
                        <p class="text-indigo-100 text-sm mt-1">Lengkapi informasi staff outlet</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $user->name ?? '') }}"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                               placeholder="Nama staff"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', $user->email ?? '') }}"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-300 @enderror"
                               placeholder="email@example.com"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                            Password {{ isset($user) ? '' : '*' }}
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('password') border-red-300 @enderror"
                               placeholder="{{ isset($user) ? 'Kosongkan jika tidak ubah' : 'Min 8 karakter' }}"
                               {{ isset($user) ? '' : 'required' }}>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">
                            Konfirmasi Password {{ isset($user) ? '' : '*' }}
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Ulangi password"
                               {{ isset($user) ? '' : 'required' }}>
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-bold text-slate-700 mb-2">
                            Posisi / Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="role" 
                                id="role"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('role') border-red-300 @enderror"
                                required>
                            @foreach($availableRoles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" {{ old('role', $user->role ?? '') === $roleKey ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Status Akun
                        </label>
                        <div class="flex items-center space-x-3 h-12">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       class="sr-only peer"
                                       {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-semibold text-slate-700">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="border-t border-slate-200 pt-6">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>Hak Akses (Permissions)
                    </h4>
                    <p class="text-sm text-slate-600 mb-4">Atur fitur apa saja yang dapat diakses oleh staff ini</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($availablePermissions as $category => $permissions)
                            <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                                <h5 class="font-bold text-indigo-700 mb-3 text-sm border-b border-indigo-200 pb-2">{{ $category }}</h5>
                                <div class="space-y-2">
                                    @foreach($permissions as $key => $label)
                                        <label class="flex items-center space-x-2 cursor-pointer group">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $key }}"
                                                   class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 focus:ring-2"
                                                   {{ in_array($key, old('permissions', $user->permissions ?? [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-slate-700 group-hover:text-slate-900">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('owner.users.index') }}" 
                   class="px-6 py-3 text-slate-600 hover:text-slate-800 font-semibold transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>{{ isset($user) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
