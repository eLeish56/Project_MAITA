<x-layout>
  <x-slot:title></x-slot:title>

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="page-container p-3">
    <div class="row g-3">
      <div class="col-4">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h5 class="card-title mb-0 text-primary">
                  <i class="fas fa-cash-register me-2"></i>
                  Kasir
                </h5>
              </div>
              <div class="col-md-6">
                <i class="fas fa-money-bill-alt float-end"></i>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label for="date" class="form-label small text-muted">Tanggal</label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="fas fa-calendar-alt text-primary"></i>
                </span>
                <input type="date" class="form-control bg-light" value="{{ date('Y-m-d') }}" name="date" id="date"
                  readonly>
              </div>
            </div>
            <div class="mb-3">
              <label for="user" class="form-label small text-muted">Kasir</label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="fas fa-user text-primary"></i>
                </span>
                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" name="user"
                  id="user" readonly>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="mb-3">
              <label for="product_name" class="form-label small text-muted">Barang</label>
              <div class="input-group input-group-lg">
                <input type="hidden" id="product_id">
                <input type="text" class="form-control" id="product_name" placeholder="Cari atau scan barcode...">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" 
                        data-bs-target="#all_items_modal" id="all_items_modal_btn">
                  <i class="fas fa-search"></i>
                </button>
              </div>
              <div class="form-text">
                <i class="fas fa-info-circle me-1"></i>
                Tekan Ctrl + Enter untuk membuka daftar barang
              </div>
            </div>
            
            <div class="row g-3 mb-4">
              <div class="col-sm-6">
                <label for="product_qty" class="form-label small text-muted">Jumlah</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-hashtag text-primary"></i>
                  </span>
                  <input type="number" class="form-control" value="1" min="1" id="product_qty">
                </div>
              </div>
              <div class="col-sm-6">
                <label for="product_stock" class="form-label small text-muted">Stok</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-boxes text-primary"></i>
                  </span>
                  <input type="number" class="form-control bg-light" value="0" id="product_stock" readonly>
                </div>
              </div>
            </div>
            
            <button class="btn btn-primary btn-lg w-100" id="add_to_cart">
              <i class="fas fa-cart-plus me-2"></i>
              Tambahkan ke Keranjang
            </button>
          </div>
        </div>
      </div>

      <div class="col-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <div class="row">
              <div class="col-md-6">
                <h5 class="card-title mb-0 text-success">
                  <i class="fas fa-shopping-cart me-2"></i>
                  Keranjang
                </h5>
              </div>
              <div class="col-md-6">
                <i class="fas fa-shopping-basket float-end"></i>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row align-items-center bg-light p-3 rounded-3 mb-3">
              <div class="col-md-6">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 text-primary">Total:</h5>
                  <h4 class="mb-0 ms-2 fw-bold" id="total">0</h4>
                </div>
                <input type="hidden" id="total_int" readonly disabled>
              </div>
              <div class="col-md-6">
                <div class="float-end">
                  <button class="btn btn-outline-danger" id="cancel_btn">
                    <i class="fas fa-times me-1"></i>
                    Batal
                  </button>
                  <button class="btn btn-outline-success mx-2" id="save_btn">
                    <i class="fas fa-bookmark me-1"></i>
                    Simpan
                  </button>
                  <button class="btn btn-primary" id="pay_btn">
                    <i class="far fa-money-bill-alt me-1"></i>
                    Proses
                  </button>
                </div>
              </div>
            </div>
            <div class="dropdown-divider mt-4"></div>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="bg-light">
                  <tr>
                    <th class="py-3"><b>Nama Barang</b></th>
                    <th class="py-3"><b>Harga</b></th>
                    <th class="py-3"><b>Jumlah</b></th>
                    <th class="py-3"><b>Subtotal</b></th>
                    <th class="py-3 text-center"><b>Aksi</b></th>
                  </tr>
                </thead>
                <tbody id="cart_data" class="border-top-0"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- All Items Modal --}}
  <div class="modal fade" id="all_items_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Daftar Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <div id="all_items_wrapper"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Payment Modal --}}
  <div class="modal fade" id="payment_modal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="payModalLabel">
            <i class="fas fa-money-check-alt me-2"></i>
            Pembayaran
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-light" style="max-height: calc(100vh - 210px); overflow-y: auto;">
          <!-- Info Transaksi Card -->
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-info-circle me-2"></i>
                Informasi Transaksi
              </h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="total_items" class="form-label small text-muted">
                    Jumlah Barang
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-white">
                      <i class="fas fa-boxes text-primary"></i>
                    </span>
                    <input type="number" class="form-control bg-white" id="total_items" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="invoice" class="form-label small text-muted">
                    Nomor Faktur
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-white">
                      <i class="fas fa-file-invoice text-primary"></i>
                    </span>
                    <input type="text" class="form-control bg-white" id="invoice" readonly>
                    <input type="hidden" id="invoice_no" readonly disabled>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Total dan Kembalian Card -->
          <div class="card mb-3 border-primary shadow-sm">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-calculator me-2"></i>
                Total & Kembalian
              </h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="grand_total" class="form-label small text-muted">
                    Total Pembayaran
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-primary text-white">
                      <i class="fas fa-money-bill-wave"></i>
                    </span>
                    <input type="text" class="form-control bg-white fw-bold" id="grand_total" readonly>
                    <input type="hidden" id="grand_total_int" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="change" class="form-label small text-muted">
                    Kembalian
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-success text-white">
                      <i class="fas fa-money-bill-wave"></i>
                    </span>
                    <input type="text" class="form-control bg-white fw-bold text-success" id="change" value="Rp. 0" readonly>
                    <input type="hidden" id="change_int" value="0" readonly disabled>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Customer Card -->
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-user me-2"></i>
                Informasi Pelanggan
              </h6>
              <div class="form-group">
                <label for="customer" class="form-label small text-muted">
                  Pelanggan <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-lg" id="customer" aria-label="Customer select">
                  <option value="0" selected>Bukan Pelanggan</option>
                  @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" 
                            data-phone="{{ $customer->phone }}"
                            data-email="{{ $customer->email }}">
                      {{ $customer->name }}
                    </option>
                  @endforeach
                </select>
                <div id="customer_details" class="mt-3 p-2 bg-light rounded" style="display: none;">
                  <div class="row g-2">
                    <div class="col-md-6">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <div>
                          <small class="text-muted d-block">Telepon</small>
                          <span id="customer_phone" class="fw-bold"></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <div>
                          <small class="text-muted d-block">Email</small>
                          <span id="customer_email" class="fw-bold"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Payment Details Card -->
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-credit-card me-2"></i>
                Detail Pembayaran
              </h6>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="payment_method" class="form-label small text-muted">
                    Metode Pembayaran <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-white">
                      <i class="fas fa-wallet text-primary"></i>
                    </span>
                    <select class="form-select" id="payment_method">
                      @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->name }}">{{ $payment_method->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="discount" class="form-label small text-muted">
                    Diskon (Rp.)
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-white">
                      <i class="fas fa-tag text-primary"></i>
                    </span>
                    <input type="text" class="form-control" id="discount" value="0" placeholder="Masukkan jumlah diskon">
                    <input type="hidden" class="form-control" id="discount_int" value="0">
                  </div>
                </div>
              </div>

              <div id="cash" class="mt-3">
                <label for="amount" class="form-label small text-muted">
                  Uang Tunai (Rp.) <span class="text-danger">*</span>
                </label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-white">
                    <i class="fas fa-money-bill-wave text-primary"></i>
                  </span>
                  <input type="text" class="form-control" id="amount" placeholder="Masukkan jumlah uang">
                  <button class="btn btn-outline-primary" type="button" id="exact_money">
                    <i class="fas fa-coins me-1"></i>
                    Uang Pas
                  </button>
                </div>
                <input type="hidden" class="form-control" id="amount_int" value="0" readonly>
              </div>
            </div>
          </div>
          <div id="cashless" class="card mb-3 shadow-sm" style="display: none;">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-qrcode me-2"></i>
                Pembayaran Non-Tunai
              </h6>
              <div class="form-group">
                <label for="payment_code" class="form-label small text-muted">
                  Kode Pembayaran
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-white">
                    <i class="fas fa-key text-primary"></i>
                  </span>
                  <input type="text" class="form-control" id="payment_code" placeholder="Masukkan kode pembayaran">
                </div>
              </div>
            </div>
          </div>

          <!-- Notes Card -->
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h6 class="card-subtitle mb-3 text-muted">
                <i class="fas fa-sticky-note me-2"></i>
                Catatan Tambahan
              </h6>
              <div class="form-group">
                <label for="note" class="form-label small text-muted">
                  Catatan Transaksi
                </label>
                <textarea class="form-control" rows="3" 
                          placeholder="Tambahkan catatan untuk transaksi ini..." 
                          id="note"></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-lg btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>
            Batal
          </button>
          <button type="button" class="btn btn-lg btn-primary" id="proccess_transaction_btn">
            <i class="fas fa-check-circle me-2"></i>
            Proses Pembayaran
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function set_max_min_qty() {
      count_stock();
      if (parseInt($(this).val()) > parseInt($('#product_stock').val())) {
        $(this).val(parseInt($('#product_stock').val()));
      }

      if (parseInt($(this).val()) < 1) {
        $(this).val(1);
      }
    }

    function proccess_transaction() {
      var customer_name = $('#customer_name').val();
      var invoice = $('#invoice').val();
      var invoice_no = $('#invoice_no').val();
      var payment_method = $('#payment_method').val();
      var grand_total = parseInt($('#grand_total_int').val());
      var amount = parseInt($('#amount_int').val());
      var change = parseInt($('#change_int').val());
      var discount = parseInt($('#discount_int').val());
      var payment_code = $('#payment_code').val();
      var note = $('#note').val();

      if (discount > grand_total) {
        return toastr.warning("Diskon tidak boleh lebih besar dari total");
      }

      if (payment_method == 'Tunai') {
        if (amount < grand_total) {
          return toastr.warning("Jumlah uang tidak mencukupi");
        }
      }

      if (!confirm('Apakah anda yakin ingin melanjutkan transaksi ini?')) return false;

      $.ajax({
        url: '{{ route('transaction.store') }}',
        type: 'POST',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          invoice,
          invoice_no,
          customer_name,
          payment_method,
          total: grand_total,
          amount,
          change,
          discount,
          payment_code,
          note,
        },
        success: function(data) {
          if (data.status == 'success') {
            toastr.success(data.message);
            clear_cart(false);
            $('#payment_modal').modal('hide');
          } else {
            toastr.error(data.message);
          }
        },
        error: function(e) {
          console.log(e);
        }
      });
    }

    function count_grand_total() {
      var total_int = parseInt($('#total_int').val());
      var discount = parseInt($('#discount_int').val());
      var grand_total = total_int - discount;

      if (discount > grand_total) {
        $('#grand_total_int').val(grand_total);
        $('#grand_total').val(indo_currency(parseInt($('#grand_total_int').val()), true));
      } else {
        $('#grand_total_int').val(grand_total);
        $('#grand_total').val(indo_currency(grand_total, true));
      }
    }

    function get_items(showModal = true) {
      $.ajax({
        url: '{{ route('transaction.get_items') }}',
        type: 'GET',
        dataType: 'html',
        success: function(data) {
          $('#all_items_wrapper').html(data);
          $('#item_stock').text(count_stock());
          if (showModal == true) {
            $('#all_items_modal').modal('show');
          }
        }
      });
    }

    function add_to_draft_cart(id) {
      $.ajax({
        url: '/inventory/item/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#product_name').val(data.name);
          $('#product_id').val(data.id);
          $('#all_items_modal').modal('hide');
          setTimeout(() => {
            $('#product_qty').focus();
          }, 300);
          count_stock();
        }
      });
    }

    function add_to_cart() {
      count_stock();
      if ($('#product_id').val() == '') {
        return toastr.warning("Pilih barang terlebih dahulu");
      }

      if (parseInt($('#product_stock').val()) < 1) {
        return toastr.warning("Stok barang tidak mencukupi");
      }

      $.ajax({
        url: '{{ route('cart.store') }}',
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          item_id: $('#product_id').val(),
          qty: $('#product_qty').val()
        },
        dataType: 'json',
        success: function(data) {
          $('#product_id').val('');
          $('#product_name').val('');
          $('#product_qty').val(1);
          $('#product_stock').val('0');
          get_cart();
        }
      });
    }

    function count_total() {
      var total = 0;
      $('.cart_subtotal_int').each(function() {
        total += parseInt($(this).val());
      });
      $('#total_int').val(total);
      $('#total').text(indo_currency(total, true));
    }

    function get_cart() {
      $.ajax({
        url: '{{ route('cart.index') }}',
        type: 'GET',
        success: function(data) {
          $('#cart_data').html(data);
          $('#product_name').focus();
          count_total();
        }
      });
    }

    function count_stock() {
      if ($('#product_id').val() == '') return;

      $.ajax({
        url: '/cart/count-stock/' + $('#product_id').val(),
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#product_stock').val(parseInt(data.stock));
          $('#product_qty').attr('max', parseInt(data.stock));
          return data.stock;
        }
      });
    }

    function clear_cart(confirmation = false) {
      if (empty_cart()) return false;

      if (confirmation) {
        if (!confirm('Apakah anda yakin ingin mengosongkan keranjang?')) return;
      }

      $('#payment_method').val('Tunai');
      $('#cash').show();
      $('#cashless').hide();
      $('#payment_code').val('');
      $('#discount').val(0);
      $('#discount_int').val(0);
      $('#amount').val(0);
      $('#amount_int').val(0);
      $('#change').val(indo_currency(0, true));
      $('#change_int').val(0);

      $.ajax({
        url: '{{ route('cart.clear') }}',
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
          get_cart();
          $('#product_name').val('');
          $('#product_id').val('');
          $('#product_qty').val(1);
          $('#product_stock').val(0);
          $('#product_name').focus();
        }
      });
    }

    function count_total_items() {
      var total = 0;
      $('.cart_qty').each(function() {
        total += parseInt($(this).val());
      });
      $('#total_items').val(total);
    }

    function get_invoice() {
      $.ajax({
        url: '{{ route('transaction.get_invoice') }}',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#invoice').val(data.invoice)
          $('#invoice_no').val(data.invoice_no);
        }
      });
    }

    function focus_product_column() {
      $('#product_name').focus();
    }

    function search_by_code() {
      $.ajax({
        url: '{{ route('transaction.get_items') }}',
        type: 'GET',
        dataType: 'json',
        data: {
          json: true
        },
        success: function(all_items) {
          var all_codes = [];
          for (let i = 0; i < all_items.length; i++) {
            all_codes.push(all_items[i].code);
          }

          if ($('#product_name').val() != '') {
            if (all_codes.includes($('#product_name').val().toUpperCase())) {
              $.ajax({
                url: '/cart/' + $('#product_name').val(),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                  $('#product_id').val(data.id);
                  $('#product_qty').focus();
                  count_stock();
                }
              });
            } else {
              $('#product_id').val('');
              $('#product_stock').val('0');
            }
          }
        }
      });
    }

    function pay() {
      if (empty_cart()) return false;

      count_total_items();
      count_grand_total();
      get_invoice();
      $('#payment_modal').modal('show');
    }

    function empty_cart() {
      if (parseInt($('#total_int').val()) < 1) {
        toastr.warning("Tidak ada barang yang dibeli");
        return true;
      }
    }

    function save_transaction() {
      var customer_id = $('#customer').val();
      var invoice = $('#invoice').val();
      var invoice_no = $('#invoice_no').val();
      var grand_total = parseInt($('#grand_total_int').val());

      if (empty_cart()) return false;

      if (!confirm('Apakah anda yakin ingin menyimpan transaksi ini?')) return false;

      $.ajax({
        url: '{{ route('transaction.save') }}',
        type: 'POST',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          invoice,
          invoice_no,
          customer_id,
          total: grand_total,
        },
        success: function(data) {
          if (data.status == 'success') {
            toastr.success(data.message);
            clear_cart(false);
            $('#payment_modal').modal('hide');
          } else {
            toastr.error(data.message);
          }
        },
        error: function(e) {
          console.log(e);
        }
      });
    }

    function updateCustomerDetails() {
      const selectedOption = $('#customer option:selected');
      if (selectedOption.val() != "0") {
        const phone = selectedOption.data('phone') || '-';
        const email = selectedOption.data('email') || '-';
        $('#customer_phone').text(phone);
        $('#customer_email').text(email);
        $('#customer_details').fadeIn('fast');
      } else {
        $('#customer_details').fadeOut('fast');
      }
    }

    $(document).ready(function() {
      get_cart();
      get_items(false);
      get_invoice();

      // Initialize Select2 for better search and selection
      $('#customer').select2({
        placeholder: "Pilih Pelanggan",
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5',
        dropdownParent: $('#payment_modal')
      });

      // Add customer change handler
      $('#customer').on('change', updateCustomerDetails);

      $('#product_qty').on('keyup', set_max_min_qty);
      $('#product_qty').on('change', set_max_min_qty);

      $('#all_items_modal').on('shown.bs.modal', function() {
        $('input[type="search"]').focus();
      });

      $('#payment_modal').on('shown.bs.modal', function() {
        $('#amount').focus();
      });

      $('#all_items_modal').on('hidden.bs.modal', focus_product_column);
      $('#payment_modal').on('hidden.bs.modal', focus_product_column);

      $('#product_name').on('keyup', function(e) {
        if (e.ctrlKey && e.keyCode == 13) {
          get_items();
        } else if (e.keyCode == 13) {
          add_to_cart();
        }

        search_by_code();
      });

      $('#product_qty').on('keyup', function(e) {
        if (e.keyCode == 13) add_to_cart();
      });

      $('#pay_btn').on('click', pay);

      $('#payment_method').on('change', function() {
        if ($(this).val() == 'Tunai') {
          $('#cash').show();
          $('#cashless').hide();
        } else {
          $('#cash').hide();
          $('#cashless').show();
        }
      });

      $('#amount').on('keyup', function(e) {

        $('#amount_int').val(parseInt($('#amount').val().replaceAll('.', '')));
        set_indo_currency('#amount');

        if (isNaN(parseInt($('#amount_int').val()))) {
          $('#amount_int').val(0);
        }

        var change = parseInt($('#amount_int').val()) - parseInt($('#grand_total_int').val());
        $('#change').val(indo_currency(change, true));
        $('#change_int').val(change);

        if (e.keyCode == '13') proccess_transaction();
      });

      $('#exact_money').on('click', function() {
        if (parseInt($('#grand_total_int').val()) < 0) return false;

        $('#amount').val(indo_currency(parseInt($('#grand_total_int').val())));
        $('#amount_int').val(parseInt($('#grand_total_int').val()));
        $('#change').val(indo_currency(0, true));
        $('#change_int').val(0);
      });

      $('#discount').on('keyup', function(e) {
        set_indo_currency('#discount');
        $('#discount_int').val(parseInt($('#discount').val().replaceAll('.', '')));
        if (isNaN(parseInt($('#discount_int').val()))) {
          $('#discount_int').val(0);
        }
        count_grand_total();

        if (e.keyCode == '13') proccess_transaction();
      });

      $('#proccess_transaction_btn').on('click', proccess_transaction);

      $('#cancel_btn').on('click', clear_cart);

      $('#save_btn').on('click', save_transaction);

      $('#all_items_modal_btn').on('click', get_items);

      $('#add_to_cart').on('click', add_to_cart);
    });
  </script>
</x-layout>
