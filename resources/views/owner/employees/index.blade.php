@extends('layouts.owner')

@section('title', 'Karyawan')
@section('header', 'Manajemen Karyawan')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Daftar Karyawan</h3>
                <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Kelola staf dan konfigurasi penggajian laundry</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
            </div>
            <button onclick="document.getElementById('createEmployeeModal').classList.remove('hidden')"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Karyawan
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Nama & Jabatan
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Skema Gaji
                        </th>
                        <th scope="col"
                            class="px-8 py-5 text-right text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all mr-4">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-800">{{ $employee->name }}</div>
                                        <div class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                            {{ $employee->position }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ $employee->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span class="block text-[12px] font-black uppercase tracking-widest text-slate-600 mb-1">
                                    {{ str_replace('_', ' ', $employee->salary_type) }}
                                </span>
                                <div class="text-sm font-black text-slate-700">
                                    @if(in_array($employee->salary_type, ['daily', 'monthly']))
                                        IDR {{ number_format($employee->base_salary, 0, ',', '.') }}
                                    @else
                                        IDR {{ number_format($employee->rate_per_unit, 0, ',', '.') }} <span
                                            class="text-[12px] font-bold text-slate-600">/ UNIT</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right space-x-2">
                                <button onclick='openEditModal(@json($employee))'
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-black uppercase tracking-tighter rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <form action="{{ route('owner.employees.destroy', $employee->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Hapus karyawan ini?');">
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
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-user-tie text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Karyawan Kosong</h4>
                                    <p class="text-slate-600 text-sm mt-1">Tambahkan staf pertama Anda untuk mengelola laundry.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createEmployeeModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('createEmployeeModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('owner.employees.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-user-plus text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Karyawan</h3>
                            <p class="text-sm text-slate-600 font-medium tracking-tight">Daftarkan anggota tim baru</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Jabatan</label>
                                    <input type="text" name="position" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Telepon</label>
                                    <input type="text" name="phone"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 space-y-4">
                                <h4 class="text-[12px] font-black text-slate-600 uppercase tracking-widest">Konfigurasi
                                    Penggajian</h4>
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Tipe Gaji</label>
                                    <select name="salary_type" id="create_salary_type"
                                        onchange="toggleSalaryFields('create')"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                        <option value="daily">Daily (Harian)</option>
                                        <option value="monthly">Monthly (Bulanan)</option>
                                        <option value="borongan_item">Borongan (Per pcs)</option>
                                        <option value="borongan_kg">Borongan (Per kg)</option>
                                    </select>
                                </div>
                                <div id="create_base_salary_group">
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Base Salary (Gaji
                                        Pokok)</label>
                                    <input type="number" step="0.01" name="base_salary"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                </div>
                                <div id="create_rate_group" class="hidden">
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Rate per Unit (Upah per
                                        item/kg)</label>
                                    <input type="number" step="0.01" name="rate_per_unit"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 mt-6 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Simpan
                            Karyawan</button>
                        <button type="button"
                            onclick="document.getElementById('createEmployeeModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editEmployeeModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('editEmployeeModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 shadow-lg shadow-blue-500/10">
                                <i class="fas fa-edit text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Karyawan</h3>
                            <p class="text-sm text-slate-600 font-medium tracking-tight">Perbarui data dan skema gaji staf
                            </p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="edit_name" required
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Jabatan</label>
                                    <input type="text" name="position" id="edit_position" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Telepon</label>
                                    <input type="text" name="phone" id="edit_phone"
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 space-y-4">
                                <h4 class="text-[12px] font-black text-slate-600 uppercase tracking-widest">Konfigurasi
                                    Penggajian</h4>
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Tipe Gaji</label>
                                    <select name="salary_type" id="edit_salary_type" onchange="toggleSalaryFields('edit')"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                        <option value="daily">Daily (Harian)</option>
                                        <option value="monthly">Monthly (Bulanan)</option>
                                        <option value="borongan_item">Borongan (Per pcs)</option>
                                        <option value="borongan_kg">Borongan (Per kg)</option>
                                    </select>
                                </div>
                                <div id="edit_base_salary_group">
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Base Salary (Gaji
                                        Pokok)</label>
                                    <input type="number" step="0.01" name="base_salary" id="edit_base_salary"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                </div>
                                <div id="edit_rate_group" class="hidden">
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">Rate per Unit (Upah per
                                        item/kg)</label>
                                    <input type="number" step="0.01" name="rate_per_unit" id="edit_rate_per_unit"
                                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 mt-6 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">Perbarui
                            Data</button>
                        <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSalaryFields(mode) {
            const type = document.getElementById(mode + '_salary_type').value;
            const baseGroup = document.getElementById(mode + '_base_salary_group');
            const rateGroup = document.getElementById(mode + '_rate_group');

            if (type === 'daily' || type === 'monthly') {
                baseGroup.classList.remove('hidden');
                rateGroup.classList.add('hidden');
            } else {
                baseGroup.classList.add('hidden');
                rateGroup.classList.remove('hidden');
            }
        }

        function openEditModal(employee) {
            document.getElementById('editEmployeeModal').classList.remove('hidden');
            document.getElementById('edit_name').value = employee.name;
            document.getElementById('edit_position').value = employee.position;
            document.getElementById('edit_phone').value = employee.phone;
            document.getElementById('edit_salary_type').value = employee.salary_type;
            document.getElementById('edit_base_salary').value = employee.base_salary;
            document.getElementById('edit_rate_per_unit').value = employee.rate_per_unit;
            toggleSalaryFields('edit');
            let form = document.getElementById('editEmployeeForm');
            form.action = "{{ url('owner/employees') }}/" + employee.id;
        }

        toggleSalaryFields('create');
    </script>
@endsection