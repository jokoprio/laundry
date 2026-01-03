<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiLondry SaaS</title>

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

        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="bg-pattern min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-xl shadow-primary-200 mb-4 animate-bounce"
                style="animation-duration: 3s">
                <i class="fas fa-soap text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">SiLondry</h1>
            <p class="text-sm text-slate-500 font-medium">Masuk ke Panel Kontrol Anda</p>
        </div>

        <div class="glass p-8 md:p-10 rounded-[2.5rem] shadow-2xl border border-white">
            <h2 class="text-xl font-bold mb-8 text-slate-800">Silakan Masuk</h2>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl animate-in fade-in duration-500">
                    <div class="flex items-center text-red-600 mb-1">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Kesalahan Login</span>
                    </div>
                    <ul class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs text-red-700 font-medium list-disc list-inside">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Alamat
                        Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" required
                            class="w-full bg-white/50 border border-slate-200 pl-11 pr-4 py-3.5 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Kata
                        Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-white/50 border border-slate-200 pl-11 pr-4 py-3.5 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                        <label for="remember_me" class="ml-2 text-xs font-medium text-slate-600">Ingat saya</label>
                    </div>
                    <a href="#" class="text-xs font-bold text-primary-600 hover:text-primary-700">Lupa sandi?</a>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-primary-600 text-white rounded-2xl font-bold hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all transform hover:-translate-y-0.5 active:scale-95">
                    Lanjut ke Dashboard
                </button>
            </form>

            <!-- ✅ PENGINGAT LOGIN (klik untuk auto-fill + copy) -->
            <div class="mt-7">
                <div class="flex items-center justify-between mb-3 px-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Quick Login</p>
                    <span id="credToast" class="hidden text-[11px] font-bold text-emerald-600">
                        <i class="fas fa-check-circle mr-1"></i>Tersalin & terisi
                    </span>
                </div>

                <div class="space-y-3">
                    <!-- Admin -->
                    <button type="button"
                        class="w-full text-left p-4 rounded-2xl bg-white/50 border border-slate-200 hover:border-primary-300 hover:bg-white/70 transition-all"
                        data-email="admin@gmail.com" data-pass="password123" onclick="useCreds(this)">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-primary-600/10 flex items-center justify-center">
                                <i class="fas fa-user-shield text-primary-700"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-bold text-slate-800">Administrator</p>
                                    <span class="text-[11px] font-bold text-primary-700">Klik untuk isi</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span class="font-semibold">Username:</span> admin@gmail.com
                                    <span class="mx-2 text-slate-300">•</span>
                                    <span class="font-semibold">Password:</span> password123
                                </p>
                            </div>
                        </div>
                    </button>

                    <!-- Tenant -->
                    <button type="button"
                        class="w-full text-left p-4 rounded-2xl bg-white/50 border border-slate-200 hover:border-primary-300 hover:bg-white/70 transition-all"
                        data-email="jokocyberlink@gmail.com" data-pass="password123" onclick="useCreds(this)">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-primary-600/10 flex items-center justify-center">
                                <i class="fas fa-store text-primary-700"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-bold text-slate-800">Tenant</p>
                                    <span class="text-[11px] font-bold text-primary-700">Klik untuk isi</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span class="font-semibold">Username:</span> jokocyberlink@gmail.com
                                    <span class="mx-2 text-slate-300">•</span>
                                    <span class="font-semibold">Password:</span> password123
                                </p>
                            </div>
                        </div>
                    </button>
                </div>

                <p class="mt-3 text-[11px] text-slate-600 px-1">
                    Klik salah satu kartu untuk mengisi form otomatis. (Sekalian copy ke clipboard jika didukung
                    browser.)
                </p>
            </div>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('landing.index') }}#register"
                        class="font-bold text-primary-600 hover:text-primary-700">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <p class="mt-10 text-center text-[12px] text-slate-600 font-bold uppercase tracking-[0.2em]">
            &copy; 2025 SiLondry SaaS. All Rights Reserved.
        </p>
    </div>

    <script>
        async function useCreds(el) {
            const email = el.getAttribute('data-email') || '';
            const pass = el.getAttribute('data-pass') || '';

            // isi ke form
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            if (emailInput) emailInput.value = email;
            if (passInput) passInput.value = pass;

            // (opsional) copy ke clipboard: "email<TAB>password"
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(`${email}\t${pass}`);
                }
            } catch (e) {
                // jika gagal, tetap lanjut tanpa error
            }

            // toast kecil
            const toast = document.getElementById('credToast');
            if (toast) {
                toast.classList.remove('hidden');
                clearTimeout(window.__credToastTimer);
                window.__credToastTimer = setTimeout(() => toast.classList.add('hidden'), 1500);
            }

            // fokus ke password biar langsung tinggal klik login (atau ganti ke email kalau mau)
            if (passInput) passInput.focus();
        }
    </script>

</body>

</html>