@extends('layouts.admin')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800">Log Aktivitas</h3>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
                        class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                </div>
                <div class="w-48">
                    <select name="tenant_id"
                        class="w-full px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        <option value="">Semua Tenant</option>
                        @foreach(\App\Models\Tenant::all() as $tenant)
                            <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold text-sm hover:bg-slate-900 transition-all active:scale-95">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'tenant_id']))
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="px-6 py-3 bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-300 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-wider">Pengguna</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-wider">Tenant</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-wider text-right">
                            Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-700 block">
                                    {{ $log->created_at->format('d M Y') }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium uppercase">
                                    {{ $log->created_at->format('H:i:s') }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-bold text-slate-700 block">{{ $log->user->name ?? 'System' }}</span>
                                        <span class="text-xs text-slate-400">{{ $log->user->role ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                                    {{ $log->tenant->name ?? 'Global' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    @php
                                        $icon = 'info-circle';
                                        $color = 'blue';
                                        if (str_contains($log->description, 'Created')) {
                                            $icon = 'plus-circle';
                                            $color = 'emerald';
                                        } elseif (str_contains($log->description, 'Updated')) {
                                            $icon = 'edit';
                                            $color = 'amber';
                                        } elseif (str_contains($log->description, 'Deleted')) {
                                            $icon = 'trash-alt';
                                            $color = 'red';
                                        } elseif (str_contains($log->description, 'Login')) {
                                            $icon = 'sign-in-alt';
                                            $color = 'indigo';
                                        }
                                    @endphp
                                    <i class="fas fa-{{ $icon }} text-{{ $color }}-500"></i>
                                    <span class="text-sm font-medium text-slate-600">
                                        {{ $log->description }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($log->properties)
                                    <button class="text-slate-400 hover:text-blue-600 transition-colors"
                                        onclick="showLogDetails('{{ $log->id }}', {{ json_encode($log->properties) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif
                                <span class="block text-[10px] text-slate-300 mt-1">{{ $log->ip_address }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-4">
                                        <i class="fas fa-history text-2xl"></i>
                                    </div>
                                    <p class="text-slate-500 font-bold">Tidak ada aktivitas ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <div id="logDetailModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLogModal()"></div>
            <div
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl relative z-10 overflow-hidden border border-slate-100">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-xl font-black text-slate-800">Detail Perubahan</h3>
                    <button onclick="closeLogModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-8 max-h-[60vh] overflow-y-auto">
                    <div id="logPropertiesContent" class="space-y-4">
                        <!-- Dynamic Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLogDetails(id, properties) {
            const content = document.getElementById('logPropertiesContent');
            content.innerHTML = '';

            if (properties.old || properties.new) {
                const table = document.createElement('table');
                table.className = 'w-full text-sm';
                table.innerHTML = `
                                        <thead>
                                            <tr class="text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">
                                                <th class="pb-4">Field</th>
                                                <th class="pb-4">Lama</th>
                                                <th class="pb-4 text-right">Baru</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50"></tbody>
                                    `;
                const tbody = table.querySelector('tbody');

                const fields = new Set([...Object.keys(properties.old || {}), ...Object.keys(properties.new || {})]);

                fields.forEach(field => {
                    const row = document.createElement('tr');
                    const oldValue = properties.old ? (properties.old[field] ?? '-') : '-';
                    const newValue = properties.new ? (properties.new[field] ?? '-') : '-';

                    row.innerHTML = `
                                            <td class="py-3 font-bold text-slate-600">${field}</td>
                                            <td class="py-3 text-red-500 font-medium">${oldValue}</td>
                                            <td class="py-3 text-emerald-500 font-black text-right">${newValue}</td>
                                        `;
                    tbody.appendChild(row);
                });
                content.appendChild(table);
            } else {
                const pre = document.createElement('pre');
                pre.className = 'bg-slate-50 p-6 rounded-2xl text-xs text-slate-600 overflow-x-auto';
                pre.innerText = JSON.stringify(properties, null, 2);
                content.appendChild(pre);
            }

            document.getElementById('logDetailModal').classList.remove('hidden');
        }

        function closeLogModal() {
            document.getElementById('logDetailModal').classList.add('hidden');
        }
    </script>
@endsection