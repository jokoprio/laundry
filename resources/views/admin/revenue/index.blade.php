@extends('layouts.admin')

@section('title', 'Laporan Pendapatan')
@section('header', 'Laporan Pendapatan')

@section('content')
    <div class="space-y-6">
        <!-- Filters & Actions -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
            <form action="{{ route('admin.reports.revenue') }}" method="GET"
                class="flex flex-col md:flex-row md:items-end gap-4 justify-between">
                <div class="flex flex-col md:flex-row gap-4 flex-grow">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full md:w-48 px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Sampai
                            Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full md:w-48 px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-6 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-colors border border-red-100">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-[2rem] text-white shadow-lg shadow-emerald-500/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-100">Total Pendapatan</span>
                </div>
                <h3 class="text-3xl font-extrabold">IDR {{ number_format($totalRevenue) }}</h3>
                <p class="text-sm text-emerald-100 mt-1">Periode {{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Pendaftar Baru</span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $tenants->count() }}</h3>
                <p class="text-sm text-slate-600 mt-1">Tenant terdaftar</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Rata-rata</span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">
                    IDR {{ $tenants->count() > 0 ? number_format($totalRevenue / $tenants->count()) : 0 }}
                </h3>
                <p class="text-sm text-slate-600 mt-1">Per Tenant</p>
            </div>
        </div>

        <!-- Chart -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
            <h2 class="text-xl font-extrabold text-slate-800 mb-6">Grafik Pendapatan</h2>
            <div class="h-[350px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Details Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
            <h2 class="text-xl font-extrabold text-slate-800 mb-6">Rincian Transaksi (Pendaftaran Baru)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-4 pl-4">Tanggal</th>
                            <th class="pb-4">Tenant</th>
                            <th class="pb-4">Paket</th>
                            <th class="pb-4 text-right pr-4">Nilai (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 pl-4 text-slate-600 text-sm font-medium">
                                    {{ $tenant->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="py-4">
                                    <span class="font-bold text-slate-700 block">{{ $tenant->name }}</span>
                                    <span class="text-xs text-slate-600">{{ $tenant->owner_name }}</span>
                                </td>
                                <td class="py-4">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold">
                                        {{ $tenant->subscriptionPackage->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-right font-bold text-slate-700">
                                    {{ number_format($tenant->subscriptionPackage->price ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-600 font-medium">
                                    Tidak ada data pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tenants->hasPages())
            <div class="px-8 py-6 border-t border-slate-50">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($data) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        hoverBackgroundColor: '#059669'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 13 },
                            bodyFont: { family: 'Inter', size: 12 },
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return 'IDR ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#d3d6daff', borderDash: [5, 5] },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#0f1011ff',
                                callback: function (value) {
                                    return new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                                }
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#050d1aff' },
                            border: { display: false }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection