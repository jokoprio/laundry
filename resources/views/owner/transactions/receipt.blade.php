<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $transaction->id }}</title>
    <style>
        @media print {
            body {
                width: 58mm;
                /* standard thermal paper width */
                margin: 0;
                padding: 0;
                font-family: 'Courier New', Courier, monospace;
                /* monospaced for alignment */
                font-size: 10px;
            }

            .no-print {
                display: none;
            }
        }

        body {
            /* Screen preview styles */
            max-width: 300px;
            margin: 20px auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 50px;
            margin-bottom: 5px;
        }

        .business-name {
            font-weight: bold;
            font-size: 14px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .col {
            flex: 1;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .items-table th,
        .items-table td {
            text-align: left;
            vertical-align: top;
        }

        .items-table td.price {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        @if($tenant->logo)
            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="logo">
        @endif
        <div class="business-name">{{ $tenant->name }}</div>
        <div>{{ $tenant->address }}</div>
        <div>{{ $tenant->phone }}</div>
    </div>

    <div class="divider"></div>

    <div class="row">
        <div class="col">No: {{ substr($transaction->id, -8) }}</div> <!-- Show last 8 chars for brevity -->
        <div class="col text-right">{{ $transaction->created_at->format('d/m/y H:i') }}</div>
    </div>
    <div class="row">
        <div class="col">Kasir: {{ $transaction->created_by ?? 'Owner' }}</div> <!-- Or user name if available -->
    </div>
    <div class="row">
        <div class="col">Plg: {{ $transaction->customer->name ?? 'Umum' }}</div>
    </div>

    <div class="divider"></div>

    <table class="items-table">
        @foreach($transaction->items as $item)
            <tr>
                <td colspan="2">{{ $item->service->name }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty + 0 }} {{ $item->service->unit }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="price">{{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="row bold">
        <div class="col">Total</div>
        <div class="col text-right">{{ number_format($transaction->total_price, 0, ',', '.') }}</div>
    </div>
    @if($transaction->amount_paid > 0)
        <div class="row">
            <div class="col">Bayar</div>
            <div class="col text-right">{{ number_format($transaction->amount_paid, 0, ',', '.') }}</div>
        </div>
        <div class="row">
            <div class="col">Kembali</div>
            <div class="col text-right">
                {{ number_format(max(0, $transaction->amount_paid - $transaction->total_price), 0, ',', '.') }}
            </div>
        </div>
    @endif

    <div class="divider"></div>

    <div class="footer">
        @if($tenant->receipt_footer)
            <p>{{ $tenant->receipt_footer }}</p>
        @else
            <p>Terima kasih atas kunjungan Anda!</p>
        @endif
        <p class="small">Powered by SiLondry</p>
    </div>

    <!-- No Print UI -->
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px;">Cetak Lagi</button>
        <button onclick="window.close()" style="padding: 10px 20px;">Tutup</button>
    </div>

</body>

</html>