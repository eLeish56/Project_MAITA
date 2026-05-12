{{-- resources/views/transaction/marketplace-online.blade.php --}}
<x-layout>
    <x-slot:title>Pesanan Marketplace (Menunggu Diambil)</x-slot:title>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pesanan Marketplace</h5>
            <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary btn-sm">← POS / Transaksi</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="marketplace-orders-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pengambil</th>
                            <th>No. HP</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Sisa Waktu</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->code }}</td>
                                <td>{{ $order->pickup_name ?? $order->customer_name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>@indo_currency($order->total_price)</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Menunggu</span>
                                    @elseif($order->status === 'picked')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Diambil</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-info"><i class="fas fa-cogs me-1"></i>Diproses</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Selesai</span>
                                    @elseif($order->status === 'expired')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Kadaluarsa</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->expired_at)
                                        @php
                                            $expiredAt = \Carbon\Carbon::parse($order->expired_at);
                                            $isExpired = now()->isAfter($expiredAt);
                                            if (!$isExpired) {
                                                $diff = $expiredAt->diff(now());
                                                if ($diff->d > 0) {
                                                    $timeRemain = "{$diff->d}h {$diff->h}m";
                                                } elseif ($diff->h > 0) {
                                                    $timeRemain = "{$diff->h}h {$diff->i}m";
                                                } else {
                                                    $timeRemain = "{$diff->i}m";
                                                }
                                            }
                                        @endphp
                                        @if($isExpired)
                                            <span class="text-danger"><strong>Kadaluarsa</strong></span>
                                        @else
                                            <span class="text-info"><strong>{{ $timeRemain }}</strong></span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-id="{{ $order->id }}"
                                            onclick="showOrderDetail(this)">
                                        <i class="fas fa-list"></i> Detail
                                    </button>
                                    @if($order->status === 'pending')
                                        @if(\Carbon\Carbon::parse($order->expired_at ?? now())->isAfter(now()))
                                            {{-- Pesanan pending dan belum expired: bisa proses atau batalkan --}}
                                            <button class="btn btn-sm btn-success" data-id="{{ $order->id }}"
                                                    onclick="processOrder(this)">
                                                <i class="fas fa-check"></i> Proses
                                            </button>
                                        @endif
                                        {{-- Tombol batalkan selalu ada untuk status pending --}}
                                        <button class="btn btn-sm btn-danger" data-id="{{ $order->id }}" data-code="{{ $order->code }}"
                                                onclick="cancelOrder(this)">
                                            <i class="fas fa-ban"></i> Batalkan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada pesanan marketplace yang menunggu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal detail pesanan (untuk showOrderDetail) --}}
    <div class="modal fade" id="transaction_detail_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- konten detail dimuat via AJAX -->
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi proses --}}
    <div class="modal fade" id="confirm_process_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Proses Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Ringkasan item pesanan akan dimuat di sini --}}
                    <div id="process_detail_body"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btn_confirm_process">Selesaikan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal batalkan pesanan --}}
    <div class="modal fade" id="cancel_order_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Kode Pesanan:</strong> <span id="cancel_order_code"></span></p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Alasan Pembatalan</label>
                        <textarea class="form-control" id="cancellation_reason" rows="3" placeholder="Masukkan alasan pembatalan pesanan..."></textarea>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Stok barang akan dikembalikan ke inventory setelah pembatalan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" id="btn_confirm_cancel">Batalkan Pesanan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            // Inisialisasi DataTable
            const mpTable = $('#marketplace-orders-table').DataTable({
                pageLength: 10,
                order: [[5, 'asc']],
                language: typeof datatableLanguageOptions !== 'undefined' ? datatableLanguageOptions : undefined,
                columnDefs: [
                    { targets: [8], orderable: false, searchable: false }
                ]
            });
            window.tableMarketplaceOrders = mpTable;
        });

        /**
         * Tampilkan detail pesanan ke modal #transaction_detail_modal.
         */
        function showOrderDetail(btn) {
            const id = btn.dataset.id;
            $.get(`/transaction/marketplace-orders/${id}/items`, function(html) {
                $('#transaction_detail_modal .modal-body').html(html);
                $('#transaction_detail_modal').modal('show');
            }).fail(function() {
                toastr.error('Gagal memuat detail pesanan.');
            });
        }

        /**
         * Ketika tombol Proses diklik:
         * 1. Ambil ringkasan item pesanan via AJAX.
         * 2. Tampilkan di modal konfirmasi.
         * 3. Simpan id pesanan pada button konfirmasi.
         */
        function processOrder(btn) {
            const id = btn.dataset.id;
            $.get(`/transaction/marketplace-orders/${id}/items`, function(html) {
                $('#process_detail_body').html(html);
                $('#btn_confirm_process').data('id', id);
                $('#confirm_process_modal').modal('show');
            }).fail(function() {
                toastr.error('Gagal memuat detail pesanan.');
            });
        }

        /**
         * Ketika tombol Selesaikan di modal konfirmasi diklik:
         * - Kirim POST untuk menyelesaikan pesanan.
         * - Jika sukses, tutup modal dan hapus baris pada tabel.
         */
        $('#btn_confirm_process').on('click', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/transaction/marketplace-orders/${id}/process`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    toastr.success(res.message || 'Pesanan berhasil diselesaikan.');
                    $('#confirm_process_modal').modal('hide');
                    // Hapus baris dari DataTable
                    window.tableMarketplaceOrders
                        .row($(`#marketplace-orders-table button[data-id="${id}"]`).closest('tr'))
                        .remove()
                        .draw();
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Gagal memproses.');
                }
            });
        });

        /**
         * Ketika tombol Batalkan diklik:
         * - Tampilkan modal untuk input alasan pembatalan.
         */
        function cancelOrder(btn) {
            const id = btn.dataset.id;
            const code = btn.dataset.code;
            $('#cancel_order_code').text(code);
            $('#cancellation_reason').val('');
            $('#btn_confirm_cancel').data('id', id);
            $('#cancel_order_modal').modal('show');
        }

        /**
         * Ketika tombol Batalkan Pesanan di modal diklik:
         * - Kirim POST untuk batalkan pesanan dengan alasan.
         * - Jika sukses, tutup modal dan hapus baris pada tabel.
         */
        $('#btn_confirm_cancel').on('click', function() {
            const id = $(this).data('id');
            const reason = $('#cancellation_reason').val().trim();

            if (!reason) {
                toastr.warning('Alasan pembatalan harus diisi.');
                return;
            }

            $.ajax({
                url: `/transaction/marketplace-orders/${id}/cancel`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cancellation_reason: reason
                },
                success: function(res) {
                    toastr.success(res.message || 'Pesanan berhasil dibatalkan.');
                    $('#cancel_order_modal').modal('hide');
                    // Hapus baris dari DataTable
                    window.tableMarketplaceOrders
                        .row($(`#marketplace-orders-table button[data-id="${id}"]`).closest('tr'))
                        .remove()
                        .draw();
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Gagal membatalkan pesanan.');
                }
            });
        });
    </script>
    @endpush
</x-layout>
