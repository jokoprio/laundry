@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('header', 'Pengaturan Sistem')

@section('content')
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center shadow-sm animate-fade-in">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $items)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-8">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">{{ ucfirst($group) }}
                            </h2>
                            <p class="text-sm text-slate-500">Konfigurasi dasar untuk modul ini</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach($items as $setting)
                            <div class="group">
                                <label for="{{ $setting->key }}"
                                    class="block text-sm font-bold text-slate-700 mb-2 group-hover:text-blue-600 transition-colors">
                                    {{ $setting->label ?? $setting->key }}
                                </label>

                                @if($setting->type == 'boolean')
                                    <div class="flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="{{ $setting->key }}" value="1" {{ $setting->value ? 'checked' : '' }} class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                        </label>
                                    </div>
                                @elseif($setting->type == 'decimal' || $setting->type == 'integer')
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-bold">IDR</span>
                                        </div>
                                        <input type="number" step="{{ $setting->type == 'decimal' ? '0.01' : '1' }}"
                                            name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}"
                                            class="w-full pl-14 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                    </div>
                                @else
                                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                                @endif

                                @if($setting->description)
                                    <p class="mt-2 text-xs text-slate-500 italic">{{ $setting->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit"
                    class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-extrabold shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }
    </style>
@endsection