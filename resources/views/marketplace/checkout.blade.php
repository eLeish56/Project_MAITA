@extends('layouts.marketplace')

@section('title', 'Checkout')
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      
      <div class="d-flex align-items-center mb-4">
        <h4 class="mb-0 flex-grow-1">
          <i class="fas fa-shopping-cart text-primary me-2"></i>
          Checkout
        </h4>
        <div class="text-muted">
          <i class="fas fa-lock me-1"></i> Transaksi Aman
        </div>
      </div>

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
      
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <ul class="mb-0">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form action="{{ route('marketplace.checkout.store') }}" method="POST">
        @csrf
        
        <!-- Informasi Pengambilan -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
              <i class="fas fa-user-circle text-primary me-2"></i>
              Informasi Pengambilan
            </h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">
                  <i class="fas fa-user me-1 text-muted"></i>
                  Nama Pengambil
                </label>
                <input type="text" 
                       name="pickup_name" 
                       class="form-control" 
                       required
                       value="{{ old('pickup_name', $buyerName ?? '') }}" 
                       placeholder="Masukkan nama lengkap">
              </div>
              <div class="col-md-6">
                <label class="form-label">
                  <i class="fas fa-phone me-1 text-muted"></i>
                  Nomor HP
                </label>
                <input type="text" 
                       name="phone" 
                       class="form-control" 
                       required
                       value="{{ old('phone') }}" 
                       placeholder="Contoh: 081234567890">
              </div>
              <div class="col-md-6">
                <label class="form-label">
                  <i class="fas fa-clock me-1 text-muted"></i>
                  Waktu Pengambilan
                </label>
                <select name="pickup_time" class="form-select" required>
                  <option value="">Pilih waktu pengambilan</option>
                  <option value="09:00" {{ old('pickup_time') == '09:00' ? 'selected' : '' }}>09:00 - 10:00 WIB</option>
                  <option value="10:00" {{ old('pickup_time') == '10:00' ? 'selected' : '' }}>10:00 - 11:00 WIB</option>
                  <option value="11:00" {{ old('pickup_time') == '11:00' ? 'selected' : '' }}>11:00 - 12:00 WIB</option>
                  <option value="13:00" {{ old('pickup_time') == '13:00' ? 'selected' : '' }}>13:00 - 14:00 WIB</option>
                  <option value="14:00" {{ old('pickup_time') == '14:00' ? 'selected' : '' }}>14:00 - 15:00 WIB</option>
                  <option value="15:00" {{ old('pickup_time') == '15:00' ? 'selected' : '' }}>15:00 - 16:00 WIB</option>
                  <option value="16:00" {{ old('pickup_time') == '16:00' ? 'selected' : '' }}>16:00 - 17:00 WIB</option>
                </select>
                <div class="form-text">Pilih estimasi waktu pengambilan</div>
              </div>

              <div class="col-12">
                <label class="form-label">
                  <i class="fas fa-sticky-note me-1 text-muted"></i>
                  Catatan Tambahan
                </label>
                <textarea name="notes" 
                          class="form-control" 
                          rows="2"
                          placeholder="Tambahkan catatan khusus jika ada (opsional)">{{ old('notes') }}</textarea>
                <div class="form-text">
                  <i class="fas fa-info-circle me-1"></i>
                  Mohon untuk datang tepat waktu sesuai jadwal yang dipilih
                </div>
              </div>

              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="confirmPickup" required>
                  <label class="form-check-label" for="confirmPickup">
                    Saya memastikan dapat mengambil pesanan sesuai dengan jadwal yang telah dipilih
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
              <i class="fas fa-shopping-basket text-primary me-2"></i>
              Detail Pesanan
            </h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px">Produk</th>
                    <th>Nama Produk</th>
                    <th style="width:100px" class="text-center">Jumlah</th>
                    <th style="width:130px" class="text-end">Harga</th>
                    <th style="width:150px" class="text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($rows as $r)
                    @php $it = $r['item']; @endphp
                    <tr>
                      <td>
                        <img src="{{ $it->photo_url ?? asset('images/no-image.png') }}"
                             class="rounded" 
                             style="width:60px;height:60px;object-fit:contain;background-color:#f8f9fa;padding:3px;">
                      </td>
                      <td>
                        <div class="fw-semibold">{{ $it->name }}</div>
                        <div class="text-muted small">Kode: {{ $it->code }}</div>
                      </td>
                      <td class="text-center">{{ (int)$r['qty'] }}</td>
                      <td class="text-end">Rp {{ number_format($r['price'], 0, ',', '.') }}</td>
                      <td class="text-end fw-semibold">Rp {{ number_format($r['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="table-light">
                  <tr>
                    <th colspan="4" class="text-end">Total Pembayaran</th>
                    <th class="text-end fs-5 text-primary">
                      Rp {{ number_format($total, 0, ',', '.') }}
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>

            <div class="alert alert-info mt-3 mb-0">
              <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fs-4 me-3"></i>
                <div>
                  <strong>Metode Pembayaran:</strong><br>
                  Pembayaran dilakukan secara tunai di tempat (COD/Pickup) saat pengambilan barang
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex gap-2 justify-content-between">
          <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Keranjang
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-check me-2"></i>
            Buat Pesanan
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@push('styles')
<style>
  .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
  }
  .card {
    border: none;
    border-radius: 0.5rem;
  }
  .card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
  .table > :not(:first-child) {
    border-top: none;
  }
</style>
@endpush

@endsection
