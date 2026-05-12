@extends('layouts.marketplace')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    .status-badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }
    .table th {
        white-space: nowrap;
        background-color: #f8f9fa;
    }
    .order-card {
        transition: transform 0.2s;
    }
    .order-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-file-invoice text-primary me-2"></i>
                        Pesanan Saya
                    </h4>
                    <p class="text-muted mb-0">
                        Daftar pesanan yang telah Anda buat
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('marketplace.cart') }}" class="btn btn-light">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Keranjang
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
                        <i class="fas fa-store me-2"></i>
                        Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-shopping-bag fa-3x"></i>
                        </div>
                        <h5 class="mb-3">Belum Ada Pesanan</h5>
                        <p class="text-muted mb-4">
                            Anda belum memiliki pesanan. Mulai berbelanja sekarang!
                        </p>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
                            <i class="fas fa-store me-2"></i>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @else
                <!-- Orders List -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0" style="width:50px;">#</th>
                                        <th class="border-0">Kode Pesanan</th>
                                        <th class="border-0">Tanggal</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Total</th>
                                        <th class="border-0" style="width:100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $index => $order)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-medium">{{ $order->code }}</span>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if($order->status === 'pending')
                                                    <span class="badge rounded-pill bg-warning text-dark status-badge">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Menunggu Diambil
                                                    </span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge rounded-pill bg-success status-badge">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Selesai
                                                    </span>
                                                @else 
                                                    <span class="badge rounded-pill bg-secondary status-badge">
                                                        {{ $order->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">
                                                    {{ indo_currency($order->total_price) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm gap-1" role="group">
                                                    <a href="{{ route('marketplace.order.show', ['code' => $order->code]) }}" 
                                                       class="btn btn-primary">
                                                        <i class="fas fa-eye me-1"></i>
                                                        Detail
                                                    </a>
                                                    @if($order->status === 'pending')
                                                        <button type="button" class="btn btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#cancelModal{{ $order->id }}"
                                                                title="Batalkan pesanan ini">
                                                            <i class="fas fa-times me-1"></i>
                                                            Batalkan
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Cancel Modals for each order -->
                @foreach($orders as $order)
                    @if($order->status === 'pending')
                    <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content border-danger">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Batalkan Pesanan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('marketplace.order.cancel') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_code" value="{{ $order->code }}">
                                    
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <p class="mb-2">
                                                <strong>Kode Pesanan:</strong> 
                                                <span class="text-primary">{{ $order->code }}</span>
                                            </p>
                                            <p class="mb-3 text-muted small">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Pesanan yang dibatalkan tidak dapat dikembalikan. Stok barang akan dikembalikan ke inventory.
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="reason{{ $order->id }}" class="form-label">
                                                <i class="fas fa-comment me-1"></i>
                                                Alasan Pembatalan
                                            </label>
                                            <textarea class="form-control" 
                                                      id="reason{{ $order->id }}" 
                                                      name="reason"
                                                      rows="3" 
                                                      placeholder="Jelaskan alasan mengapa Anda ingin membatalkan pesanan ini..."
                                                      required></textarea>
                                            <small class="text-muted d-block mt-1">
                                                Alasan akan membantu kami meningkatkan layanan. (Max 255 karakter)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>
                                            Jangan Batalkan
                                        </button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i>
                                            Ya, Batalkan Pesanan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif

        </div>
    </div>
</div>
@endsection