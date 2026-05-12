@extends('layouts.app')

@section('title', 'Pesanan Online Pending')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2>
                <i class="fas fa-shopping-cart text-primary me-2"></i>
                Pesanan Online Pending
            </h2>
            <small class="text-muted">Kelola pesanan online yang menunggu pengambilan</small>
        </div>
        <div class="col-auto">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($orders->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Tidak ada pesanan online yang pending saat ini.
    </div>
    @else
    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-{{ $order->isExpired() ? 'danger' : 'warning' }} text-white py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 font-weight-bold">{{ $order->code }}</h6>
                            <small>{{ $order->pickup_name }}</small>
                        </div>
                        @if($order->isExpired())
                        <span class="badge bg-light text-danger">EXPIRED</span>
                        @else
                        <span class="badge bg-light text-dark">PENDING</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="mb-3">
                        <p class="mb-1">
                            <small class="text-muted">Nama Pemesan:</small>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-user me-1"></i>
                            <strong>{{ $order->user->name ?? '-' }}</strong>
                        </p>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <p class="mb-1">
                            <small class="text-muted">Kontak:</small>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone-alt me-1"></i>
                            {{ $order->phone }}
                        </p>
                    </div>

                    <!-- Total -->
                    <div class="mb-3">
                        <p class="mb-1">
                            <small class="text-muted">Total:</small>
                        </p>
                        <h5 class="mb-0 text-primary">{{ indo_currency($order->total_price) }}</h5>
                    </div>

                    <!-- Time Remaining -->
                    <div class="mb-3">
                        @if($order->expired_at)
                        <div class="alert alert-{{ $order->isExpired() ? 'danger' : 'info' }} py-2 px-3 mb-0">
                            <small>
                                <i class="fas fa-{{ $order->isExpired() ? 'times-circle' : 'clock' }} me-1"></i>
                                @php
                                    $expiredAt = $order->expired_at instanceof \DateTime 
                                        ? $order->expired_at 
                                        : \Carbon\Carbon::parse($order->expired_at);
                                    $diff = $expiredAt->diff(now());
                                @endphp
                                @if($order->isExpired())
                                    <strong>Kadaluarsa</strong>
                                @else
                                    @if($diff->d > 0)
                                        <strong>{{ $diff->d }}h {{ $diff->h }}m sisa</strong>
                                    @elseif($diff->h > 0)
                                        <strong>{{ $diff->h }}h {{ $diff->i }}m sisa</strong>
                                    @else
                                        <strong>{{ $diff->i }}m sisa</strong>
                                    @endif
                                @endif
                            </small>
                        </div>
                        @endif
                    </div>

                    <!-- Items Count -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-box me-1"></i>
                            {{ $order->items->count() }} item pesanan
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-grid gap-2">
                        <a href="{{ route('marketplace.order.show', $order->code) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>
                            Lihat Detail
                        </a>
                        @if($order->status === 'pending')
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#cancelModal{{ $order->id }}">
                            <i class="fas fa-ban me-1"></i>
                            Batalkan Pesanan
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cancel Modal -->
            @if($order->status === 'pending')
            <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Batalkan Pesanan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('marketplace.order.cancel') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_code" value="{{ $order->code }}">
                            
                            <div class="modal-body">
                                <p class="mb-3">Alasan pembatalan:</p>
                                <textarea class="form-control form-control-sm" name="reason" rows="2" 
                                          placeholder="Masukkan alasan pembatalan" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm">Ya, Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
