<div class="card card-product h-100 border-0 shadow-sm">
    {{-- Image Container --}}
    <div class="position-relative">
        <div class="product-img-container p-4 bg-light rounded-top">
            <img src="{{ $item->photo_url }}"
                 alt="{{ $item->name }}"
                 class="product-img"
                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'">
        </div>
        
        {{-- Stock Badge --}}
        <div class="badge-overlay">
            @if((int)$item->stock <= 5 && (int)$item->stock > 0)
                <span class="badge bg-warning text-dark rounded-pill shadow-sm">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Stok Terbatas
                </span>
            @elseif((int)$item->stock <= 0)
                <span class="badge bg-danger rounded-pill shadow-sm">
                    <i class="fas fa-times-circle me-1"></i>
                    Habis
                </span>
            @endif
        </div>

        {{-- Category Badge --}}
        @if($item->category)
            <div class="category-badge">
                <span class="badge bg-primary bg-opacity-75 rounded-pill shadow-sm">
                    <i class="fas fa-tag me-1"></i>
                    {{ $item->category->name }}
                </span>
            </div>
        @endif
    </div>

    <div class="card-body p-4">
        <h5 class="product-title mb-2">{{ $item->name }}</h5>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">
                <i class="fas fa-barcode text-primary me-1"></i>
                {{ $item->code }}
            </span>
            <span class="text-muted small">
                <i class="fas fa-box text-primary me-1"></i>
                Stok: {{ (int)$item->stock }}
            </span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="price text-success mb-0">
                {{ indo_currency($item->selling_price) }}
            </div>
            @if((int)$item->stock > 0)
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                    <i class="fas fa-check-circle me-1"></i>
                    Tersedia
                </span>
            @endif
        </div>

        <form action="{{ route('marketplace.cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="qty" value="1">
            <div class="d-flex gap-2">
                <a href="{{ route('marketplace.show', $item->id) }}" 
                   class="btn btn-sm btn-light flex-grow-1">
                    <i class="fas fa-eye me-1"></i>
                    Detail
                </a>
                <button type="submit" 
                        class="btn btn-sm btn-primary flex-grow-1" 
                        @if((int)$item->stock <= 0) disabled @endif>
                    <i class="fas fa-cart-plus me-1"></i>
                    Keranjang
                </button>
            </div>
        </form>
    </div>
</div>
