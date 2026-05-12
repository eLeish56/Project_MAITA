<table class="table" id="supplier_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Nama</b></th>
      <th><b>No. Telp</b></th>
      <th><b>Email</b></th>
      <th><b>Produk yang Disediakan</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($suppliers as $supplier)
      <tr>
        <td style="width: 8%;">{{ $loop->iteration }}</td>
        <td style="width: 20%;">{{ $supplier->name }}</td>
        <td style="width: 15%;">{{ $supplier->phone }}</td>
        <td style="width: 20%;">{{ $supplier->email }}</td>
        <td style="width: 25%;">
          @if ($supplier->products && $supplier->products->count() > 0)
            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
              @foreach ($supplier->products as $product)
                <span style="display: inline-block; background: #e7f3ff; color: #0d6efd; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                  {{ $product->product_name }}
                </span>
              @endforeach
            </div>
          @else
            <span style="color: #999; font-style: italic;">-</span>
          @endif
        </td>
        @if ($type != 'export')
          <td style="width: 25%;" class="text-center">
            <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
              <button class="btn btn-sm rounded text-white btn-info" data-bs-toggle="modal"
                data-bs-target="#detail-modal" data-name="{{ $supplier->name }}" data-phone="{{ $supplier->phone }}"
                data-email="{{ $supplier->email }}" data-address="{{ $supplier->address }}"
                data-description="{{ $supplier->description }}" id="detail-btn"
                style="padding: 6px 12px; font-size: 11px; font-weight: 600;">
                <i class="fas fa-info-circle"></i> Detil
              </button>
              <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-sm rounded text-white btn-success"
                style="padding: 6px 12px; font-size: 11px; font-weight: 600;">
                <i class="fas fa-edit"></i> Ubah
              </a>
              <form action="{{ route('supplier.destroy', $supplier->id) }}" method="post" class="d-inline">
                <button type="submit"
                  onclick="return confirm('Apakah anda yakin ingin menghapus supplier {{ $supplier->name }}?')"
                  class="btn btn-sm rounded text-white btn-danger"
                  style="padding: 6px 12px; font-size: 11px; font-weight: 600;">
                  <i class="fas fa-trash-alt"></i> Hapus
                </button>
                @csrf
                @method('DELETE')
              </form>
            </div>
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
