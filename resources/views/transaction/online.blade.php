{{-- Improved version of resources/views/transaction/online.blade.php
    for marketplace orders. This template displays marketplace orders
    waiting to be picked up (status pending) and provides
    a simple button to process them immediately.
--}}

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
                            <th>No. HP</th>
                            <th>Total</th>
                            <th>Tanggal</th>
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
                                    {{-- Tombol detail menampilkan item pesanan --}}
                                    <button class="btn btn-sm btn-info" data-id="{{ $order->id }}"
                                            onclick="showOrderDetail(this)">
                                        <i class="fas fa-list"></i> Detail
                                    </button>
                                    {{-- Tombol proses langsung menyelesaikan pesanan --}}
                                    <button class="btn btn-sm btn-success" data-id="{{ $order->id }}"
                                            onclick="processOrder(this)">
                                        <i class="fas fa-check"></i> Proses
                                    </button>
                                    {{-- Tombol batalkan pesanan --}}
                                    <button class="btn btn-sm btn-danger" data-id="{{ $order->id }}"
                                            onclick="cancelOrder(this)">
                                        <i class="fas fa-times"></i> Batalkan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada pesanan marketplace yang menunggu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan detail pesanan --}}
    {{-- Modal Detail Pesanan --}}
    <div class="modal fade" id="transaction_detail_modal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Content will be loaded here --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan detail pesanan --}}
    {{-- Modal Detail Pesanan --}}
    <div class="modal fade" id="transaction_detail_modal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Content will be loaded here --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cetak Nota Transaksi Online - Selaras dengan Design --}}
    <div class="modal fade" id="print_receipt_modal_online" tabindex="-1" aria-labelledby="printReceiptLabelOnline" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <!-- Header dengan Gradient -->
                <div class="modal-header bg-primary text-white border-0 pb-4">
                    <div class="w-100 text-center">
                        <h5 class="modal-title" id="printReceiptLabelOnline">
                            <i class="fas fa-check-circle me-2"></i>
                            Transaksi Berhasil!
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;"></button>
                </div>
                <!-- Body -->
                <div class="modal-body text-center py-4">
                    <!-- Success Icon Animation -->
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #e7f5ff; border-radius: 50%;">
                            <i class="fas fa-check-circle text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <!-- Message -->
                    <div class="mt-4">
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-2" style="color: #0d6efd;"></i>
                            <span>Apakah Anda ingin mencetak nota untuk transaksi ini?</span>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-light border-0 pt-3 pb-3">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal" style="padding: 8px 24px;">
                        <i class="fas fa-times me-2"></i>
                        <span>Tidak, Lewati</span>
                    </button>
                    <button type="button" class="btn btn-primary rounded-3" id="print_receipt_btn_online" style="padding: 8px 24px;">
                        <i class="fas fa-print me-2"></i>
                        <span>Ya, Cetak Nota</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            // Inisialisasi DataTable untuk daftar pesanan marketplace
            const mpTable = $('#marketplace-orders-table').DataTable({
                pageLength: 10,
                order: [[5, 'asc']],
                language: typeof datatableLanguageOptions !== 'undefined' ? datatableLanguageOptions : undefined,
                columnDefs: [
                    { targets: [6], orderable: false, searchable: false }
                ]
            });
            window.tableMarketplaceOrders = mpTable;

            // Handle print receipt button
            $('#print_receipt_btn_online').on('click', function() {
                var transactionId = $(this).attr('data-transaction-id');
                window.open('/transaction/' + transactionId + '/print-receipt', '_blank');
                $('#print_receipt_modal_online').modal('hide');
            });

            // Jika user menutup modal tanpa cetak
            $('#print_receipt_modal_online').on('hidden.bs.modal', function() {
                window.tableMarketplaceOrders.draw();
            });
        });

        /**
         * Menampilkan detail item pesanan marketplace.
         * Pastikan ada modal #transaction_detail_modal di layout.
         */
        function showOrderDetail(btn) {
            const id = btn.dataset.id;
            // Gunakan route yang benar sesuai dengan jenis pesanan
            const url = '{{ route('transaction.marketplace.orders') }}' === window.location.pathname
                ? `/transaction/marketplace-orders/${id}/items`
                : `/transaction/online-orders/${id}/items`;
            
            $.get(url, function(html) {
                $('#transaction_detail_modal .modal-body').html(html);
                $('#transaction_detail_modal').modal('show');
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Gagal memuat detail pesanan.');
            });
        }

        /**
         * Batalkan pesanan online oleh kasir
         * Meminta alasan pembatalan, mengirim permintaan POST, dan menghapus baris dari tabel setelah sukses
         */
        function cancelOrder(btn) {
            const id = btn.dataset.id;
            
            // Tampilkan prompt untuk alasan pembatalan
            const reason = prompt('Masukkan alasan pembatalan pesanan:', '');
            if (reason === null || reason.trim() === '') {
                toastr.warning('Pembatalan dibatalkan');
                return;
            }

            $(btn).prop('disabled', true)
                  .html('<i class="fas fa-spinner fa-spin"></i> Membatalkan...');

            $.ajax({
                url: `/transaction/online-orders/${id}/cancel`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cancellation_reason: reason
                },
                success: function(res) {
                    toastr.success('Pesanan berhasil dibatalkan dan stok dikembalikan');
                    
                    // Reset tombol ke keadaan awal
                    $(btn).prop('disabled', false)
                          .html('<i class="fas fa-times"></i> Batalkan');
                    
                    // Refresh tabel setelah 2 detik
                    setTimeout(() => {
                        $(btn).closest('tr').fadeOut('slow', function() {
                            $(this).remove();
                        });
                        
                        // Reload halaman jika sudah tidak ada order
                        if ($('#marketplace-orders-table tbody tr').length === 0) {
                            window.location.reload();
                        }
                    }, 1500);
                },
                error: function(xhr) {
                    // Reset tombol ke keadaan awal saat error
                    $(btn).prop('disabled', false)
                          .html('<i class="fas fa-times"></i> Batalkan');
                          
                    // Handle CSRF token expiration
                    if (xhr.status === 419) {
                        toastr.error('Sesi telah berakhir. Halaman akan dimuat ulang.');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        return;
                    }
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Terjadi kesalahan saat membatalkan pesanan.');
                }
            });
        }

        /**
         * Proses pesanan marketplace secara otomatis.
         * Meminta konfirmasi, mengirim permintaan POST, dan menghapus baris dari tabel setelah sukses.
         */
        function processOrder(btn) {
            const id = btn.dataset.id;
            if (!confirm('Yakin ingin memproses pesanan ini?')) return;

            $(btn).prop('disabled', true)
                  .html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: `/transaction/online-orders/${id}/process`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {},
                success: function(res) {
                    toastr.success('Pesanan berhasil diproses');
                    
                    // Tampilkan modal cetak nota
                    if (res.transaction_id) {
                        $('#print_receipt_btn_online').attr('data-transaction-id', res.transaction_id);
                        $('#print_receipt_modal_online').modal('show');
                    }
                    
                    // Reset tombol ke keadaan awal
                    $(btn).prop('disabled', false)
                          .html('<i class="fas fa-check"></i> Proses');
                    
                    // Refresh tabel setelah 2 detik
                    setTimeout(() => {
                        window.tableMarketplaceOrders.draw();
                        window.tableMarketplaceOrders
                            .row($(btn).closest('tr'))
                            .remove()
                            .draw();
                    }, 2000);
                },
                error: function(xhr) {
                    // Reset tombol ke keadaan awal saat error
                    $(btn).prop('disabled', false)
                          .html('<i class="fas fa-check"></i> Proses');
                          
                    // Handle CSRF token expiration
                    if (xhr.status === 419) {
                        toastr.error('Sesi telah berakhir. Halaman akan dimuat ulang.');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        return;
                    }
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Terjadi kesalahan saat memproses pesanan.');
                }
            });
        }
    </script>
    @endpush
</x-layout>
