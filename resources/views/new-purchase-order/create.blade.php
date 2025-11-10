<x-layout>
    <x-slot:title>Buat Purchase Order</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Buat Purchase Order dari PR #{{ $pr->pr_number }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('new-purchase-orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pr_id" value="{{ $pr->id }}">
                            <input type="hidden" name="supplier_id" value="{{ $pr->supplier_id }}">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor PR</label>
                                        <input type="text" class="form-control" value="{{ $pr->pr_number }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal PR</label>
                                        <input type="text" class="form-control" value="{{ $pr->request_date->format('d/m/Y') }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Diminta Oleh</label>
                                        <input type="text" class="form-control" value="{{ $pr->requester->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kontak Person</label>
                                        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                                               value="{{ old('contact_person') }}" required>
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kontak Telepon</label>
                                        <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               value="{{ old('contact_phone') }}" required>
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Perkiraan Tanggal Pengiriman</label>
                                        <input type="date" name="estimated_delivery_date" class="form-control @error('estimated_delivery_date') is-invalid @enderror"
                                               value="{{ old('estimated_delivery_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                                        @error('estimated_delivery_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Supplier</label>
                                        <input type="text" class="form-control" value="{{ $pr->supplier->name }}" readonly>
                                        <input type="hidden" name="supplier_id" value="{{ $pr->supplier_id }}">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pr->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product_name }}
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           name="items[{{ $loop->index }}][quantity]"
                                                           value="{{ $item->quantity }}" readonly>
                                                </td>
                                                <td>{{ $item->unit }}</td>
                                                <td>
                                                    <input type="number" class="form-control text-end unit-price" 
                                                           name="items[{{ $loop->index }}][unit_price]"
                                                           value="{{ old("items.{$loop->index}.unit_price", 0) }}" required
                                                           min="0" step="1000">
                                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][notes]" value="">
                                                </td>
                                                <td class="text-end">
                                                    <span class="item-total">0</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end">
                                                <span id="grand-total">0</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('new-purchase-orders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateTotals() {
            let grandTotal = 0;
            document.querySelectorAll('tbody tr').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                const total = quantity * unitPrice;
                
                row.querySelector('.item-total').textContent = total.toLocaleString('id-ID');
                grandTotal += total;
            });
            
            document.getElementById('grand-total').textContent = grandTotal.toLocaleString('id-ID');
        }

        document.querySelectorAll('.unit-price').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        // Initial calculation
        updateTotals();
    </script>
    @endpush
</x-layout>