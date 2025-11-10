<x-layout>
  <x-slot:title>Laporan Purchase Order</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif

      <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports.purchases.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.purchases.index') }}" class="btn btn-secondary">Reset</a>
                        <a href="{{ route('reports.purchases.export', request()->all()) }}" class="btn btn-success">
                            Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total PO</h5>
                    <h3 class="card-text">{{ $purchaseOrders->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <h3 class="card-text">{{ number_format($totalItems) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Nilai PO</h5>
                    <h3 class="card-text">Rp {{ number_format($totalPurchases) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Summary -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Ringkasan per Supplier</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Jumlah PO</th>
                            <th>Total Items</th>
                            <th>Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplierSummary as $summary)
                        <tr>
                            <td>{{ $summary['supplier_name'] }}</td>
                            <td>{{ $summary['total_orders'] }}</td>
                            <td>{{ number_format($summary['total_items']) }}</td>
                            <td>Rp {{ number_format($summary['total_amount']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PO List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Purchase Order yang Selesai</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor PO</th>
                            <th>Tanggal PO</th>
                            <th>Supplier</th>
                            <th>Nomor GR</th>
                            <th>Tanggal GR</th>
                            <th>Total Items</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->created_at->format('d/m/Y') }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>
                                @foreach($po->goodsReceipts as $gr)
                                    {{ $gr->gr_number }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($po->goodsReceipts as $gr)
                                    {{ $gr->created_at->format('d/m/Y') }}<br>
                                @endforeach
                            </td>
                            <td>{{ $po->items->sum('quantity') }}</td>
                            <td>Rp {{ number_format($po->total_amount) }}</td>
                            <td>
                                <span class="badge bg-success">Completed</span>
                            </td>
                            <td>
                                <a href="{{ route('reports.purchases.show', $po->id) }}" 
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layout>