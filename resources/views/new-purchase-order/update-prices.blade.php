<x-layout>
    <x-slot:title>Update Harga PO #{{ $po->po_number }}</x-slot:title>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Update Harga PO #{{ $po->po_number }}</h5>
                        <p class="text-sm mb-0">Update harga untuk Purchase Order</p>
                    </div>
                    <a href="{{ route('new-purchase-orders.show', $po->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('new-purchase-orders.prices.update', $po->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-primary shadow-sm rounded-circle p-2 me-3">
                                            <i class="fas fa-building text-white"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted text-sm mb-1">Supplier</p>
                                            <h6 class="mb-0">{{ $po->supplier->name }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-success shadow-sm rounded-circle p-2 me-3">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted text-sm mb-1">Tanggal PO</p>
                                            <h6 class="mb-0">{{ $po->po_date->format('d/m/Y') }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-sm text-dark">Item</th>
                                    <th class="text-sm text-dark text-end">Quantity</th>
                                    <th class="text-sm text-dark">Satuan</th>
                                    <th class="text-sm text-dark text-end" style="min-width: 200px;">Harga Satuan</th>
                                    <th class="text-sm text-dark text-end" style="min-width: 150px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->items as $item)
                                <tr>
                                    <td>
                                        <p class="text-sm mb-0">{{ $item->product_name }}</p>
                                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                    </td>
                                    <td class="text-end">
                                        <p class="text-sm mb-0">{{ number_format($item->quantity, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ $item->unit }}</p>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   name="items[{{ $loop->index }}][unit_price]" 
                                                   value="{{ old("items.{$loop->index}.unit_price", $item->unit_price) }}"
                                                   class="form-control text-end"
                                                   step="1"
                                                   min="0"
                                                   required
                                                   onchange="updateTotal(this, {{ $item->quantity }}, {{ $loop->index }})">
                                        </div>
                                    </td>
                                    <td class="text-end" id="total-{{ $loop->index }}">
                                        <p class="text-sm mb-0">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                    <td class="text-end" id="grandTotal">
                                        <strong>{{ number_format($po->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" 
                                name="action" 
                                value="update" 
                                class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        
                        <button type="submit" 
                                name="action" 
                                value="confirm" 
                                class="btn btn-success"
                                onclick="return confirm('Yakin ingin mengkonfirmasi semua harga? Status akan berubah menjadi final setelah dikonfirmasi.')">
                            <i class="fas fa-check-circle me-2"></i>Konfirmasi Semua Harga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateTotal(input, quantity, index) {
            const price = parseFloat(input.value) || 0;
            const total = price * quantity;
            document.getElementById('total-' + index).innerText = total.toLocaleString('id-ID');
            
            // Update grand total
            let grandTotal = 0;
            document.querySelectorAll('input[name$="[unit_price]"]').forEach(input => {
                const qty = parseInt(input.closest('tr').querySelector('td:nth-child(2)').textContent.replace(/\D/g,''));
                grandTotal += (parseFloat(input.value) || 0) * qty;
            });
            document.getElementById('grandTotal').innerText = grandTotal.toLocaleString('id-ID');
        }
    </script>
</x-layout>