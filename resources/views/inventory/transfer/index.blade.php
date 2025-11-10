<x-layout>
  <x-slot:title>Transfer Stok ke Etalase</x-slot:title>

  @if(session('status'))
    <x-alert type="success" :message="session('status')"/>
  @endif

  <form action="{{ route('inventory.transfer.do') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="batch_id">Pilih Batch Gudang</label>
      <select name="batch_id" class="form-select" required>
        @foreach($items as $item)
          @foreach($item->batches as $batch)
            <option value="{{ $batch->id }}">
              {{ $item->name }} | Qty: {{ $batch->qty_on_hand }} | Exp: {{ $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : '-' }}
            </option>
          @endforeach
        @endforeach
      </select>
    </div>
    <div class="form-group mt-2">
      <label for="qty">Jumlah ke Etalase</label>
      <input type="number" step="0.01" class="form-control" name="qty" required>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Transfer</button>
  </form>
</x-layout>
