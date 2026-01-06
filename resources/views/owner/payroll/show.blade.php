@extends('layouts.owner')

@section('title', 'Slip Gaji')

@section('content')
    <div class="max-w-3xl mx-auto flex flex-col space-y-4">

        <!-- Action Buttons -->
        <div class="flex justify-between items-center print:hidden">
            <a href="{{ route('owner.payroll.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar</a>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                Cetak Slip
            </button>
        </div>

        <!-- Slip Container -->
        <div class="bg-white p-8 shadow-lg border border-gray-200" id="printableArea">
            <div class="text-center border-b pb-4 mb-4">
                <h1 class="text-2xl font-bold uppercase tracking-wide">{{ Auth::user()->tenant->name ?? 'Laundry Service' }}
                </h1>
                <p class="text-gray-500 text-sm">{{ Auth::user()->tenant->address ?? 'Address Line Here' }}</p>
                <h2 class="text-xl font-semibold mt-4">SLIP GAJI</h2>
                <p class="text-sm text-gray-600">Periode: {{ $payroll->period_start->format('d M Y') }} -
                    {{ $payroll->period_end->format('d M Y') }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="text-gray-500 block text-xs uppercase">Nama Karyawan</span>
                    <span class="font-bold text-lg">{{ $payroll->employee->name }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-500 block text-xs uppercase">Jabatan</span>
                    <span class="font-bold">{{ $payroll->employee->position }}</span>
                </div>
            </div>

            <table class="w-full mb-6">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="text-left py-2">Keterangan</th>
                        <th class="text-right py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class="py-2">Gaji Pokok</td>
                        <td class="text-right font-mono">IDR {{ number_format($payroll->base_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($payroll->bonus > 0)
                        <tr>
                            <td class="py-2 text-green-600">Bonus / Insentif</td>
                            <td class="text-right font-mono text-green-600">+ IDR
                                {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($payroll->deductions > 0)
                        <tr>
                            <td class="py-2 text-red-600">Potongan</td>
                            <td class="text-right font-mono text-red-600">- IDR
                                {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-800">
                        <td class="py-3 font-bold text-lg">GAJI BERSIH</td>
                        <td class="py-3 text-right font-bold text-lg font-mono">IDR
                            {{ number_format($payroll->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($payroll->details && count($payroll->details) > 0)
                <div class="bg-gray-50 p-4 rounded text-xs text-gray-600 mb-4">
                    <p class="font-bold mb-1">Rincian:</p>
                    <ul class="list-disc pl-4">
                        @foreach($payroll->details as $key => $value)
                            <li>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-12 flex justify-between text-center text-sm">
                <div>
                    <p class="mb-16">Disetujui Oleh,</p>
                    <p class="font-bold border-t border-gray-400 px-4 inline-block pt-1">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="mb-16">Diterima Oleh,</p>
                    <p class="font-bold border-t border-gray-400 px-4 inline-block pt-1">{{ $payroll->employee->name }}</p>
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-400">
                Dibuat pada {{ $payroll->created_at->format('d M Y H:i') }} | ID: {{ $payroll->id }}
            </div>
        </div>
    </div>
@endsection