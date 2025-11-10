<table class="table" id="customer_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Nama</b></th>
      <th><b>Email</b></th>
      <th><b>No. Telp</b></th>
      <th><b>Transaksi POS</b></th>
      <th><b>Transaksi Online</b></th>
      <th><b>Total Transaksi</b></th>
      <th><b>Total Pembelian</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($customers as $customer)
      <tr>
        <td style="width: 5%;">{{ $loop->iteration }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->email }}</td>
        <td>{{ $customer->phone }}</td>
        <td align="center">{{ $customer->pos_transactions_count }}</td>
        <td align="center">{{ $customer->marketplace_orders_count }}</td>
        <td align="center">{{ $customer->total_transactions }}</td>
        <td align="right">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
        @if ($type != 'export')
          <td class="text-center" style="width: 25%;">
            <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" 
              data-name="{{ $customer->name }}"
              data-email="{{ $customer->email }}"
              data-phone="{{ $customer->phone }}"
              data-address="{{ $customer->address }}"
              data-pos-transactions="{{ $customer->pos_transactions_count }}"
              data-online-transactions="{{ $customer->marketplace_orders_count }}"
              data-total-transactions="{{ $customer->total_transactions }}"
              data-total-spent="{{ $customer->total_spent }}"
              id="detail-btn">
              <i class="fas fa-info-circle"></i> Detil
            </button>
            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-sm rounded-3 text-white btn-secondary">
              <i class="fas fa-info-circle"></i>
              Detil
            </a>
            <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
              Ubah
            </a>
            <form action="{{ route('customer.destroy', $customer->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus akun pelanggan {{ $customer->name }}? Akun akan diarsipkan dan pelanggan tidak akan bisa login.')"
                class="btn btn-sm rounded-3 text-white btn-danger">
                <i class="fas fa-user-slash"></i>
                Nonaktifkan
              </button>
              @csrf
              @method('DELETE')
            </form>
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
