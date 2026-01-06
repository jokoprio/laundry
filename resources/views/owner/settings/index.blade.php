@extends('layouts.owner')

@section('title', 'Pengaturan Bisnis')
@section('header', 'Profil & Pengaturan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
            <h2 class="text-xl font-extrabold text-slate-800 mb-6">Identitas Bisnis</h2>

            <form action="{{ route('owner.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Bisnis</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}"
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon /
                                WhatsApp</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-600"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}"
                                    class="w-full pl-10 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">
                            </div>
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('address', $tenant->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Logo Bisnis</label>
                            <div class="flex items-start space-x-6">
                                <div
                                    class="w-24 h-24 rounded-2xl bg-slate-100 flex items-center justify-center border-2 border-dashed border-slate-300 overflow-hidden shrink-0">
                                    @if($tenant->logo)
                                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image text-3xl text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" class="block w-full text-sm text-slate-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-bold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100
                                        " accept="image/*">
                                    <p class="text-[12px] text-slate-600 mt-2">Format: JPG, PNG. Max: 2MB. Disarankan rasio
                                        1:1.</p>
                                </div>
                            </div>
                            @error('logo')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="receipt_footer" class="block text-sm font-bold text-slate-700 mb-2">Catatan Struk
                                (Footer)</label>
                            <textarea name="receipt_footer" id="receipt_footer" rows="3"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda. Barang yang tidak diambil > 30 hari..."
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('receipt_footer', $tenant->receipt_footer) }}</textarea>
                            <p class="text-[12px] text-slate-600 mt-1">Teks ini akan muncul di bagian bawah struk belanja.
                            </p>
                            @error('receipt_footer')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-indigo-500/30 flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection