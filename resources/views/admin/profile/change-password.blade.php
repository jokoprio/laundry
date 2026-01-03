@extends('layouts.admin')

@section('title', 'Ganti Password')
@section('header', 'Ganti Password')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-key text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-white">Ubah Password</h3>
                        <p class="text-blue-100 text-sm mt-1">Perbarui password akun Anda</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.profile.update-password') }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-600"></i>
                        </div>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('current_password') border-red-300 @enderror"
                            placeholder="Masukkan password saat ini" required>
                    </div>
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label for="new_password" class="block text-sm font-bold text-slate-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-key text-slate-600"></i>
                        </div>
                        <input type="password" name="new_password" id="new_password"
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('new_password') border-red-300 @enderror"
                            placeholder="Masukkan password baru (min. 8 karakter)" required>
                    </div>
                    @error('new_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">
                        <i class="fas fa-info-circle"></i> Password minimal 8 karakter
                    </p>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-8">
                    <label for="new_password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-check-double text-slate-600"></i>
                        </div>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-3 text-slate-600 hover:text-slate-800 font-semibold transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition-all transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Simpan Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-6">
            <h4 class="font-bold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-shield-halved mr-2"></i>Tips Keamanan Password
            </h4>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mt-0.5 mr-2"></i>
                    <span>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mt-0.5 mr-2"></i>
                    <span>Jangan gunakan password yang sama dengan akun lain</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 mt-0.5 mr-2"></i>
                    <span>Ubah password secara berkala untuk keamanan maksimal</span>
                </li>
            </ul>
        </div>
    </div>
@endsection