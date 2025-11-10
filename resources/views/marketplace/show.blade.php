@extends('layouts.marketplace')

@section('title', $item->name)

@push('styles')
<style>
    .product-image {
        max-height: 400px;
        object-fit: contain;
        transition: transform 0.3s ease;
        border-radius: 1rem;
    }

    .product-image:hover {
        transform: scale(1.05);
        cursor: zoom-in;
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.9);
    }

    .badge-stock {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .btn-order {
        background-color: #00875a;
        border-color: #00875a;
        color: white;
    }

    .btn-order:hover {
        background-color: #006644;
        border-color: #006644;
        color: white;
    }

    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #0052cc;
    }

    .product-price {
        color: #00875a;
        font-size: 2rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="d-flex justify-content-end p-3">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" class="modal-image" alt="Product Image">
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-box text-primary me-2"></i>
                            Detail Produk
                        </h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('marketplace.index') }}">
                                        <i class="fas fa-store me-1"></i>
                                        Marketplace
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">{{ $item->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('marketplace.cart') }}" class="btn btn-light position-relative">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Keranjang
                            @if(session()->has('marketplace_cart') && count(session('marketplace_cart')) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(session('marketplace_cart')) }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
                            <i class="fas fa-store me-2"></i>
                            Lanjut Belanja
                        </a>
                    </div>
                </div>

                <!-- Product Detail -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="row g-0">
                        <!-- Image Section -->
                        <div class="col-lg-6">
                            <div class="p-4 h-100 d-flex align-items-center justify-content-center bg-light">
                                <div class="text-center">
                                    <img src="{{ $item->photo_url }}"
                                         alt="{{ $item->name }}"
                                         class="product-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal">
                                    @if($item->picture)
                                        <div class="mt-3 text-muted">
                                            <i class="fas fa-search-plus me-1"></i>
                                            Klik untuk memperbesar
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="col-lg-6">
                            <div class="p-4 p-lg-5">
                                <div class="mb-4">
                                    <h1 class="h3 fw-bold mb-2">{{ $item->name }}</h1>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-barcode text-primary me-2"></i>
                                        {{ $item->code }}
                                        @if($item->category)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            {{ $item->category->name }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="product-price mb-4">
                                    {{ indo_currency($item->selling_price) }}
                                </div>

                                @if(!empty($item->description))
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">Deskripsi</h6>
                                        <p class="text-muted mb-0">{{ $item->description }}</p>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    @if((int)$item->stock > 0)
                                        <span class="badge-stock bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Stok Tersedia: {{ (int)$item->stock }}
                                        </span>
                                    @else
                                        <span class="badge-stock bg-danger bg-opacity-10 text-danger">
                                            <i class="fas fa-times-circle me-2"></i>
                                            Stok Habis
                                        </span>
                                    @endif
                                </div>

                                <form action="{{ route('marketplace.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-medium">Jumlah</label>
                                            <input type="number"
                                                   id="qty"
                                                   name="qty"
                                                   class="form-control"
                                                   value="1"
                                                   min="1"
                                                   max="{{ max(1, (int)$item->stock) }}"
                                                   @if((int)$item->stock <= 0) disabled @endif>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-flex gap-2">
                                                <button type="submit"
                                                        class="btn btn-primary flex-fill"
                                                        @if((int)$item->stock <= 0) disabled @endif>
                                                    <i class="fas fa-cart-plus me-2"></i>
                                                    Tambah ke Keranjang
                                                </button>
                                                
                                                <button type="submit"
                                                        formaction="{{ route('marketplace.cart.add') }}?checkout=true"
                                                        class="btn btn-order flex-fill"
                                                        @if((int)$item->stock <= 0) disabled @endif>
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Pesan Sekarang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productImage = document.querySelector('.product-image');
        const modalImg = document.getElementById('modalImage');

        if (productImage && !productImage.src.includes('no-image.png')) {
            productImage.addEventListener('click', function() {
                modalImg.src = this.src;
            });
        }
    });
</script>
@endpush
