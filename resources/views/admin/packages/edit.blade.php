@extends('layouts.admin')

@section('title', 'Edit Paket')
@section('header', 'Edit Paket: ' . $package->name)

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Paket</label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (IDR)</label>
                    <input type="number" name="price" value="{{ old('price', $package->price) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Durasi (Hari)</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}"
                        required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maksimal Pengguna</label>
                        <input type="number" name="max_users" value="{{ old('max_users', $package->max_users) }}"
                            placeholder="Kosong = Tanpa Batas"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maksimal Perangkat</label>
                        <input type="number" name="max_devices" value="{{ old('max_devices', $package->max_devices) }}"
                            placeholder="Kosong = Tanpa Batas"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.packages.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Perbarui
                        Paket</button>
                </div>
            </div>
        </form>
    </div>
@endsection