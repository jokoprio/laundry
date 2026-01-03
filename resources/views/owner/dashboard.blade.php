@extends('layouts.owner')

@section('title', 'Dashboard')
@section('header', 'Ringkasan Bisnis')

@section('content')
    <!-- High-level Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Sales Today -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-green-500 text-xs font-bold leading-none">
                        <i class="fas fa-calendar-day mr-1 text-[12px]"></i>Hari Ini
                    </span>
                </div>
            </div>
            <div>
                <p class="text-[12px] font-bold text-slate-600 uppercase tracking-widest mb-1">Penjualan Hari Ini</p>
                <h3 class="text-2xl font-extrabold text-slate-800">IDR {{ number_format($stats['today_sales']) }}</h3>
                <p class="text-[11px] text-slate-600 mt-1">Bulan ini: IDR {{ number_format($stats['month_sales']) }}</p>
            </div>
        </div>

        <!-- Active Orders -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <i class="fas fa-shopping-bag text-xl"></i>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center">
                    <i class="fas fa-ellipsis-h text-slate-300 text-xs"></i>
                </div>
            </div>
            <div>
                <p class="text-[12px] font-bold text-slate-600 uppercase tracking-widest mb-1">Pesanan Aktif</p>
                <h3 class="text-2xl font-extrabold text-slate-800">-</h3>
                <p class="text-[11px] text-slate-600 mt-1">Menunggu pemrosesan</p>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                @if($stats['low_stock_count'] > 0)
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-orange-400 opacity-75"></span>
                @endif
            </div>
            <div>
                <p class="text-[12px] font-bold text-slate-600 uppercase tracking-widest mb-1">Stok Rendah</p>
                <h3
                    class="text-2xl font-extrabold {{ $stats['low_stock_count'] > 0 ? 'text-orange-600' : 'text-slate-800' }}">
                    {{ $stats['low_stock_count'] }}
                </h3>
                <a href="{{ route('owner.inventory.index') }}"
                    class="text-[12px] font-bold text-orange-600 hover:underline mt-1 block tracking-tighter">Periksa
                    Inventaris &rarr;</a>
            </div>
        </div>

        <!-- Subscription -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-crown text-xl"></i>
                </div>
            </div>
            <div>
                <p class="text-[12px] font-bold text-slate-600 uppercase tracking-widest mb-1">Langganan</p>
                <h3 class="text-lg font-extrabold text-slate-800 truncate leading-none">
                    {{ $tenant->subscriptionPackage->name ?? 'Free Trial' }}
                </h3>
                @php
                    $daysLeft = $tenant->subscription_expires_at ? now()->diffInDays($tenant->subscription_expires_at, false) : 0;
                @endphp
                <div class="mt-2">
                    @if($daysLeft > 0)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[12px] font-bold bg-emerald-100 text-emerald-700">
                            {{ ceil($daysLeft) }} Hari Tersisa
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[12px] font-bold bg-red-100 text-red-700">
                            Expired
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Grafik Pendapatan</h2>
                <p class="text-sm text-slate-500">Tren penjualan harian</p>
            </div>

            <form action="{{ route('owner.dashboard') }}" method="GET"
                class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="bg-transparent border-0 text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                <span class="text-slate-600 mx-1">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="bg-transparent border-0 text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                <button type="submit"
                    class="ml-2 w-8 h-8 bg-indigo-600 text-white rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </form>
        </div>

        <div class="h-[300px] w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Top Services -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-800 mb-6">Layanan Terlaris</h3>
                <div class="space-y-4">
                    @forelse($topServices as $service)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm leading-none">{{ $service->name }}</p>
                                    <p class="text-[12px] text-slate-600 mt-0.5">Pendapatan</p>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-indigo-600">
                                {{ number_format($service->total_revenue) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-slate-600 text-sm font-medium">Belum ada data transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-800 mb-6">Akses Cepat</h3>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('owner.transactions.create') }}"
                        class="flex items-center p-5 bg-blue-50/50 hover:bg-blue-600 group rounded-2xl transition-all border border-blue-100/50">
                        <div
                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform mr-4">
                            <i class="fas fa-plus text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 group-hover:text-white transition-colors">Transaksi Baru
                            </p>
                            <p class="text-[12px] text-slate-600 group-hover:text-blue-100 transition-colors">Input order
                                pelanggan</p>
                        </div>
                    </a>
                    <a href="{{ route('owner.inventory.index') }}"
                        class="flex items-center p-5 bg-emerald-50/50 hover:bg-emerald-600 group rounded-2xl transition-all border border-emerald-100/50">
                        <div
                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform mr-4">
                            <i class="fas fa-boxes text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 group-hover:text-white transition-colors">Restock Bahan
                            </p>
                            <p class="text-[12px] text-slate-600 group-hover:text-emerald-100 transition-colors">Perbarui
                                stok barang</p>
                        </div>
                    </a>
                    <a href="{{ route('owner.expenses.index') }}"
                        class="flex items-center p-5 bg-slate-50 hover:bg-slate-800 group rounded-2xl transition-all border border-slate-200/50">
                        <div
                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform mr-4">
                            <i class="fas fa-file-invoice-dollar text-slate-500"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 group-hover:text-white transition-colors">Pengeluaran
                            </p>
                            <p class="text-[12px] text-slate-600 group-hover:text-slate-600 transition-colors">Catat arus
                                kas keluar</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Low Stock Monitoring -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 h-full">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 leading-none">Peringatan Inventaris</h3>
                        <p class="text-xs text-slate-600 mt-2">Bahan yang hampir habis</p>
                    </div>
                    <a href="{{ route('owner.inventory.index') }}"
                        class="px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-[12px] font-bold text-slate-600 transition-colors">
                        Lihat Semua
                    </a>
                </div>

                @if($lowStockItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($lowStockItems as $item)
                            <div
                                class="flex items-center justify-between p-4 bg-orange-50/30 rounded-2xl border border-orange-100/50">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-orange-500 mr-4">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-none">{{ $item->name }}</p>
                                        <p class="text-[12px] text-slate-600 mt-1 uppercase tracking-tighter">Batas Minim Terlampaui
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-extrabold text-orange-600">{{ $item->stock }}</span>
                                    <span class="text-[12px] text-orange-400 font-bold ml-0.5">{{ $item->unit }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div
                            class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 text-emerald-500">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800">Semua Aman!</h4>
                        <p class="text-slate-500 text-sm mt-1">Seluruh kategori inventaris dalam batas normal.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
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
                            grid: {
                                color: '#f1f5f9',
                                borderDash: [5, 5]
                            },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#394250ff',
                                callback: function (value) {
                                    return new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                                }
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#394250ff'
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection