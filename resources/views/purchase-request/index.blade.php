<x-layout>
    <x-slot:title>Daftar Permintaan Pembelian</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Daftar Permintaan Pembelian</h4>
                            <div>
                                @if(Auth::user()->role === 'supervisor')
                                    <a href="{{ route('purchase-requests.index', ['status' => 'pending']) }}" 
                                       class="btn {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }} me-2">
                                        <i class="fas fa-clock"></i> Menunggu Persetujuan 
                                        <span class="badge bg-white text-warning">
                                            {{ $requests->where('status', 'pending')->count() }}
                                        </span>
                                    </a>
                                @endif
                                <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Buat PR Baru
                                </a>
                            </div>
                        </div>
                        
                        <div class="btn-group mb-3">
                            <a href="{{ route('purchase-requests.index', ['status' => 'all']) }}" 
                               class="btn {{ $status === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Semua
                            </a>
                            <a href="{{ route('purchase-requests.index', ['status' => 'pending']) }}" 
                               class="btn {{ $status === 'pending' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Menunggu
                            </a>
                            <a href="{{ route('purchase-requests.index', ['status' => 'approved']) }}" 
                               class="btn {{ $status === 'approved' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Disetujui
                            </a>
                            <a href="{{ route('purchase-requests.index', ['status' => 'po_created']) }}" 
                               class="btn {{ $status === 'po_created' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                PO Dibuat
                            </a>
                            <a href="{{ route('purchase-requests.index', ['status' => 'rejected']) }}" 
                               class="btn {{ $status === 'rejected' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                Ditolak
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor PR</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                        <th>Diminta Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $pr)
                                        <tr>
                                            <td>{{ $pr->pr_number }}</td>
                                            <td>{{ $pr->request_date->format('d/m/Y') }}</td>
                                            <td>{{ $pr->supplier->name }}</td>
                                            <td>
                                                @if($pr->status === 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($pr->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($pr->status === 'po_created')
                                                    <span class="badge bg-info">PO Dibuat</span>
                                                @elseif($pr->status === 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($pr->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $pr->requester->name }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('purchase-requests.show', $pr->id) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    
                                                    @if($pr->status === 'approved' && !$pr->purchaseOrders()->exists())
                                                        <a href="{{ route('new-purchase-orders.create-direct', $pr->id) }}" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-file-alt"></i> Buat PO
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>