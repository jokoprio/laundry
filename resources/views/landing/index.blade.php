<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLondry - Solusi Manajemen Laundry Modern & Cerdas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent),
                radial-gradient(circle at bottom left, rgba(147, 197, 253, 0.1), transparent);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans selection:bg-primary-100 selection:text-primary-700">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-200">
                            <i class="fas fa-soap text-white text-xl"></i>
                        </div>
                        <span
                            class="font-extrabold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-800">SiLondry</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features"
                        class="text-slate-600 hover:text-primary-600 font-medium transition-colors">Fitur</a>
                    <a href="#pricing"
                        class="text-slate-600 hover:text-primary-600 font-medium transition-colors">Harga</a>
                    <div class="h-6 w-px bg-slate-200"></div>
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-slate-700">Halo, {{ Auth::user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 text-slate-700 hover:bg-red-50 hover:text-red-600 transition-all">Keluar</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-slate-600 hover:text-primary-600 font-medium transition-colors">Masuk</a>
                        <a href="#register"
                            class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all transform hover:-translate-y-0.5">Daftar
                            Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                <div class="text-center lg:text-left space-y-8">
                    <div>
                        <span
                            class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-primary-50 text-primary-700 border border-primary-100 mb-6">
                            <span class="flex h-2 w-2 rounded-full bg-primary-600 mr-2 animate-pulse"></span>
                            Terpercaya oleh 1000+ Pengusaha Laundry
                        </span>
                        <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                            Kelola Bisnis Laundry <br />
                            <span
                                class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-400">Jauh
                                Lebih Mudah</span>
                        </h1>
                        <p class="mt-6 text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            Solusi SaaS laundry terlengkap untuk mengoptimalkan operasional, memantau keuangan secara
                            real-time, dan meningkatkan loyalitas pelanggan Anda.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="#register"
                            class="px-8 py-4 bg-primary-600 text-white rounded-2xl font-bold text-lg hover:bg-primary-700 shadow-xl shadow-primary-200 transition-all transform hover:-translate-y-1 flex items-center justify-center">
                            Mulai Uji Coba Gratis
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                        <a href="#features"
                            class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all flex items-center justify-center">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
                <div class="mt-16 lg:mt-0 relative">
                    <div
                        class="absolute -top-12 -left-12 w-64 h-64 bg-primary-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow">
                    </div>
                    <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"
                        style="animation-delay: 2s"></div>
                    <div class="relative animate-float">
                        <img src="{{ asset('images/hero.png') }}" alt="Laundry Dashboard Preview"
                            class="rounded-3xl shadow-2xl border border-white/50 ring-1 ring-slate-200">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">Fitur Unggulan Kami</h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto mb-16">Dirancang khusus untuk membantu Anda mengelola
                bisnis laundry dari skala kecil hingga menengah ke atas.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <!-- Feature 1 -->
                <div
                    class="p-8 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl hover:border-primary-100 transition-all group">
                    <div
                        class="w-14 h-14 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <i class="fas fa-cash-register text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">POS & Kasir Cerdas</h3>
                    <p class="text-slate-600 leading-relaxed">Transaksi cepat, pencetakan struk otomatis, dan integrasi
                        dengan sistem pembayaran digital.</p>
                </div>
                <!-- Feature 2 -->
                <div
                    class="p-8 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl hover:border-primary-100 transition-all group">
                    <div
                        class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="fas fa-boxes text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Manajemen Inventaris</h3>
                    <p class="text-slate-600 leading-relaxed">Pantau stok bahan baku (sabun, pewangi) secara otomatis
                        setiap kali ada transaksi.</p>
                </div>
                <!-- Feature 3 -->
                <div
                    class="p-8 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl hover:border-primary-100 transition-all group">
                    <div
                        class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Laporan Keuangan</h3>
                    <p class="text-slate-600 leading-relaxed">Analisis performa bisnis Anda melalui laporan laba rugi
                        dan tren penjualan yang mendalam.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-24 bg-slate-50 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">Paket Harga Transparan</h2>
                <p class="text-slate-500 text-lg">Investasi terbaik untuk pertumbuhan bisnis Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $package)
                    <div
                        class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 flex flex-col hover:shadow-lg transition-all relative overflow-hidden group">
                        @if($loop->index == 1)
                            <div
                                class="absolute top-0 right-0 bg-primary-600 text-white text-[12px] font-bold px-4 py-1 transform rotate-45 translate-x-12 translate-y-3">
                                POPULER</div>
                        @endif
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $package->name }}</h3>
                            <div class="flex items-baseline mb-4">
                                <span class="text-4xl font-extrabold text-slate-900">RP
                                    {{ number_format($package->price) }}</span>
                                <span class="ml-2 text-slate-500">/ {{ $package->duration_days }} hari</span>
                            </div>
                            <ul class="space-y-4">
                                <li class="flex items-center text-slate-600">
                                    <i class="fas fa-check-circle text-primary-500 mr-3"></i>
                                    Maks. Pengguna: {{ $package->max_users ?? 'Tak Terbatas' }}
                                </li>
                                <li class="flex items-center text-slate-600">
                                    <i class="fas fa-check-circle text-primary-500 mr-3"></i>
                                    Semua Fitur Premium
                                </li>
                                <li class="flex items-center text-slate-600">
                                    <i class="fas fa-check-circle text-primary-500 mr-3"></i>
                                    Dukungan Prioritas
                                </li>
                                <li class="flex items-center text-slate-600">
                                    <i class="fas fa-check-circle text-primary-500 mr-3"></i>
                                    Laporan Tak Terbatas
                                </li>
                            </ul>
                        </div>
                        <a href="#register"
                            class="mt-auto block w-full py-4 text-center rounded-2xl font-bold bg-slate-50 text-slate-700 border border-slate-200 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all">Pilih
                            Paket</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Registration -->
    <section id="register" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass p-8 md:p-16 rounded-[40px] border border-slate-200 shadow-2xl">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold mb-6">Mulai Bisnis Anda <br /><span
                                class="text-primary-600">Hari Ini</span></h2>
                        <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                            Bergabunglah bersama ratusan pengusaha laundry lainnya. Dapatkan akses gratis selama 14 hari
                            tanpa komitmen kartu kredit.
                        </p>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Keamanan Terjamin</h4>
                                    <p class="text-sm text-slate-500">Data bisnis Anda aman di cloud kami yang
                                        terenkripsi.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-sync"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Update Berkala</h4>
                                    <p class="text-sm text-slate-500">Kami terus menambahkan fitur baru setiap bulannya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                        <form action="{{ route('landing.register') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Bisnis</label>
                                    <input type="text" name="business_name" required
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                        placeholder="Laundry Ku">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama
                                        Pemilik</label>
                                    <input type="text" name="owner_name" required
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                        placeholder="John Doe">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Bisnis</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                    placeholder="hello@company.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                                <input type="text" name="phone" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                    placeholder="0812xxxxxx">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                                    <input type="password" name="password" required
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                        placeholder="••••••••">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                        placeholder="••••••••">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Kupon
                                    (Opsional)</label>
                                <input type="text" name="coupon_code"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                                    placeholder="DISKONBARU">
                            </div>
                            <button type="submit"
                                class="w-full py-4 bg-primary-600 text-white rounded-2xl font-bold hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all transform hover:-translate-y-0.5">Daftar
                                Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-soap text-white text-sm"></i>
                        </div>
                        <span class="font-extrabold text-2xl text-white">SiLondry</span>
                    </div>
                    <p class="text-slate-600 max-w-sm mb-8">
                        The ultimate solution for modern laundry businesses. We help you scale, automate, and grow with
                        technology.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 hover:bg-primary-600 hover:text-white transition-all"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 hover:bg-primary-600 hover:text-white transition-all"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-600 hover:bg-primary-600 hover:text-white transition-all"><i
                                class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Perusahaan</h4>
                    <ul class="space-y-4 text-slate-600">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Bantuan</h4>
                    <ul class="space-y-4 text-slate-600">
                        <li><a href="#" class="hover:text-white transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-10 border-t border-slate-800 text-center">
                <p class="text-slate-500 text-sm">
                    &copy; 2025 SiLondry SaaS. Seluruh hak cipta dilindungi undang-undang.
                </p>
            </div>
        </div>
    </footer>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Selamat!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-[30px]',
                    confirmButton: 'rounded-xl px-8 py-3'
                }
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            Swal.fire({
                title: 'Pendaftaran Gagal',
                html: '<div class="text-left text-sm py-2">@foreach ($errors->all() as $error)<div class="mb-1 flex items-start"><span class="mr-2 text-red-500">•</span> {{ $error }}</div>@endforeach</div>',
                icon: 'error',
                confirmButtonColor: '#d33',
                customClass: {
                    popup: 'rounded-[30px]',
                    confirmButton: 'rounded-xl px-8 py-3'
                }
            });
        </script>
    @endif

</body>

</html>