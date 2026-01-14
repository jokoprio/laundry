@extends('layouts.owner')

@section('title', 'Pemeliharaan Data')
@section('header', 'Pemeliharaan Data')

@section('content')
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100">
            <h3 class="text-xl font-extrabold text-slate-800">Reset Data Bisnis</h3>
            <p class="text-xs text-slate-600 mt-1">Bersihkan data spesifik untuk memulai ulang modul atau membersihkan
                database.</p>
        </div>

        @if($errors->any())
            <div class="px-8 mt-4">
                <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl text-sm font-bold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-times-circle mr-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('owner.maintenance.reset') }}" method="POST" id="resetForm">
            @csrf
            <!-- Hidden Confirmation Input -->
            <input type="hidden" name="confirmation" id="finalConfirmationInput">

            <div class="p-8">
                <!-- Warning Alert -->
                <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[2rem] flex items-start">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 shrink-0 mr-4">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-black uppercase tracking-tight">Peringatan Kritis!</h4>
                        <p class="text-red-700/80 text-sm font-medium mt-1 leading-relaxed">
                            Tindakan ini bersifat <strong>permanen</strong> dan tidak dapat dibatalkan. Data yang telah
                            dihapus tidak dapat dikembalikan. Pastikan Anda telah melakukan backup jika diperlukan.
                        </p>
                    </div>
                </div>

                <!-- Selection Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $categories = [
                            ['id' => 'branches', 'name' => 'Data Cabang', 'icon' => 'fa-store', 'desc' => 'Menghapus semua daftar cabang'],
                            ['id' => 'transactions', 'name' => 'Riwayat Transaksi', 'icon' => 'fa-cash-register', 'desc' => 'Menghapus semua invoice & item'],
                            ['id' => 'customers', 'name' => 'Pelanggan', 'icon' => 'fa-users', 'desc' => 'Menghapus database member & saldo'],
                            ['id' => 'inventory', 'name' => 'Stok Barang', 'icon' => 'fa-box-archive', 'desc' => 'Menghapus semua item inventaris'],
                            ['id' => 'stock_mutations', 'name' => 'Mutasi Stok', 'icon' => 'fa-exchange-alt', 'desc' => 'Menghapus riwayat pindah barang'],
                            ['id' => 'suppliers', 'name' => 'Supplier', 'icon' => 'fa-truck-field', 'desc' => 'Menghapus daftar mitra penyuplai'],
                            ['id' => 'purchases', 'name' => 'Pembelian', 'icon' => 'fa-shopping-cart', 'desc' => 'Menghapus riwayat belanja bahan'],
                            ['id' => 'employees', 'name' => 'Data Karyawan', 'icon' => 'fa-vcard', 'desc' => 'Menghapus data karyawan'],
                            ['id' => 'users', 'name' => 'Akun Login Staff', 'icon' => 'fa-user-lock', 'desc' => 'Menghapus akses login (kecuali anda)'],
                            ['id' => 'payroll', 'name' => 'Penggajian', 'icon' => 'fa-wallet', 'desc' => 'Menghapus riwayat bayar gaji'],
                            ['id' => 'expenses', 'name' => 'Pengeluaran', 'icon' => 'fa-file-invoice-dollar', 'desc' => 'Menghapus biaya operasional'],
                            ['id' => 'services', 'name' => 'Layanan & Harga', 'icon' => 'fa-concierge-bell', 'desc' => 'Menghapus daftar paket & harga'],
                            ['id' => 'membership_levels', 'name' => 'Level Member', 'icon' => 'fa-id-card-clip', 'desc' => 'Menghapus kategori diskon member'],
                            ['id' => 'loyalty', 'name' => 'Loyalty Program', 'icon' => 'fa-gift', 'desc' => 'Reset poin pelanggan ke 0'],
                        ];
                    @endphp

                    @foreach($categories as $cat)
                        <label class="relative group cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat['id'] }}" class="peer sr-only">
                            <div
                                class="p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:bg-white group-hover:shadow-lg group-hover:shadow-slate-200 group-hover:-translate-y-1">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-10 h-10 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 peer-checked:text-blue-600 transition-colors">
                                        <i class="fas {{ $cat['icon'] }}"></i>
                                    </div>
                                    <span class="ml-3 font-black text-slate-800 tracking-tight">{{ $cat['name'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-bold leading-snug uppercase tracking-tighter">
                                    {{ $cat['desc'] }}
                                </p>
                            </div>
                            <div class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition-opacity">
                                <div
                                    class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-[10px]">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Global Actions -->
                <div class="mt-8 flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="flex items-center">
                        <input type="checkbox" id="selectAll"
                            class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="selectAll" class="ml-3 font-bold text-slate-700">Pilih Semua Kategori</label>
                    </div>
                    <button type="button" onclick="showConfirmation()"
                        class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-500/20 active:scale-95 transition-all">
                        Reset Data Terpilih
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div
            class="relative mx-auto border w-full max-w-md shadow-2xl rounded-[2.5rem] bg-white overflow-hidden border-slate-100">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-red-600 shadow-lg shadow-red-500/10">
                        <i class="fas fa-shield-virus text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Konfirmasi Akhir</h3>
                    <p class="text-sm text-slate-600 font-medium">Ketik <strong>REKONFIRMASI</strong> di bawah ini untuk
                        melanjutkan penghapusan data secara permanen.</p>
                </div>

                <div class="space-y-4">
                    <input type="text" id="confirmationInput" placeholder="Ketik di sini..."
                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-center text-sm font-black placeholder-slate-300 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all outline-none">

                    <div class="flex gap-3">
                        <button type="button" onclick="submitReset()" id="finalBtn" disabled
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-red-600 text-sm font-black text-white hover:bg-red-700 shadow-xl shadow-red-600/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all uppercase tracking-tighter">
                            Hapus Sekarang
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="flex-1 inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('input[name="categories[]"]');
        const confirmationInput = document.getElementById('confirmationInput');
        const finalBtn = document.getElementById('finalBtn');

        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        confirmationInput.addEventListener('input', (e) => {
            finalBtn.disabled = e.target.value !== 'REKONFIRMASI';
        });

        function showConfirmation() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            if (selected.length === 0) {
                alert('Pilih setidaknya satu kategori data untuk direset.');
                return;
            }
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmationInput.value = '';
            finalBtn.disabled = true;
        }

        function submitReset() {
            const confirmationValue = confirmationInput.value;
            if (confirmationValue !== 'REKONFIRMASI') return;

            document.getElementById('finalConfirmationInput').value = confirmationValue;
            document.getElementById('resetForm').submit();
        }
    </script>
@endsection