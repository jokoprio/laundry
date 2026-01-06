<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Owner') - My Laundry</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        owner: {
                            sidebar: '#2b343b', // Modern Dark Sidebar
                            content: '#f8fafc', // Content Background
                            primary: '#4d5bf9', // Brand Color
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Sidebar Styles (Online Tutorials Inspired) */
        .navigation {
            position: relative;
            height: 100%;
            transition: 0.5s;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .navigation .menu-list {
            padding-left: 5px;
            padding-top: 20px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .navigation .menu-list::-webkit-scrollbar {
            width: 0;
        }

        .navigation .menu-list li {
            position: relative;
            list-style: none;
            width: 100%;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            transition: background 0.3s;
        }

        /* Active Page (The Curved Look) */
        .navigation .menu-list li.active-page {
            background: #f8fafc;
            /* Match Content Background */
        }

        .navigation .menu-list li b:nth-child(1) {
            position: absolute;
            top: -20px;
            height: 20px;
            width: 100%;
            background: #f8fafc;
            display: none;
        }

        .navigation .menu-list li b:nth-child(1)::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-bottom-right-radius: 20px;
            background: #2b343b;
            /* Match Sidebar Background */
        }

        .navigation .menu-list li b:nth-child(2) {
            position: absolute;
            bottom: -20px;
            height: 20px;
            width: 100%;
            background: #f8fafc;
            display: none;
        }

        .navigation .menu-list li b:nth-child(2)::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-top-right-radius: 20px;
            background: #2b343b;
            /* Match Sidebar Background */
        }

        .navigation .menu-list li.active-page b:nth-child(1),
        .navigation .menu-list li.active-page b:nth-child(2) {
            display: block;
        }

        .navigation .menu-list li b {
            pointer-events: none;
            z-index: 0;
        }

        .navigation .menu-list li a {
            position: relative;
            display: flex;
            width: 100%;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.6);
            transition: 0.3s;
            z-index: 10;
        }

        .navigation .menu-list li.active-page a {
            color: #2b343b;
            font-weight: 700;
        }

        .navigation .menu-list li a .icon {
            position: relative;
            display: block;
            min-width: 60px;
            height: 60px;
            line-height: 60px;
            text-align: center;
            z-index: 1;
        }

        .navigation .menu-list li a .icon i {
            font-size: 1.25rem;
        }

        .navigation .menu-list li a .title {
            position: relative;
            display: block;
            padding-left: 10px;
            height: 60px;
            line-height: 60px;
            white-space: nowrap;
            font-weight: 500;
            font-size: 0.9rem;
            z-index: 1;
        }

        /* Hover Label for Collapsed Sidebar */
        .hover-label {
            display: none;
        }

        .navigation.collapsed .menu-list li .hover-label {
            display: block;
            position: absolute;
            left: 80px;
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
            background: #2b343b;
            color: #fff;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            pointer-events: none;
            z-index: 100;
        }

        .navigation.collapsed .menu-list li:hover .hover-label {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0);
        }

        .navigation.collapsed .menu-list li .hover-label::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
            width: 8px;
            height: 8px;
            background: #2b343b;
        }

        /* Section Header */
        .navigation .section-header {
            position: relative;
            z-index: 20;
            padding: 35px 0 10px 25px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #7dd3fc;
            /* Premium Sky Blue */
            white-space: nowrap;
            display: flex;
            align-items: center;
            opacity: 0.9;
        }

        .navigation .section-header::after {
            content: '';
            height: 1px;
            flex: 1;
            background: linear-gradient(to right, rgba(125, 211, 252, 0.2), transparent);
            margin-left: 15px;
            margin-right: 20px;
        }

        .navigation.collapsed .section-header {
            padding: 25px 0 10px 0;
            justify-content: center;
        }

        .navigation.collapsed .section-header::after {
            display: none;
        }

        .navigation:not(.collapsed) .menu-list li:hover:not(.active-page) a {
            color: #fff;
            transform: translateX(5px);
        }

        .navigation.collapsed .menu-list li:hover:not(.active-page) a {
            transform: none;
            color: #2b343b;
        }

        /* Curved Effect on Hover (Collapsed Mode) */
        .navigation.collapsed .menu-list li:hover:not(.active-page) {
            background: #f8fafc;
        }

        .navigation.collapsed .menu-list li:hover:not(.active-page) b:nth-child(1),
        .navigation.collapsed .menu-list li:hover:not(.active-page) b:nth-child(2) {
            display: block;
        }

        /* Highlight Priority (Collapsed Mode) */
        .navigation.collapsed .menu-list:hover li.active-page:not(:hover) {
            background: transparent;
        }

        .navigation.collapsed .menu-list:hover li.active-page:not(:hover) b {
            display: none !important;
        }

        .navigation.collapsed .menu-list:hover li.active-page:not(:hover) a {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }

        /* Tooltip Clipping Fix */
        .navigation.collapsed {
            overflow: visible !important;
        }

        .navigation.collapsed .menu-list {
            overflow: visible !important;
        }
    </style>
</head>

<body class="bg-owner-content text-slate-900 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="navigation bg-owner-sidebar shrink-0 z-50 shadow-2xl transition-all duration-300"
            :class="sidebarOpen ? 'w-72' : 'w-20 collapsed'">

            <!-- Brand Logo -->
            <div class="h-24 flex items-center px-5 shrink-0">
                <div
                    class="w-10 h-10 bg-owner-primary rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                    <i class="fas fa-soap text-white"></i>
                </div>
                <span
                    class="ml-4 font-black text-2xl tracking-tighter text-white whitespace-nowrap overflow-hidden transition-all duration-300"
                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 ml-0'">SiLondry</span>
            </div>

            <ul class="menu-list flex-1">

                <!-- Main -->
                <div class="section-header !pt-4" x-text="sidebarOpen ? 'Utama' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.dashboard') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.dashboard') }}">
                        <span class="icon"><i class="fas fa-th-large"></i></span>
                        <span class="title" x-show="sidebarOpen">Dashboard</span>
                    </a>
                    <span class="hover-label">Dashboard</span>
                </li>

                <!-- Operasional -->
                <div class="section-header" x-text="sidebarOpen ? 'Operasional' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.transactions.create') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.transactions.create') }}">
                        <span class="icon"><i class="fas fa-cash-register"></i></span>
                        <span class="title" x-show="sidebarOpen">Kasir / POS</span>
                    </a>
                    <span class="hover-label">Kasir / POS</span>
                </li>
                <li class="{{ request()->routeIs('owner.transactions.index') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.transactions.index') }}">
                        <span class="icon"><i class="fas fa-receipt"></i></span>
                        <span class="title" x-show="sidebarOpen">Riwayat Transaksi</span>
                    </a>
                    <span class="hover-label">Riwayat Transaksi</span>
                </li>
                <li class="{{ request()->routeIs('owner.customers.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.customers.index') }}">
                        <span class="icon"><i class="fas fa-user-group"></i></span>
                        <span class="title" x-show="sidebarOpen">Pelanggan</span>
                    </a>
                    <span class="hover-label">Pelanggan</span>
                </li>

                <!-- Logistik -->
                <div class="section-header" x-text="sidebarOpen ? 'Inventaris' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.inventory.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.inventory.index') }}">
                        <span class="icon"><i class="fas fa-box-archive"></i></span>
                        <span class="title" x-show="sidebarOpen">Stok Barang</span>
                    </a>
                    <span class="hover-label">Stok Barang</span>
                </li>
                <li class="{{ request()->routeIs('owner.suppliers.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.suppliers.index') }}">
                        <span class="icon"><i class="fas fa-truck-ramp-box"></i></span>
                        <span class="title" x-show="sidebarOpen">Supplier</span>
                    </a>
                    <span class="hover-label">Supplier</span>
                </li>
                <li class="{{ request()->routeIs('owner.purchases.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.purchases.index') }}">
                        <span class="icon"><i class="fas fa-cart-shopping"></i></span>
                        <span class="title" x-show="sidebarOpen">Pembelian</span>
                    </a>
                    <span class="hover-label">Pembelian</span>
                </li>

                <!-- HRD -->
                <div class="section-header" x-text="sidebarOpen ? 'Karyawan' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.employees.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.employees.index') }}">
                        <span class="icon"><i class="fas fa-users-gear"></i></span>
                        <span class="title" x-show="sidebarOpen">Data Staff</span>
                    </a>
                    <span class="hover-label">Data Staff</span>
                </li>
                <li class="{{ request()->routeIs('owner.users.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.users.index') }}">
                        <span class="icon"><i class="fas fa-user-shield"></i></span>
                        <span class="title" x-show="sidebarOpen">Kelola Akun Staff</span>
                    </a>
                    <span class="hover-label">Kelola Akun Staff</span>
                </li>
                <li class="{{ request()->routeIs('owner.payroll.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.payroll.index') }}">
                        <span class="icon"><i class="fas fa-money-check-dollar"></i></span>
                        <span class="title" x-show="sidebarOpen">Penggajian</span>
                    </a>
                    <span class="hover-label">Penggajian</span>
                </li>

                <!-- Finance -->
                <div class="section-header" x-text="sidebarOpen ? 'Keuangan' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.reports.finance') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.reports.finance') }}">
                        <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="title" x-show="sidebarOpen">Laporan Laba Rugi</span>
                    </a>
                    <span class="hover-label">Laporan Laba Rugi</span>
                </li>
                <li class="{{ request()->routeIs('owner.expenses.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.expenses.index') }}">
                        <span class="icon"><i class="fas fa-wallet"></i></span>
                        <span class="title" x-show="sidebarOpen">Pengeluaran</span>
                    </a>
                    <span class="hover-label">Pengeluaran</span>
                </li>
                <li class="{{ request()->routeIs('owner.services.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.services.index') }}">
                        <span class="icon"><i class="fas fa-tags"></i></span>
                        <span class="title" x-show="sidebarOpen">Layanan & Harga</span>
                    </a>
                    <span class="hover-label">Layanan & Harga</span>
                </li>

                <!-- Marketing -->
                <div class="section-header" x-text="sidebarOpen ? 'Marketing' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.membership-levels.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.membership-levels.index') }}">
                        <span class="icon"><i class="fas fa-id-card-clip"></i></span>
                        <span class="title" x-show="sidebarOpen">Level Member</span>
                    </a>
                    <span class="hover-label">Level Member</span>
                </li>
                <li class="{{ request()->routeIs('owner.loyalty.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.loyalty.index') }}">
                        <span class="icon"><i class="fas fa-gift"></i></span>
                        <span class="title" x-show="sidebarOpen">Loyalty Program</span>
                    </a>
                    <span class="hover-label">Loyalty Program</span>
                </li>

                <!-- Action -->
                <div class="section-header" x-text="sidebarOpen ? 'Sistem' : '•••'"></div>
                <li class="{{ request()->routeIs('owner.settings.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.settings.index') }}">
                        <span class="icon"><i class="fas fa-store"></i></span>
                        <span class="title" x-show="sidebarOpen">Identitas Bisnis</span>
                    </a>
                    <span class="hover-label">Identitas Bisnis</span>
                </li>
                <li class="{{ request()->routeIs('owner.profile.*') ? 'active-page' : '' }}">
                    <b></b><b></b>
                    <a href="{{ route('owner.profile.change-password') }}">
                        <span class="icon"><i class="fas fa-key"></i></span>
                        <span class="title" x-show="sidebarOpen">Ganti Password</span>
                    </a>
                    <span class="hover-label">Ganti Password</span>
                </li>
                <li>
                    <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit()">
                        <span class="icon text-red-400"><i class="fas fa-power-off"></i></span>
                        <span class="title text-red-400" x-show="sidebarOpen">Keluar Aplikasi</span>
                    </a>
                    <span class="hover-label">Keluar Aplikasi</span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </li>
            </ul>

            <!-- Profile Bottom Area -->
            <div class="p-5 border-t border-white/5 bg-slate-900/20 shrink-0">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 rounded-xl bg-owner-primary flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20 shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-slate-500 font-bold uppercase mt-0.5">Business Owner</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header
                class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10 shadow-sm">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="w-10 h-10 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-600 transition-all flex items-center justify-center border border-slate-100">
                        <i class="fas fa-bars-staggered" :class="sidebarOpen ? '' : 'rotate-180'"></i>
                    </button>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">@yield('header')</h2>
                </div>

                <div class="flex items-center space-x-6">
                    <div
                        class="flex items-center space-x-2 px-3 py-1.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Sistem
                            Online</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="hidden md:block text-right">
                            <p class="text-xs font-bold text-slate-700 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-owner-primary font-bold uppercase mt-1">Status Pro</p>
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area (Scrollable) -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto p-8 bg-owner-content">
                <div class="max-w-7xl mx-auto">
                    <!-- Notifications -->
                    @if(session('success'))
                        <div
                            class="mb-8 flex items-center p-5 bg-emerald-50 border border-emerald-100 rounded-3xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
                            <div
                                class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20 text-white">
                                <i class="fas fa-check text-xl"></i>
                            </div>
                            <div class="ml-5">
                                <p class="text-base font-extrabold text-emerald-900 leading-none">Berhasil!</p>
                                <p class="text-sm text-emerald-700 mt-1.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Main Dynamic Content -->
                    <div class="pb-20">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>