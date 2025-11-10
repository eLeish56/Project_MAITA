@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Batch</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daftar Batch Aktif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="batchTable">
                    <thead>
                        <tr>
                            <th>Kode Batch</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Tersisa</th>
                            <th>Tgl Kadaluarsa</th>
                            <th>Status</th>
                            <th>No GR</th>
                            <th>Tgl Terima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                        <tr class="{{ $batch->expiry_status === 'warning' ? 'table-warning' : ($batch->expiry_status === 'expired' ? 'table-danger' : '') }}">
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ $batch->product_name }}</td>
                            <td>{{ number_format($batch->remaining_quantity, 0) }} {{ $batch->unit }}</td>
                            <td>{{ $batch->expiry_date ? $batch->expiry_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($batch->expiry_status === 'expired')
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                @elseif($batch->expiry_status === 'warning')
                                    <span class="badge bg-warning text-dark">Hampir Kadaluarsa</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td>{{ $batch->goodsReceipt->gr_number }}</td>
                            <td>{{ $batch->goodsReceipt->receipt_date->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#batchTable').DataTable({
        order: [[3, 'asc']], // Sort by expiry date
    });
});
</script>
@endpush
@endsection