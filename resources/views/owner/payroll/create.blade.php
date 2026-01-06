@extends('layouts.owner')

@section('title', 'Buat Gaji')
@section('header', 'Buat Gaji')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow overflow-hidden p-6">
        <form action="{{ route('owner.payroll.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Employee Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pilih Karyawan</label>
                    <select name="employee_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->name }} - {{ ucfirst($employee->salary_type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Awal Periode</label>
                        <input type="date" name="period_start" required value="{{ date('Y-m-01') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Akhir Periode</label>
                        <input type="date" name="period_end" required value="{{ date('Y-m-t') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                </div>

                <!-- Optional Manual Overrides -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Penyesuaian</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bonus</label>
                            <input type="number" name="bonus" value="0" min="0"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Potongan</label>
                            <input type="number" name="deductions" value="0" min="0"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Hari Kerja (Hanya untuk Harian)</label>
                        <input type="number" name="days_worked" placeholder="Default: 26"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk menggunakan default (26 hari) atau hitung
                            otomatis.</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('owner.payroll.index') }}"
                        class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded mr-2">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Hitung & Buat
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection