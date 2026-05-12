<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permintaan Pembelian {{ $pr->pr_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            background: white;
            margin: 0;
            padding: 0;
        }

        body {
            padding: 3cm 3cm 3cm 3cm;
        }

        .container {
            width: auto;
            margin: 0;
            padding: 0;
        }

        /* ===== HEADER/KOP SURAT ===== */
        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
            padding-bottom: 6px;
        }

        .header-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .header-address {
            font-size: 9px;
            line-height: 1.3;
            color: #333;
        }

        /* ===== INFO SECTION ===== */
        .info-section {
            margin-bottom: 8px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }

        .info-left {
            flex: 1;
        }

        .info-right {
            width: 180px;
            text-align: left;
        }

        .info-row {
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 70px;
        }

        /* ===== SUBTITLE ===== */
        .subtitle {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin: 8px 0;
        }

        /* ===== DOC TITLE ===== */
        .doc-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
        }

        /* ===== SUPPLIER INFO ===== */
        .supplier-info {
            margin-bottom: 10px;
            font-size: 10px;
            line-height: 1.5;
        }

        .supplier-label {
            font-weight: bold;
            display: inline-block;
            width: 60px;
            vertical-align: top;
        }

        .supplier-value {
            display: inline-block;
            width: calc(100% - 65px);
            vertical-align: top;
            word-wrap: break-word;
        }

        /* ===== ITEMS TABLE ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 12px 0;
        }

        .items-table th {
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            background-color: #fff;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 5px 3px;
        }

        .text-center {
            text-align: center;
        }

        /* ===== NOTES SECTION ===== */
        .notes-section {
            margin: 10px 0;
            font-size: 10px;
        }

        .notes-label {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .notes-content {
            min-height: 20px;
            line-height: 1.4;
        }

        /* ===== SIGNATURE SECTION ===== */
        .signature-section {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
        }

        .sig-date {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .sig-date-label {
            font-weight: bold;
        }

        .sig-column {
            width: auto;
            text-align: right;
        }

        .sig-label {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .sig-line {
            border-top: 1px solid #000;
            height: 35px;
            margin-bottom: 2px;
            width: 150px;
            margin-left: auto;
        }

        .sig-name {
            font-weight: bold;
            margin-bottom: 1px;
            min-height: 14px;
        }

        .sig-role {
            font-size: 9px;
            color: #666;
        }

        /* ===== PRINT OPTIMIZATION ===== */
        @media print {
            body {
                padding: 3cm 3cm 3cm 3cm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER KOP SURAT -->
        <div class="header">
            <div class="header-title">SMK MUHAMMADIYAH 1 PALEMBANG</div>
            <div class="header-address">
                Jl. Balayudha, RT.16/RW.4, Ario Kemuning, Kec. Kemuning<br>
                Kota Palembang, Sumatera Selatan 30128 | Telepon: (0711) 414662
            </div>
        </div>

        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-left"></div>
            <div class="info-right">
                <div class="info-row">
                    <span class="info-label">Referensi</span>: {{ $pr->pr_number }}
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>: {{ $pr->request_date->format('d/m/Y') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Halaman</span>: 1/1
                </div>
            </div>
        </div>

        <!-- SUBTITLE -->
        <div class="subtitle">TEACHING FACTORY</div>

        <!-- DOCUMENT TITLE -->
        <div class="doc-title">Permintaan Pembelian</div>

        <!-- SUPPLIER INFO -->
        <div class="supplier-info">
            <div>
                <span class="supplier-label">Kepada:</span>
                <span class="supplier-value">{{ $pr->supplier->name }}</span>
            </div>
            <div>
                <span class="supplier-label"></span>
                <span class="supplier-value">{{ $pr->supplier->address ?? '-' }}</span>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%">No</th>
                    <th style="width: 50%">Barang/Produk</th>
                    <th style="width: 17%">Kuantitas</th>
                    <th style="width: 25%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pr->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada item</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- NOTES SECTION -->
        @if($pr->description || $pr->approval_notes)
        <div class="notes-section">
            <div class="notes-label">Catatan/Keterangan:</div>
            <div class="notes-content">{{ $pr->description ?? $pr->approval_notes ?? '-' }}</div>
        </div>
        @endif

        <!-- SIGNATURE SECTION -->
        @php
            $manager = $pr->approver ?? \App\Models\User::where('role', 'supervisor')->first();
        @endphp
        <div class="signature-section">
            <div class="sig-date">
                <div class="sig-date-label">Palembang, {{ $pr->request_date->format('d M Y') }}</div>
            </div>
            <div class="sig-column">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $manager ? $manager->name : '___________________' }}</div>
                <div class="sig-role">Manager Operasional</div>
            </div>
        </div>
    </div>
</body>
</html>