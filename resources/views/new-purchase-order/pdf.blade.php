<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order #{{ $po->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            margin-bottom: 20px;
        }
        .company-info {
            float: left;
            width: 60%;
        }
        .document-info {
            float: right;
            width: 35%;
        }
        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            clear: both;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .items-table th {
            background-color: #f0f0f0;
        }
        .text-end {
            text-align: right;
        }
        .total-section {
            width: 40%;
            float: right;
            margin-top: 20px;
        }
        .signature-section {
            clear: both;
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            float: left;
            width: 45%;
            text-align: center;
            margin: 0 2.5%;
        }
        .terms {
            clear: both;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            @php
                $logoPath = public_path('images/logo.png');
                $logoBase64 = file_exists($logoPath) ? 
                    base64_encode(file_get_contents($logoPath)) : 
                    '';
            @endphp
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" height="50">
            @endif
            <h3>Teaching Factory</h3>
        </div>
        <div class="document-info">
            <table class="info-table">
                <tr>
                    <td>Referensi</td>
                    <td>: {{ $po->po_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $po->po_date ? $po->po_date->format('d/m/Y') : now()->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Halaman</td>
                    <td>: 1/1</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="document-title">Purchase Order</div>

    <table class="info-table">
        <tr>
            <td style="width: 50%">
                <strong>Order Ke:</strong><br>
                {{ $po->supplier->name }}<br>
                {{ $po->supplier->address }}<br>
                Indonesia<br>
                Tel: {{ $po->supplier->phone }}<br>
                Email: {{ $po->supplier->email }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kuantitas</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Pajak</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }} {{ $item->unit }}</td>
                    <td class="text-end">{{ number_format($item->unit_price) }}</td>
                    <td class="text-end">0%</td>
                    <td class="text-end">0%</td>
                    <td class="text-end">{{ number_format($item->quantity * $item->unit_price) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table class="info-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-end">Rp {{ number_format($po->total_amount) }}</td>
            </tr>
            <tr>
                <td>Diskon</td>
                <td class="text-end">Rp 0</td>
            </tr>
            <tr>
                <td>Total</td>
                <td class="text-end">Rp {{ number_format($po->total_amount) }}</td>
            </tr>
            <tr>
                <td>Pajak</td>
                <td class="text-end">Rp 0</td>
            </tr>
            <tr>
                <td>Pajak Included</td>
                <td class="text-end">Rp 0</td>
            </tr>
            <tr>
                <td><strong>Jumlah Tertagih:</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($po->total_amount) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <h4>Syarat & Ketentuan:</h4>
        <ol>
            <li>Harga tersebut di atas berlaku tetap sampai dengan pengiriman barang</li>
            <li>Bank Bersama: [Detail Bank]</li>
            <li>Pembayaran dilakukan setelah barang diterima dengan lengkap</li>
        </ol>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            Disetujui oleh,<br><br><br><br>
            _________________<br>
            {{ auth()->user()->name }}<br>
            {{ auth()->user()->position ?? 'Supervisor' }}
        </div>
        <div class="signature-box">
            Diterima oleh,<br><br><br><br>
            _________________<br>
            {{ $po->supplier->name }}
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>