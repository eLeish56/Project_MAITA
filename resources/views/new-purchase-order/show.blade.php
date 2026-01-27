<x-layout>
    <x-slot:title>Detail Purchase Order</x-slot:title>

    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Show validation errors (so user sees why submission failed) --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Purchase Order #{{ $po->po_number }}</h5>
                    <div>
                        @if($po->status !== 'draft')
                            <a href="{{ route('new-purchase-orders.pdf', $po->id) }}" 
                               class="btn btn-secondary" target="_blank">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        @endif
                        @if($po->status === 'draft' || $po->status === 'sent')
                            <a href="{{ route('new-purchase-orders.prices.edit', $po->id) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Update Harga
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi PO -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi PO:</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="150">Nomor PR</td>
                                <td>: {{ $po->purchaseRequest->pr_number }}</td>
                            </tr>
                            <tr>
                                <td>Nomor PO</td>
                                <td>: {{ $po->po_number }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal PO</td>
                                <td>: {{ $po->po_date ? $po->po_date->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: 
                                    @if($po->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @elseif($po->status === 'sent')
                                        <span class="badge bg-info">Terkirim</span>
                                    @elseif($po->status === 'confirmed')
                                        <span class="badge bg-primary">Dikonfirmasi</span>
                                    @elseif($po->status === 'received')
                                        <span class="badge bg-success">Diterima</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Supplier:</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="150">Nama</td>
                                <td>: {{ $po->supplier->name }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: {{ $po->supplier->phone }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $po->supplier->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Daftar Item -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th class="text-end">Harga</th>
                                <th>Status Harga</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($po->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td class="text-end">
                                        {{ number_format($item->final_unit_price ?? $item->unit_price) }}
                                        @if($item->is_estimated_price)
                                            <span class="badge bg-warning">Estimasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->unit_price == 0)
                                            <span class="badge bg-secondary">Belum Ada Harga</span>
                                        @elseif($po->prices_confirmed && $item->unit_price > 0)
                                            <span class="badge bg-success">Final</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($item->quantity * ($item->final_unit_price ?? $item->unit_price)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Subtotal:</th>
                                <th class="text-end">{{ number_format($po->total_amount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($po->notes)
                    <div class="mt-4">
                        <h6>Catatan:</h6>
                        <p class="mb-0">{{ $po->notes }}</p>
                    </div>
                @endif

                <!-- Tabs untuk GR dan Invoice -->
                <div class="mt-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#gr-tab">
                                <i class="fas fa-truck me-2"></i>Goods Receipt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#invoice-tab">
                                <i class="fas fa-file-invoice me-2"></i>Invoice
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content pt-4">
                        <!-- Goods Receipt Tab -->
                        <div class="tab-pane fade show active" id="gr-tab">
                            @if($po->goodsReceipts->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No. GR</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Diterima Oleh</th>
                                                <th>Status Kadaluarsa</th>
                                                <th>Batch</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($po->goodsReceipts as $gr)
                                                <tr>
                                                    <td>{{ $gr->gr_number }}</td>
                                                    <td>{{ $gr->receipt_date ? date('d/m/Y', strtotime($gr->receipt_date)) : '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Diterima</span>
                                                    </td>
                                                    <td>{{ $gr->receiver ? $gr->receiver->name : '-' }}</td>
                                                    <td>
                                                        @foreach($gr->items as $grItem)
                                                            @if($grItem->expiry_status === 'expired')
                                                                <span class="badge bg-danger">Kadaluarsa</span>
                                                            @elseif($grItem->expiry_status === 'warning')
                                                                <span class="badge bg-warning">Mendekati Kadaluarsa</span>
                                                            @elseif($grItem->expiry_status === 'safe')
                                                                <span class="badge bg-success">Aman</span>
                                                            @endif
                                                            <small class="d-block">
                                                                {{ $grItem->product_name }}: 
                                                                {{ $grItem->expiry_date ? $grItem->expiry_date->format('d/m/Y') : 'N/A' }}
                                                            </small>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach($gr->items as $grItem)
                                                            <div class="mb-1">
                                                                <small class="d-block">
                                                                    {{ $grItem->product_name }}: 
                                                                    {{ $grItem->batch_number ?? 'N/A' }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    Sisa: {{ $grItem->remaining_quantity }}
                                                                </small>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 text-muted">Belum ada Goods Receipt</p>
                                </div>
                            @endif
                        </div>

                        <!-- Invoice Tab -->
                        <div class="tab-pane fade" id="invoice-tab">
                            @if($po->invoices->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No. Invoice</th>
                                                <th>Tanggal</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($po->invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->invoice_date ? date('d/m/Y', strtotime($invoice->invoice_date)) : '-' }}</td>
                                                    <td>{{ $invoice->due_date ? date('d/m/Y', strtotime($invoice->due_date)) : '-' }}</td>
                                                    <td>Rp {{ number_format($invoice->amount) }}</td>
                                                    <td>
                                                        @if($invoice->status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($invoice->status === 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($invoice->invoice_file)
                                                            <a href="{{ route('new-purchase-orders.invoice.download', $invoice->id) }}" class="btn btn-sm btn-info" title="Download Invoice">
                                                                <i class="fas fa-file-pdf"></i> Invoice
                                                            </a>
                                                        @else
                                                            <span class="badge bg-warning">Tidak ada file</span>
                                                        @endif
                                                        @if($invoice->payment_proof)
                                                            <a href="{{ route('new-purchase-orders.payment-proof.download', $invoice->id) }}" class="btn btn-sm btn-secondary ms-1" title="Download Bukti Pembayaran">
                                                                <i class="fas fa-receipt"></i> Bukti Pembayaran
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 text-muted">Belum ada Invoice</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4">
            @if($po->status === 'draft')
                @if(!$po->prices_confirmed)
                    <a href="{{ route('new-purchase-orders.prices.edit', $po->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Update Harga
                    </a>
                @endif

                <form action="{{ route('new-purchase-orders.mark-sent', $po->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" 
                            onclick="return confirm('Tandai PO ini sebagai terkirim?')"
                            {{ !$po->prices_confirmed ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane"></i> Kirim ke Supplier
                    </button>
                </form>
                
                @if(!$po->prices_confirmed)
                    <div class="text-danger mt-2">
                        <small><i class="fas fa-exclamation-circle"></i> PO tidak dapat dikirim sebelum semua harga dikonfirmasi</small>
                    </div>
                @endif
            @endif

            @if($po->status === 'sent')
                <!-- Button to create Goods Receipt -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createGRModal">
                    <i class="fas fa-truck"></i> Buat Goods Receipt
                </button>
            @endif

            @if(in_array($po->status, ['received', 'validated']) && !$po->invoices()->exists())
                <!-- Button to create Invoice -->
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                    <i class="fas fa-file-invoice-dollar"></i> Buat Invoice
                </button>
            @elseif($po->invoices()->exists() && $po->status !== 'completed')
                <!-- Button to view invoices when already exists -->
                <a href="#invoice-tab" class="btn btn-secondary" onclick="document.querySelector('[data-bs-target=\"#invoice-tab\"]')?.click()">
                    <i class="fas fa-eye"></i> Lihat Invoice
                </a>
            @endif
        </div>

        <!-- Modal Create Goods Receipt -->
        <div class="modal fade" id="createGRModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Goods Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('new-purchase-orders.create-gr', $po->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Penerimaan</label>
                                    <input type="date" name="receipt_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Surat Penerimaan Barang</label>
                                    <input type="file" name="receipt_document" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Format: PDF, JPG, JPEG, PNG (max 2MB)</small>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah PO</th>
                                            <th>Jumlah Diterima</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th style="width: 200px;">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($po->items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->product_name }}
                                                <input type="hidden" name="items[{{ $loop->index }}][product_name]" value="{{ $item->product_name }}">
                                                <input type="hidden" name="items[{{ $loop->index }}][item_id]" value="{{ $item->item_id }}">
                                                @if(!$item->item_id)
                                                    <div class="text-danger">
                                                        <small>Warning: No master item linked!</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                            <td>
                                                <input type="number" 
                                                       name="items[{{ $loop->index }}][quantity_received]" 
                                                       class="form-control"
                                                       min="0"
                                                       max="{{ $item->quantity }}"
                                                       value="{{ $item->quantity }}"
                                                       required>
                                                <input type="hidden" name="items[{{ $loop->index }}][unit]" value="{{ $item->unit }}">
                                            </td>
                                            <td>
                                                <input type="date" 
                                                       name="items[{{ $loop->index }}][expiry_date]" 
                                                       class="form-control"
                                                       required
                                                       min="{{ date('Y-m-d') }}"
                                                       onchange="validateExpiryDate(this)">
                                                <small class="text-muted d-block">Tanggal kadaluarsa produk</small>
                                            </td>
                                            <!-- Batch number akan digenerate otomatis -->
                                            <td>
                                                <input type="text" 
                                                       name="items[{{ $loop->index }}][notes]" 
                                                       class="form-control"
                                                       placeholder="Catatan penerimaan...">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Umum</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">Simpan Goods Receipt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Create Invoice -->
        <div class="modal fade" id="createInvoiceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('invoices.store', $po->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nomor Invoice</label>
                                <input type="text" name="invoice_number" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Invoice</label>
                                <input type="date" name="invoice_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Jatuh Tempo</label>
                                <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah Tagihan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="amount" class="form-control" required value="{{ $po->total_amount }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File Invoice</label>
                                <input type="file" name="invoice_file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (max 2MB)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bukti Pembayaran</label>
                                <input type="file" name="payment_proof" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (max 2MB)</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan dan Selesaikan PO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Script untuk validasi tanggal dan kalkulasi -->
        <script>
            function validateExpiryDate(input) {
                const today = new Date();
                const expiryDate = new Date(input.value);
                
                if (expiryDate <= today) {
                    alert('Tanggal kadaluarsa harus lebih dari hari ini!');
                    input.value = '';
                    return false;
                }

                // Peringatan jika tanggal kadaluarsa kurang dari 30 hari
                const thirtyDaysFromNow = new Date();
                thirtyDaysFromNow.setDate(today.getDate() + 30);
                
                if (expiryDate <= thirtyDaysFromNow) {
                    alert('Perhatian: Produk akan kadaluarsa dalam waktu kurang dari 30 hari!');
                }
            }

            // Inisialisasi semua input tanggal dengan tanggal minimal hari ini
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.querySelectorAll('input[type="date"]').forEach(input => {
                    if (input.name.includes('expiry_date')) {
                        input.min = today;
                    }
                });
            });
        </script>
    </div>
</x-layout>