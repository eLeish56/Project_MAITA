<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - {{ $transaction->invoice }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            background-color: #fff;
        }

        .container {
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            background-color: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 3px;
        }

        .header h1 {
            font-size: 11px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }

        .header p {
            font-size: 8px;
            color: #000;
            margin: 1px 0;
            line-height: 1.2;
        }

        .header .address {
            font-size: 7px;
            line-height: 1.15;
            margin: 2px 0 0 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        .invoice-info {
            font-size: 9px;
            margin-bottom: 3px;
            line-height: 1.2;
        }

        .invoice-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }

        .invoice-info-row label {
            font-weight: bold;
            display: inline;
        }

        .invoice-info-row span {
            display: inline;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 3px 0;
            font-size: 8px;
        }

        .items-table thead {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .items-table th {
            padding: 2px 2px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            line-height: 1;
        }

        .items-table td {
            padding: 2px 2px;
            border-bottom: none;
            font-size: 8px;
            line-height: 1.2;
        }

        .items-table th:nth-child(2),
        .items-table td:nth-child(2),
        .items-table th:nth-child(3),
        .items-table td:nth-child(3),
        .items-table th:nth-child(4),
        .items-table td:nth-child(4) {
            text-align: right;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 1px solid #000;
        }

        .summary {
            margin: 3px 0;
            font-size: 9px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: none;
        }

        .summary-row.divider-top {
            border-top: 1px dashed #000;
            padding-top: 2px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 10px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 0;
        }

        .summary-label {
            flex: 1;
        }

        .summary-value {
            text-align: right;
            min-width: 60px;
        }

        .payment-info {
            margin-top: 3px;
            font-size: 8px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            line-height: 1.2;
        }

        .payment-row label {
            font-weight: bold;
        }

        .payment-row span {
            text-align: right;
            flex: 1;
            text-align: right;
            margin-left: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 5px;
            padding-top: 3px;
            border-top: 1px solid #000;
            font-size: 8px;
            color: #000;
        }

        .footer p {
            margin: 1px 0;
            line-height: 1.2;
        }

        .thank-you {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin: 3px 0;
        }

        .customer-info {
            font-size: 8px;
            margin-bottom: 3px;
            padding: 2px;
            line-height: 1.2;
        }

        .customer-info-row {
            margin-bottom: 1px;
        }

        .customer-info label {
            font-weight: bold;
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
                width: 80mm;
            }
            .container {
                padding: 0;
                width: 80mm;
                margin: 0;
            }
            @page {
                size: 80mm auto;
                margin: 0;
                padding: 0;
            }
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>TEACHING FACTORY</h1>
            <h1>SMK MUHAMMADIYAH 1 PALEMBANG</h1>
            <div class="address">Jl. Balayudha, RT.16/RW.4, Ario Kemuning,<br>Kec. Kemuning, Kota Palembang,<br>Sumatera Selatan 30128</div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Receipt Number -->
        <div style="text-align: center; margin-bottom: 2px;">
            <div style="font-weight: bold; font-size: 9px;">NOTA PENJUALAN</div>
            <div style="font-size: 8px;">No: {{ $transaction->invoice }}</div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-info-row">
                <label>Tanggal:</label>
                <span>{{ $transaction->created_at->locale('id')->isoFormat('DD/MM/YY') }}</span>
            </div>
            <div class="invoice-info-row">
                <label>Jam:</label>
                <span>{{ $transaction->created_at->format('H:i') }}</span>
            </div>
            <div class="invoice-info-row">
                <label>Kasir:</label>
                <span>{{ substr($transaction->user->name, 0, 15) }}</span>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Customer Info (if available) -->
        @if($transaction->customer)
            <div class="customer-info">
                <div class="customer-info-row">
                    <label>Pelanggan:</label>
                    <span>{{ $transaction->customer->name }}</span>
                </div>
            </div>
        @endif

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Barang</th>
                    <th style="width: 12%;">Hrg</th>
                    <th style="width: 12%;">Qty</th>
                    <th style="width: 31%;">Jml</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>{{ substr($detail['item_name'], 0, 20) }}</td>
                        <td>{{ number_format($detail['price']/1000, 0) }}K</td>
                        <td style="text-align: center;">{{ $detail['qty'] }}</td>
                        <td>{{ number_format($detail['subtotal']/1000, 0) }}K</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            @if($transaction->discount > 0)
                <div class="summary-row">
                    <div class="summary-label">Diskon</div>
                    <div class="summary-value">-{{ number_format($transaction->discount/1000, 0) }}K</div>
                </div>
            @endif
            
            <div class="summary-row total">
                <div class="summary-label">TOTAL</div>
                <div class="summary-value">{{ number_format($transaction->total/1000, 0) }}K</div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="payment-row">
                <label>Metode:</label>
                <span>{{ substr($transaction->paymentMethod ? $transaction->paymentMethod->name : '-', 0, 10) }}</span>
            </div>
            
            @if($transaction->amount > 0)
                <div class="payment-row">
                    <label>Uang:</label>
                    <span>{{ number_format($transaction->amount/1000, 0) }}K</span>
                </div>
            @endif
            
            @if($transaction->change > 0)
                <div class="payment-row">
                    <label>Kmb:</label>
                    <span>{{ number_format($transaction->change/1000, 0) }}K</span>
                </div>
            @endif
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Terima Kasih!</div>
            <p>Barang tidak diterima kembali</p>
            <p style="font-size: 7px;">{{ now()->locale('id')->isoFormat('DD/MM/YY HH:mm') }}</p>
        </div>
    </div>
</body>
</html>
