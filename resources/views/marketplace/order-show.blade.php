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
              <br>
              <!-- Status Badge -->
              @if($order->status === 'pending')
                <span class="badge bg-warning text-dark mt-2">
                  <i class="fas fa-hourglass-half me-1"></i> Menunggu Pengambilan
                </span>
              @elseif($order->status === 'picked')
                <span class="badge bg-success mt-2">
                  <i class="fas fa-check-circle me-1"></i> Sudah Diambil
                </span>
              @elseif($order->status === 'expired')
                <span class="badge bg-danger mt-2">
                  <i class="fas fa-times-circle me-1"></i> Kadaluarsa
                </span>
              @elseif($order->status === 'cancelled')
                <span class="badge bg-secondary mt-2">
                  <i class="fas fa-ban me-1"></i> Dibatalkan
                </span>
              @endif
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

        <!-- Status & Actions Section -->
        @if($order->status === 'pending')
        <div class="card-footer bg-light py-3">
          <div class="row align-items-center">
            <div class="col-md-6">
              @if($order->expired_at)
                @if($order->isExpired())
                  <!-- ORDER SUDAH EXPIRED -->
                  <div class="alert alert-danger mb-0">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                      <div>
                        <h6 class="mb-1">⚠️ Pesanan Kadaluarsa</h6>
                        <small>
                          <strong>Waktu pengambilan sudah berakhir!</strong><br>
                          Pesanan akan dibatalkan otomatis dan stok dikembalikan.<br>
                          Expired: {{ \Carbon\Carbon::parse($order->expired_at)->format('d/m/Y H:i') }}
                        </small>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- COUNTDOWN MASIH BERLAKU -->
                  <div class="alert mb-0" id="countdown-alert" style="background-color: #d1ecf1;">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-clock text-info fa-lg me-3" id="countdown-icon"></i>
                      <div>
                        <h6 class="mb-1" id="countdown-title">Sisa Waktu Pengambilan</h6>
                        <small id="countdown-display">
                          @php
                            $remaining = $order->expired_at->diffInMinutes(now());
                            $hours = intval($remaining / 60);
                            $minutes = $remaining % 60;
                          @endphp
                          @if($hours > 0)
                            <strong>{{ $hours }}h {{ $minutes }}m</strong>
                          @else
                            <strong>{{ $minutes }}m</strong>
                          @endif
                        </small>
                      </div>
                    </div>
                  </div>
                @endif
              @endif
            </div>
            <div class="col-md-6 text-md-end">
              <p class="mb-2"><small class="text-muted">Pembayaran COD saat pengambilan</small></p>
              <button onclick="window.print()" class="btn btn-sm btn-outline-primary me-2">
                <i class="fas fa-print me-1"></i> Cetak
              </button>
              @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'supervisor', 'cashier']))
                @if($order->isExpired())
                  <button class="btn btn-sm btn-danger" disabled title="Pesanan sudah expired">
                    <i class="fas fa-ban me-1"></i> Tidak Bisa Diproses (Expired)
                  </button>
                @else
                  <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="fas fa-ban me-1"></i> Batalkan
                  </button>
                @endif
              @endif
            </div>
          </div>
        </div>
        @elseif($order->status === 'picked')
        <div class="card-footer bg-light">
          <div class="alert alert-success mb-0">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle fa-2x me-3"></i>
              <div>
                <h6 class="mb-1">Pesanan Sudah Diambil</h6>
                <small>Diambil pada {{ \Carbon\Carbon::parse($order->picked_up_at)->format('d/m/Y H:i') }}</small>
              </div>
            </div>
          </div>
        </div>
        @elseif($order->status === 'cancelled')
        <div class="card-footer bg-light">
          <div class="alert alert-danger mb-0">
            <div class="d-flex align-items-center">
              <i class="fas fa-ban fa-2x me-3"></i>
              <div>
                <h6 class="mb-1">Pesanan Dibatalkan</h6>
                <small>
                  Alasan: {{ $order->cancellation_reason ?? 'Tidak ada alasan' }}<br>
                  @if($order->canceled_by)
                    Dibatalkan oleh: {{ $order->cancelledBy->name ?? 'Sistem' }}
                  @else
                    Dibatalkan otomatis karena melewati waktu pengambilan 24 jam
                  @endif
                </small>
              </div>
            </div>
          </div>
        </div>
        @elseif($order->status === 'expired')
        <div class="card-footer bg-light">
          <div class="alert alert-warning mb-0">
            <div class="d-flex align-items-center">
              <i class="fas fa-hourglass-end fa-2x me-3"></i>
              <div>
                <h6 class="mb-1">Pesanan Kadaluarsa</h6>
                <small>Waktu pengambilan 24 jam telah berakhir.</small>
              </div>
            </div>
          </div>
        </div>
        @endif

      </div> <!-- end card -->
    </div>
  </div>
</div>

@if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'supervisor'))
<!-- Modal Batalkan Pesanan -->
<div class="modal fade" id="cancelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-ban me-2"></i>Batalkan Pesanan
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('marketplace.order.cancel') }}" method="POST">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="order_code" value="{{ $order->code }}">
          
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Perhatian!</strong> Pembatalan pesanan akan mengembalikan stok barang ke inventory.
          </div>

          <div class="mb-3">
            <label for="reason" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="reason" name="reason" rows="3" 
                      placeholder="Contoh: Stok tidak tersedia, Permintaan customer, dll" required></textarea>
            <small class="text-muted">Alasan ini akan dicatat dalam sistem</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-ban me-2"></i>Ya, Batalkan Pesanan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@push('scripts')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
  // Real-time countdown timer untuk marketplace order
  const expiredAtStr = '{{ $order->expired_at ? $order->expired_at->toIso8601String() : null }}';
  
  if (expiredAtStr) {
    function updateCountdown() {
      const expiredAt = new Date(expiredAtStr);
      const now = new Date();
      const diff = expiredAt - now;
      
      if (diff <= 0) {
        // Order sudah expired
        document.getElementById('countdown-alert').style.backgroundColor = '#f8d7da';
        document.getElementById('countdown-icon').className = 'fas fa-times-circle text-danger fa-lg me-3';
        document.getElementById('countdown-title').innerHTML = '<span class="text-danger">Pesanan Kadaluarsa</span>';
        document.getElementById('countdown-display').innerHTML = '<small>Waktu pengambilan sudah berakhir</small>';
        
        // Refresh halaman setelah 5 detik untuk update status
        setTimeout(() => window.location.reload(), 5000);
        return;
      }
      
      const hours = Math.floor(diff / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);
      
      let display = '';
      if (hours > 0) {
        display = `<strong>${hours}h ${minutes}m ${seconds}s</strong>`;
      } else {
        display = `<strong>${minutes}m ${seconds}s</strong>`;
      }
      
      // Ubah warna jika kurang dari 1 jam
      if (hours === 0 && minutes < 60) {
        document.getElementById('countdown-alert').style.backgroundColor = '#fff3cd';
        document.getElementById('countdown-icon').className = 'fas fa-exclamation-triangle text-warning fa-lg me-3';
      }
      
      document.getElementById('countdown-display').innerHTML = display;
    }
    
    // Update setiap detik
    setInterval(updateCountdown, 1000);
    // Initial update
    updateCountdown();
  }

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

<style>
@media print { #btn-download, .btn-outline-secondary, .btn-outline-primary { display:none!important } }
</style>
@endpush

@endsection
