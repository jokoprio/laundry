@extends('layouts.owner')

@section('title', 'Program Loyalitas')
@section('header', 'Program Loyalitas')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Settings Card -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 shadow-xl shadow-blue-200">
                <div
                    class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 text-white shadow-lg">
                    <i class="fas fa-gift text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Aturan Program</h3>
                <p class="text-blue-100 text-sm font-medium mb-8 leading-relaxed">Berikan reward pada pelanggan setia Anda
                    untuk meningkatkan retensi dan omzet.</p>
<<<<<<< HEAD

=======
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 border border-white/10">
                    <p class="text-[12px] font-black text-blue-200 uppercase tracking-[0.2em] mb-2">Kurs Konversi Saat Ini
                    </p>
                    <div class="text-2xl font-black text-white mb-2">IDR 10.000 = <span class="text-yellow-400">1
                            Poin</span></div>
                    <p class="text-xs text-blue-100/70 font-medium">Poin diberikan otomatis saat transaksi lunas.</p>
                </div>
            </div>

            <div class="bg-amber-50 rounded-[2.5rem] p-8 mt-6 border border-amber-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 mr-4">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h4 class="text-lg font-black text-amber-900 tracking-tight">Tips Loyalitas</h4>
                </div>
                <p class="text-sm text-amber-800/80 font-medium leading-relaxed">Gunakan poin sebagai insentif bagi
                    pelanggan untuk mencoba layanan baru atau meningkatkan frekuensi kunjungan.</p>
            </div>
        </div>

        <!-- Customer Points List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800">Poin Pelanggan</h3>
<<<<<<< HEAD
                        <p class="text-xs text-slate-600 mt-1">Daftar saldo poin yang dimiliki oleh tiap member</p>
=======
                        <div class="flex items-center mt-1">
                    <p class="text-xs text-slate-600">Daftar saldo poin yang dimiliki oleh tiap member</p>
                    <span class="mx-2 text-slate-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fas fa-building mr-1.5 opacity-50"></i>
                        Cabang: {{ session('active_branch_name', 'Semua (Pusat)') }}
                    </span>
                </div>
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
                    </div>
                </div>

                <div
                    class="overflow-x-auto flex-grow overflow-y-auto max-h-[600px] scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 z-10 bg-white shadow-sm shadow-slate-50">
                            <tr class="bg-slate-50/50">
                                <th scope="col"
                                    class="px-8 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                                    Pelanggan & Kontak
                                </th>
                                <th scope="col"
                                    class="px-6 py-5 text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                                    Saldo Poin
                                </th>
                                <th scope="col"
                                    class="px-8 py-5 text-right text-[12px] font-bold text-slate-500 uppercase tracking-[0.2em]">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all mr-4">
                                                <i class="fas fa-user-tag"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-extrabold text-slate-800">{{ $customer->name }}</div>
                                                <div class="text-[12px] text-slate-600 font-bold uppercase tracking-tighter">
                                                    {{ $customer->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div
                                            class="inline-flex items-center px-4 py-2 bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-2xl text-[13px] font-black uppercase tracking-tighter shadow-sm shadow-yellow-500/5">
                                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                                            {{ number_format($customer->points) }} <span
                                                class="ml-1 text-[9px] opacity-60">PTS</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <button onclick='openRedeemModal(@json($customer))'
                                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-exchange-alt mr-2"></i> Tukar Hadiah
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                                                <i class="fas fa-users text-4xl"></i>
                                            </div>
                                            <h4 class="text-lg font-bold text-slate-800">Pelanggan Kosong</h4>
                                            <p class="text-slate-600 text-sm mt-1">Belum ada pelanggan yang terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Redeem Modal -->
    <div id="redeemModal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('redeemModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
                <form action="{{ route('owner.loyalty.redeem') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_id" id="redeem_customer_id">

                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-yellow-600 shadow-lg shadow-yellow-500/10 border border-yellow-100">
                                <i class="fas fa-gift text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Tukar Poin</h3>
                            <p class="text-sm text-slate-600 font-medium tracking-tight">Reward untuk: <span
                                    id="customer_name_display" class="text-indigo-600 font-black"></span></p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-[12px] font-black text-slate-600 uppercase tracking-widest mb-2 px-1">Pilih
                                    Hadiah / Reward</label>
                                <select name="reward_name" id="reward_select"
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                                    onchange="updatePointsCost()">
                                    <option value="Discount 10k" data-cost="50">Diskon 10rb (50 pts)</option>
                                    <option value="Free Wash 5kg" data-cost="100">Cuci Gratis 5kg (100 pts)</option>
                                    <option value="Free Dry Clean" data-cost="150">Dry Clean Gratis (150 pts)</option>
                                </select>
                            </div>

                            <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                                <label
                                    class="block text-[12px] font-black text-indigo-400 uppercase tracking-widest mb-2 px-1">Biaya
                                    Poin Dibutuhkan</label>
                                <div class="flex items-center">
                                    <input type="number" name="points_to_redeem" id="points_input" readonly
                                        class="block w-full bg-transparent text-4xl font-black text-indigo-700 outline-none border-none pointer-events-none p-0">
                                    <span class="text-indigo-400 font-black text-xl italic opacity-50">PTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 flex flex-col gap-3 mt-4">
                        <button type="submit"
                            class="inline-flex justify-center items-center rounded-2xl px-6 py-5 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 active:scale-[0.98] transition-all uppercase tracking-widest">Konfirmasi
                            Penukaran</button>
                        <button type="button" onclick="document.getElementById('redeemModal').classList.add('hidden')"
                            class="inline-flex justify-center items-center rounded-2xl px-6 py-4 bg-slate-100 text-sm font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-tighter">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRedeemModal(customer) {
            document.getElementById('redeemModal').classList.remove('hidden');
            document.getElementById('redeem_customer_id').value = customer.id;
            document.getElementById('customer_name_display').innerText = customer.name;
            updatePointsCost();
        }

        function updatePointsCost() {
            const select = document.getElementById('reward_select');
            const cost = select.options[select.selectedIndex].getAttribute('data-cost');
            document.getElementById('points_input').value = cost;
        }
    </script>

    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thumb-slate-200::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }

        .scrollbar-track-transparent::-webkit-scrollbar-track {
            background-color: transparent;
        }
    </style>
@endsection