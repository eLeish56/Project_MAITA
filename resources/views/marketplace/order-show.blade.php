@extends('layouts.marketplace')

@section('title', 'Detail Pesanan')
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
          <i class="fas fa-file-invoice text-primary me-2"></i>
          Detail Pesanan
        </h4>
        <div class="d-flex gap-2">
          <a href="{{ route('marketplace.order.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
          </a>
          <button id="btn-download" class="btn btn-primary">
            <i class="fas fa-download me-2"></i>
            Unduh Nota
          </button>
          <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="fas fa-print me-2"></i>
            Cetak
          </button>
        </div>
      </div>

      <div id="nota" class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h5 class="mb-0">
                <i class="fas fa-shopping-bag me-2"></i>
                Nota Pesanan
              </h5>
            </div>
            <div class="col-md-6 text-md-end">
              <h5 class="mb-0">{{ $order->code }}</h5>
              <small>
                <i class="fas fa-calendar-alt me-1"></i>
                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
              </small>
            </div>
          </div>
        </div>
        
        <div class="card-body">
          <!-- Informasi Pesanan -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-user text-primary fa-2x"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">Informasi Pengambil</h6>
                  <p class="mb-0">{{ $order->pickup_name }}</p>
                  <p class="mb-0 text-muted">
                    <i class="fas fa-phone-alt me-1"></i>
                    {{ $order->phone }}
                  </p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                  <div class="bg-success bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-money-bill text-success fa-2x"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">Metode Pembayaran</h6>
                  <p class="mb-0">COD/Pickup (Tunai di tempat)</p>
                </div>
              </div>
            </div>

            @if($order->notes)
            <div class="col-12">
              <div class="alert alert-light border mb-0">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-muted me-2"></i>
                  </div>
                  <div>
                    <strong>Catatan:</strong><br>
                    {{ $order->notes }}
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>

      <hr>

                <!-- Daftar Item -->
          <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th class="text-center" style="width: 50px">No</th>
                  <th>Item</th>
                  <th class="text-end" style="width: 150px">Harga</th>
                  <th class="text-center" style="width: 100px">Jumlah</th>
                  <th class="text-end" style="width: 150px">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rows as $i => $item)
                <tr>
                  <td class="text-center">{{ $i + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                           style="width: 48px; height: 48px">
                        <i class="fas fa-box text-muted"></i>
                      </div>
                      <div>
                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                        <small class="text-muted">{{ $item['code'] }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="text-end">{{ indo_currency($item['price']) }}</td>
                  <td class="text-center">
                    <span class="badge bg-primary rounded-pill">
                      {{ $item['qty'] }}
                    </span>
                  </td>
                  <td class="text-end fw-bold">{{ indo_currency($item['subtotal']) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="4" class="fw-bold">Total Pembayaran</td>
                  <td class="text-end">
                    <h5 class="mb-0 fw-bold text-primary">{{ indo_currency($total) }}</h5>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

        </div> <!-- end card-body -->

        @if($order->status === 'pending_pickup')
        <div class="card-footer bg-light">
          <div class="alert alert-info mb-0">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-info-circle fa-2x me-3"></i>
              </div>
              <div>
                <h6 class="mb-1">Pembayaran COD/Pickup</h6>
                <p class="mb-0">Silakan lakukan pembayaran saat mengambil barang di lokasi Teaching Factory.</p>
              </div>
            </div>
          </div>
        </div>
        @endif

      </div> <!-- end card -->
    </div>
  </div>
</div>

@push('scripts')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
  document.getElementById('btn-download').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    
    html2canvas(document.getElementById('nota')).then(canvas => {
      const link = document.createElement('a');
      link.download = 'nota-{{ $order->code }}.png';
      link.href = canvas.toDataURL();
      link.click();
      
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-download me-2"></i>Unduh Nota';
    });
  });
</script>
@endpush

      <div class="alert alert-info mb-0">
        Tunjukkan nota ini saat pengambilan dan lakukan pembayaran <strong>tunai di tempat</strong>.
        Setelah dibayar, kasir akan menyelesaikan transaksi di sistem POS.
      </div>
    </div>
  </div>

</div>

{{-- html2canvas untuk unduh PNG --}}
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"
        integrity="sha256-1FfAC+zs7nH2yQQfGzF0uQjYj91h3HAG0OV9K8d3G3A=" crossorigin="anonymous"></script>
<script>
document.getElementById('btn-download')?.addEventListener('click', function () {
  const el = document.getElementById('nota');
  if (!el) return;
  html2canvas(el, {backgroundColor: '#ffffff', scale: 2}).then(canvas => {
    const a = document.createElement('a');
    a.download = 'nota-{{ $order->code }}.png';
    a.href = canvas.toDataURL('image/png');
    a.click();
  });
});
</script>

<style>
@media print { #btn-download, .btn-outline-secondary, .btn-outline-primary { display:none!important } }
</style>
@endsection
