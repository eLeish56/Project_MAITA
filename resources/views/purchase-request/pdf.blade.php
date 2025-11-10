<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Request {{ $pr->pr_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .items-table th {
            background-color: #f0f0f0;
        }
        .total-row td {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
            margin-right: 3%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .clear {
            clear: both;
        }
        .notes-section {
            margin-top: 30px;
            border: 1px solid #000;
            padding: 10px;
            min-height: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">TEACHING FACTORY</div>
        <div class="document-title">PURCHASE REQUEST</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="120">Nomor PR</td>
                <td width="10">:</td>
                <td>{{ $pr->pr_number }}</td>
                <td width="120">Tanggal Request</td>
                <td width="10">:</td>
                <td>{{ $pr->request_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>:</td>
                <td>{{ $pr->supplier->name }}</td>
                <td>Status</td>
                <td>:</td>
                <td>
                    @if($pr->status === 'pending')
                        Menunggu Persetujuan
                    @elseif($pr->status === 'approved')
                        Disetujui
                    @else
                        Ditolak
                    @endif
                </td>
            </tr>
            <tr>
                <td>Diminta Oleh</td>
                <td>:</td>
                <td>{{ $pr->requester->name }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Stok Saat Ini</th>
                <th>Jumlah Diminta</th>
                <th>Satuan</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pr->items as $index => $item)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: center">{{ $item->current_stock }}</td>
                <td style="text-align: center">{{ $item->quantity }}</td>
                <td style="text-align: center">{{ $item->unit }}</td>
                <td>{{ $item->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($pr->notes)
    <div class="notes-section">
        <strong>Catatan PR:</strong><br>
        {{ $pr->notes }}
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box" style="width: 100%; text-align: right; margin-right: 50px;">
            <div>Disetujui oleh,</div>
            <div class="signature-line" style="width: 200px; float: right;">
                {{ $pr->approver ? $pr->approver->name : '________________' }}<br>
                {{ $pr->approver ? $pr->approver->position : '________________' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>