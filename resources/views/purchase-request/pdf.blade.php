<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Request {{ $pr->pr_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 40mm;
        }

        html, body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            width: 100%;
        }

        /* Kop Surat */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            width: 100%;
        }

        .header-title {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .header-subtitle {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header-address {
            font-size: 8px;
            line-height: 1.3;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
            width: 100%;
        }

        /* Info Tabel */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .label {
            width: 100px;
            font-weight: bold;
            padding-right: 8px;
        }

        .colon {
            width: 8px;
            padding: 0 5px;
            text-align: center;
        }

        .value {
            border-bottom: 1px dotted #000;
            padding-right: 5px;
        }

        /* Divider */
        .divider {
            border-top: 1px solid #000;
            margin: 15px 0;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
            font-size: 10px;
        }

        .items-table thead {
            background: white;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .items-table th {
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 4px 3px;
            border-bottom: 1px solid #000;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #000;
        }

        .text-center {
            text-align: center;
        }

        /* Notes */
        .notes {
            margin: 15px 0;
            font-size: 10px;
            width: 100%;
        }

        .notes-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .notes-box {
            border: 1px solid #000;
            padding: 6px;
            min-height: 25px;
        }

        /* Signature */
        .signature {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            width: 100%;
        }

        .sig-label {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sig-line {
            border-top: 1px solid #000;
            height: 20px;
            width: 150px;
            margin: 0 auto 3px auto;
        }

        .sig-name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-title">TEACHING FACTORY</div>
            <div class="header-subtitle">SMK Muhammadiyah 1 Palembang</div>
            <div class="header-address">
                Jl. Balayudha, RT.16/RW.4, Ario Kemuning, Kec. Kemuning<br>
                Kota Palembang, Sumatera Selatan 30128 | Telepon: (0711) 414662
            </div>
        </div>

        <!-- Judul -->
        <div class="doc-title">PERMINTAAN PEMBELIAN</div>

        <!-- Info -->
        <table class="info-table">
            <tr>
                <td class="label">Nomor Dokumen</td>
                <td class="colon">:</td>
                <td class="value">{{ $pr->pr_number }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="colon">:</td>
                <td class="value">{{ $pr->request_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nama Supplier</td>
                <td class="colon">:</td>
                <td class="value">{{ $pr->supplier->name }}</td>
            </tr>
            <tr>
                <td class="label">Diminta Oleh</td>
                <td class="colon">:</td>
                <td class="value">{{ $pr->requester->name }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Tabel Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 35%;">Nama Barang/Produk</th>
                    <th style="width: 12%;">Stok Saat Ini</th>
                    <th style="width: 12%;">Jumlah Diminta</th>
                    <th style="width: 10%;">Satuan</th>
                    <th style="width: 26%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pr->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->current_stock ?? 0 }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Catatan -->
        @if($pr->notes)
        <div class="notes">
            <div class="notes-label">Catatan/Keterangan:</div>
            <div class="notes-box">{{ $pr->notes }}</div>
        </div>
        @endif

        <!-- Signature -->
        <div class="signature">
            <div class="sig-label">Disetujui Oleh</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $pr->approver ? $pr->approver->name : '_______________' }}</div>
        </div>
    </div>
</body>
</html>