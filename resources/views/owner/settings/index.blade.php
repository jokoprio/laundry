@extends('layouts.owner')

@section('title', 'Pengaturan Bisnis')
@section('header', 'Profil & Pengaturan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
<<<<<<< HEAD
            <h2 class="text-xl font-extrabold text-slate-800 mb-6">Identitas Bisnis</h2>
=======
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">Identitas Bisnis</h2>
                    <p class="text-xs text-slate-500 font-bold uppercase mt-1">
                        {{ $isBranch ? 'Cabang: ' . $entity->name : 'Pusat (External)' }}
                    </p>
                </div>
                @if($isBranch)
                    <div class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase">
                        Mode Cabang
                    </div>
                @else
                    <div class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-[10px] font-black uppercase">
                        Mode Pusat
                    </div>
                @endif
            </div>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

            <form action="{{ route('owner.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Bisnis</label>
<<<<<<< HEAD
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}"
=======
                            <input type="text" name="name" id="name" value="{{ old('name', $entity->name) }}"
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
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
<<<<<<< HEAD
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}"
=======
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $entity->phone) }}"
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
                                    class="w-full pl-10 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">
                            </div>
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="3"
<<<<<<< HEAD
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('address', $tenant->address) }}</textarea>
=======
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('address', $entity->address) }}</textarea>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
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
<<<<<<< HEAD
                                    @if($tenant->logo)
                                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo"
=======
                                    @if($entity->logo)
                                        <img src="{{ asset('storage/' . $entity->logo) }}" alt="Logo"
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image text-3xl text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" class="block w-full text-sm text-slate-500
<<<<<<< HEAD
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-bold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100
                                        " accept="image/*">
=======
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-xl file:border-0
                                                file:text-xs file:font-bold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100
                                            " accept="image/*">
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
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
<<<<<<< HEAD
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('receipt_footer', $tenant->receipt_footer) }}</textarea>
=======
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-slate-50">{{ old('receipt_footer', $entity->receipt_footer) }}</textarea>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
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