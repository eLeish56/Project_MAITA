<x-layout>
    <x-slot:title>Detail Purchase Order</x-slot:title>

    <div class="mb-3">
        <a href="{{ route('reports.purchases.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Purchase Order</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nomor PO</th>
                            <td>: {{ $purchaseOrder->po_number }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal PO</th>
                            <td>: {{ $purchaseOrder->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: <span class="badge bg-success">{{ ucfirst($purchaseOrder->status) }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Supplier</th>
                            <td>: {{ $purchaseOrder->supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $purchaseOrder->supplier->address }}</td>
                        </tr>
                        <tr>
                            <th>Kontak</th>
                            <td>: {{ $purchaseOrder->supplier->phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Detail -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Items</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Jumlah Order</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td>{{ optional($item->item)->code ?? '-' }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>Rp {{ number_format($item->unit_price) }}</td>
                            <td>Rp {{ number_format($item->quantity * $item->unit_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th>Rp {{ number_format($purchaseOrder->total_amount) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Receipt Details -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Penerimaan Barang</h5>
        </div>
        <div class="card-body">
            @foreach($receiptDetails as $receipt)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">GR Number: {{ $receipt['gr_number'] }}</h6>
                    <span class="badge bg-success">Completed</span>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tanggal Terima:</strong><br>
                        {{ \Carbon\Carbon::parse($receipt['receipt_date'])->format('d/m/Y') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Diterima Oleh:</strong><br>
                        {{ $receipt['received_by'] }}
                    </div>
                    <div class="col-md-4">
                        <strong>Status Pemeriksaan:</strong><br>
                        <span class="badge bg-success">Verified</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Item</th>
                                <th>Jumlah Diterima</th>
                                <th>Batch Number</th>
                                <th>Tanggal Produksi</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Status Batch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipt['items'] as $item)
                            <tr>
                                <td>{{ $item['product_name'] }}</td>
                                <td class="text-center">{{ $item['quantity_received'] }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $item['batch_number'] }}</span>
                                </td>
                                <td>{{ isset($item['production_date']) ? \Carbon\Carbon::parse($item['production_date'])->format('d/m/Y') : '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['expiry_date'])->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @php
                                        $today = \Carbon\Carbon::now();
                                        $expiryDate = \Carbon\Carbon::parse($item['expiry_date']);
                                        $monthsUntilExpiry = $today->diffInMonths($expiryDate, false);
                                    @endphp
                                    
                                    @if($monthsUntilExpiry < 0)
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($monthsUntilExpiry <= 3)
                                        <span class="badge bg-warning">Near Expiry</span>
                                    @else
                                        <span class="badge bg-success">Good</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</x-layout>