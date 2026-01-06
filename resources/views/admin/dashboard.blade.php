@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Ringkasan Sistem')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Stat Card 1 -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Penyewa Aktif</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($stats['active_tenants']) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Bisnis Berlangganan</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Total Penyewa</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($stats['total_tenants']) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Seluruh Basis Data</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Segera Berakhir</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-orange-600">{{ number_format($stats['expiring_soon']) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Butuh Perhatian Utama</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Estimasi MRR</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-emerald-600">IDR {{ number_format($stats['estimated_mrr']) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Pendapatan Berulang</p>
            </div>
        </div>
    </div>

    <!-- Growth Chart Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Pertumbuhan Subscriber</h2>
                <p class="text-sm text-slate-500">Grafik pendaftaran penyewa baru</p>
            </div>

            <!-- Date Filter -->
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="bg-transparent border-0 text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                <span class="text-slate-600 mx-1">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="bg-transparent border-0 text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                <button type="submit" class="ml-2 w-8 h-8 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </form>
        </div>

        <div class="h-[300px] w-full">
            <canvas id="subscriberChart"></canvas>
        </div>
    </div>

    <!-- Charts/Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Expiring Tenants List -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-extrabold text-slate-800">Menjelang Berakhir</h2>
                <span class="px-3 py-1 bg-red-50 text-red-600 text-[12px] font-extrabold uppercase tracking-tighter rounded-full border border-red-100 italic">Next 3 Days</span>
            </div>

            <div class="overflow-x-auto">
                @if($expiringTenants->count() > 0)
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">
                                <th class="pb-4">Nama Bisnis</th>
                                <th class="pb-4">Kadaluarsa</th>
                                <th class="pb-4 text-right">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($expiringTenants as $tenant)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 font-bold text-slate-700 text-sm">{{ $tenant->name }}</td>
                                    <td class="py-4 text-slate-500 text-xs">{{ $tenant->subscription_expires_at->format('d M Y') }}</td>
                                    <td class="py-4 text-right">
                                        <span class="px-3 py-1 rounded-lg bg-orange-50 text-orange-600 text-xs font-bold">
                                            {{ $tenant->days_left }} Hari
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                        <p class="text-slate-600 font-medium">Semua penyewa memiliki masa aktif aman.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Disk Usage -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-extrabold text-slate-800">Kapasitas Penyimpanan</h2>
                <i class="fas fa-database text-slate-200"></i>
            </div>
            <div class="space-y-6 max-h-[300px] overflow-y-auto pr-2 custom-scroll">
                @foreach($tenants as $tenant)
                    @php 
                                            $percentage = ($tenant->disk_usage_mb / $tenant->disk_limit_mb) * 100;
                        $colorClass = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-orange-500' : 'bg-blue-600');
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $tenant->name }}</span>
                            <span class="text-[12px] font-medium text-slate-600">{{ $tenant->disk_usage_mb }} MB / {{ $tenant->disk_limit_mb }} MB</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $colorClass }} h-full rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-extrabold text-slate-800">Distribusi Wilayah Bisnis</h2>
            <div class="flex items-center space-x-2">
                <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Live Monitoring</span>
            </div>
        </div>
        <div class="h-[400px] w-full rounded-3xl overflow-hidden ring-1 ring-slate-100">
            <div id="map" class="h-full w-full"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Init Map with a cleaner tile style
        var map = L.map('map', {
            zoomControl: false,
            scrollWheelZoom: false
        }).setView([-6.2088, 106.8456], 5);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: 'CartoDB'
        }).addTo(map);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Subscriber Chart
        const ctx = document.getElementById('subscriberChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Subscriber Baru',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 12 },
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#94a3b8',
                            stepSize: 1
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#94a3b8'
                        },
                        border: { display: false }
                    }
                }
            }
        });

        // Fetch Data
        fetch("{{ route('admin.map') }}")
            .then(response => response.json())
            .then(data => {
                data.forEach(tenant => {
                    if (tenant.latitude && tenant.longitude) {
                        var marker = L.circleMarker([tenant.latitude, tenant.longitude], {
                            radius: 8,
                            fillColor: "#2563eb",
                            color: "#fff",
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(map);

                        marker.bindPopup(`<div class="p-2 font-bold text-slate-800">${tenant.name}</div>`);
                    }
                });
            });
    </script>
@endpush