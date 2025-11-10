{{-- View untuk menampilkan detail item pesanan online dalam modal --}}
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Item</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">@indo_currency($item->price)</td>
                    <td class="text-end">@indo_currency($item->subtotal)</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada item</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end fw-bold">Total:</td>
                <td class="text-end fw-bold">@indo_currency($order->total)</td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="mt-3">
    <h6>Informasi Pesanan:</h6>
    <table class="table table-sm table-borderless">
        <tr>
            <td width="150">Kode Pesanan</td>
            <td>: {{ $order->code }}</td>
        </tr>
        <tr>
            <td>Nama Pengambil</td>
            <td>: {{ $order->pickup_name }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>: @indo_currency($order->total)</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: <span class="badge bg-{{ $order->status === 'pending_pickup' ? 'warning' : 'success' }}">
                {{ $order->status === 'pending_pickup' ? 'Menunggu Diambil' : 'Selesai' }}
            </span></td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>: {{ $order->phone }}</td>
        </tr>
        <tr>
            <td>Waktu Pesanan</td>
            <td>: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td>
        </tr>
        @if($order->notes)
        <tr>
            <td>Catatan</td>
            <td>: {{ $order->notes }}</td>
        </tr>
        @endif
    </table>
</div>