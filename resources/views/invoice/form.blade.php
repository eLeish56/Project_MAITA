@extends('layouts.app')

{{-- Halaman form invoice menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Buat Invoice')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Buat Invoice</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store', $po->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="invoice_number" class="form-label">Nomor Invoice</label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required 
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" 
                                value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="form-label">Jumlah Tagihan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" required 
                                    value="{{ $po->total_amount ?? 0 }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="invoice_file" class="form-label">File Invoice</label>
                            <input type="file" name="invoice_file" id="invoice_file" class="form-control" required>
                            <div class="form-text text-muted">
                                Format: PDF, JPG, JPEG, PNG (max 2MB)
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Simpan Invoice dan Selesaikan Proses
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection