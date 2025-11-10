@extends('layouts.app')

{{-- Gunakan Bootstrap untuk tampilan ini --}}
@section('use_bootstrap', true)

@section('title', 'Buat Goods Receipt')

@push('styles')
<style>
    .expiry-badge {
        font-size: 0.8em;
        padding: 2px 8px;
        border-radius: 12px;
        background-color: #e9ecef;
        display: inline-block;
        margin-left: 8px;
    }
    .card-item {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .form-label {
        font-weight: 500;
    }
    .validation-error {
        border-color: #dc3545;
    }
    .hidden {
        display: none;
    }
</style>
@endpush

@section('content')
    {{-- Baris judul dan tombol kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Buat Goods Receipt</h5>
        <a href="{{ route('new-purchase-orders.show', $po->id) }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mb-3">
        <strong>Error!</strong> Ada beberapa masalah dengan input Anda.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            {{-- Form pembuatan GR --}}
            <form action="{{ route('goods-receipts.store', $po->id) }}" method="POST" enctype="multipart/form-data" id="grForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="receipt_date" class="form-label">Tanggal Penerimaan</label>
                        <input type="date" name="receipt_date" id="receipt_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="receipt_document" class="form-label">Surat Penerimaan Barang</label>
                        <input type="file" name="receipt_document" id="receipt_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, JPEG, PNG (max 2MB)</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Catatan untuk seluruh penerimaan barang"></textarea>
                    </div>
                </div>

                <h6 class="mt-4 mb-3">Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-3">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Nama Barang</th>
                                <th style="width: 15%">Jumlah Dipesan</th>
                                <th style="width: 60%">Detail Penerimaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($po->items as $index => $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product_name }}</strong>
                                        <input type="hidden" name="items[{{ $index }}][product_name]" value="{{ $item->product_name }}">
                                        @if($item->item)
                                            <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->item->id }}">
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </td>
                                    <td>
                                        <div class="card card-item">
                                            <div class="card-body p-3">
                                                <div class="row g-3">
                                                    <!-- Jumlah dan Satuan -->
                                                    <div class="col-md-6">
                                                        <label class="form-label required">Jumlah Diterima</label>
                                                        <input type="number" 
                                                            name="items[{{ $index }}][quantity_received]" 
                                                            class="form-control quantity-input" 
                                                            required 
                                                            min="1"
                                                            max="{{ $item->quantity }}"
                                                            placeholder="Jumlah"
                                                            data-original-qty="{{ $item->quantity }}"
                                                            value="{{ old('items.'.$index.'.quantity_received') }}">
                                                        <small class="text-muted">Maksimal: {{ $item->quantity }}</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Satuan</label>
                                                        <input type="text" 
                                                            name="items[{{ $index }}][unit]" 
                                                            class="form-control" 
                                                            value="{{ $item->unit }}"
                                                            placeholder="Satuan"
                                                            readonly>
                                                    </div>

                                                    <!-- Batch dan Kadaluarsa -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Nomor Batch</label>
                                                        <input type="text" 
                                                            name="items[{{ $index }}][batch_number]" 
                                                            class="form-control"
                                                            value="{{ old('items.'.$index.'.batch_number') }}"
                                                            placeholder="Nomor Batch">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Tanggal Kadaluarsa (opsional)</label>
                                                            <input type="date" 
                                                                name="items[{{ $index }}][expiry_date]" 
                                                                class="form-control"
                                                                value="{{ old('items.'.$index.'.expiry_date') }}"
                                                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                                            <small class="text-muted">Kosongkan jika barang tidak memiliki kadaluarsa</small>
                                                        </div>
                                                    </div>

                                                    <!-- Catatan Item -->
                                                    <div class="col-12">
                                                        <label class="form-label">Catatan Item</label>
                                                        <textarea name="items[{{ $index }}][notes]" 
                                                            class="form-control" 
                                                            rows="2"
                                                            placeholder="Catatan khusus untuk item ini">{{ old('items.'.$index.'.notes') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('new-purchase-orders.show', $po->id) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Goods Receipt</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate quantity inputs
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const max = parseInt(this.getAttribute('max'));
            const value = parseInt(this.value);
            
            if (value > max) {
                this.value = max;
                alert('Jumlah yang diterima tidak boleh melebihi jumlah yang dipesan: ' + max);
            }
            
            if (value < 1) {
                this.value = 1;
            }
        });
    });
});
</script>
@endpush
