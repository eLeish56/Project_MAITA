@extends('layouts.marketplace')

@section('title', 'Home')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border-radius: 1rem;
        overflow: hidden;
    }

    .hero-image {
        max-width: 100%;
        height: auto;
        filter: drop-shadow(0 0 1rem rgba(0,0,0,.2));
    }

    .card-product {
        height: 100%;
        transition: all 0.3s ease;
        border: none;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .card-product:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem rgba(0,0,0,.15) !important;
    }

    .product-img-container {
        aspect-ratio: 1;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem 1rem 0 0;
    }

    .product-img {
        object-fit: contain;
        width: 80%;
        height: 80%;
        transition: transform 0.5s ease;
    }

    .card-product:hover .product-img {
        transform: scale(1.1);
    }

    .badge-overlay {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 2;
    }

    .category-badge {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        z-index: 2;
    }

    .price {
        color: #198754;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .product-title {
        font-weight: 500;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #2d3436;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .text-primary {
        color: #0052cc !important;
    }
    
    .bg-primary {
        background-color: #0052cc !important;
    }
    
    .btn-primary {
        background-color: #0052cc;
        border-color: #0052cc;
    }
    
    .btn-primary:hover {
        background-color: #0047b3;
        border-color: #0047b3;
    }
    
    .text-success {
        color: #00875a !important;
    }
    }

    .filter-button {
        transition: all 0.2s;
    }

    .filter-button:hover {
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-store text-primary me-2"></i>
                            Teaching Factory Marketplace
                        </h4>
                        <p class="text-muted mb-0">
                            Pilih produk berkualitas dengan harga terbaik
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ route('marketplace.order.index') }}" class="btn btn-light">
                                <i class="fas fa-file-invoice me-2"></i>
                                Pesanan Saya
                            </a>
                        @endauth
                        <a href="{{ route('marketplace.cart') }}" class="btn btn-primary position-relative">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Keranjang
                            @if(($cartCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="hero-section mb-0">
                            <div class="container py-5">
                                <div class="row align-items-center">
                                    <div class="col-lg-7">
                                        <h1 class="display-5 fw-bold mb-4">Belanja Produk Unggulan</h1>
                                        <p class="lead mb-4">
                                            Pilih produk berkualitas dengan harga terbaik, pesan dan ambil langsung di lokasi kami.
                                        </p>
                                        <div class="d-flex flex-wrap gap-3">
                                            <a href="#products" class="btn btn-outline-light btn-lg">
                                                <i class="fas fa-th-large me-2"></i>
                                                Jelajahi Produk
                                            </a>
                                            <a href="{{ route('marketplace.cart') }}" class="btn btn-light btn-lg">
                                                <i class="fas fa-shopping-cart me-2"></i>
                                                Lihat Keranjang
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 d-none d-lg-block text-end">
                                        <img src="{{ asset('images/hero-market.png') }}"
                                            alt="Marketplace Illustration"
                                            class="hero-image"
                                            onerror="this.style.display='none'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <!-- Search Form -->
                        <form action="{{ route('marketplace.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" 
                                       name="search" 
                                       class="form-control form-control-lg" 
                                       placeholder="Cari produk..." 
                                       value="{{ request('search') }}"
                                       aria-label="Cari produk">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-2"></i>
                                    Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @if(request('search'))
                <!-- Search Results Info -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-search me-2"></i>
                    Hasil pencarian untuk: <strong>{{ request('search') }}</strong>
                    <span class="ms-2">({{ $items->total() }} produk ditemukan)</span>
                </div>
                @endif

        <!-- Products Grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="products">
            @if($items->count() === 0)
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h3 class="h4 mb-3">Belum Ada Produk</h3>
                        <p class="text-muted">
                            Mohon maaf, saat ini belum ada produk yang tersedia.
                            Silakan cek kembali dalam beberapa waktu.
                        </p>
                    </div>
                </div>
            @else
                @foreach($items as $item)
                    <div class="col">
                        @include('marketplace.partials.product-card', ['item' => $item])
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>

@endsection