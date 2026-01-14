@extends('layouts.admin')

@section('title', 'Manajemen Tenant')
@section('header', 'Manajemen Tenant')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Laundry Terdaftar</h3>
                <p class="text-xs text-slate-500 mt-1">Kelola seluruh bisnis yang berlangganan</p>
            </div>
            <div class="flex items-center space-x-2 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                <i class="fas fa-store text-admin-primary text-sm"></i>
                <span class="text-sm font-bold text-slate-700">{{ $tenants->count() }} Total</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-8 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Nama Bisnis
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Domain
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Paket
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-5 text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Masa Aktif
                        </th>
                        <th scope="col" class="px-8 py-5 text-right text-[12px] font-bold text-slate-700 uppercase tracking-[0.2em]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-50 text-admin-primary rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-admin-primary group-hover:text-white transition-all mr-4">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-800">{{ $tenant->name }}</div>
                                        <div class="text-[11px] text-slate-500 font-medium">{{ $tenant->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200 group-hover:bg-white transition-colors">
                                    {{ $tenant->domain }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <i class="fas fa-box-open text-slate-300 text-xs mr-2"></i>
                                    {{ $tenant->subscriptionPackage->name ?? 'None' }}
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @if($tenant->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold bg-red-50 text-red-600 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-slate-500">
                                {{ $tenant->subscription_expires_at ? $tenant->subscription_expires_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <button onclick="openDeleteModal('{{ $tenant->id }}', '{{ $tenant->name }}')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-[11px] font-black uppercase tracking-tighter rounded-xl text-white bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20 active:scale-95 transition-all">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                        <i class="fas fa-store-slash text-4xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800">Belum Ada Tenant</h4>
                                    <p class="text-slate-600 text-sm mt-1">Tenant yang mendaftar akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteTenantModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="deleteTenantForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white p-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-20 h-20 bg-red-50 rounded-[2rem] flex items-center justify-center mb-6 text-red-500 shadow-lg shadow-red-500/10">
                                <i class="fas fa-exclamation-triangle text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2" id="modal-title">Hapus Tenant?</h3>
                            <p class="text-sm text-slate-500 font-medium px-4">
                                Anda akan menghapus <b id="modalTenantName" class="text-red-600"></b> secara permanen. Tindakan ini menghapus seluruh data terkait.
                            </p>
                        </div>

                        <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-[12px] font-black text-slate-600 uppercase tracking-widest mb-4">Konfirmasi Keamanan</p>
                            <p class="text-sm text-slate-600 mb-4">
                                Silakan ketik <b id="modalTenantNameConfirm" class="text-slate-800 select-all border-b-2 border-slate-200"></b> di bawah:
                            </p>
                            <input type="text" name="confirm_name" required
                                class="block w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all outline-none"
                                placeholder="Ketik nama bisnis tepat sama">
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-red-600 text-sm font-black text-white hover:bg-red-700 shadow-xl shadow-red-600/20 active:scale-[0.98] transition-all uppercase tracking-tighter">
                            Ya, Hapus Permanen
                        </button>
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, name) {
            const modal = document.getElementById('deleteTenantModal');
            modal.classList.remove('hidden');
            document.getElementById('modalTenantName').innerText = name;
            document.getElementById('modalTenantNameConfirm').innerText = name;

            let form = document.getElementById('deleteTenantForm');
            form.action = "{{ url('admin/tenants') }}/" + id;
        }

        function closeDeleteModal() {
            document.getElementById('deleteTenantModal').classList.add('hidden');
        }
    </script>
@endsection