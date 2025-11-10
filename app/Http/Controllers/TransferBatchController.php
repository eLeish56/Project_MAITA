<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\Inventory\TransferBatchService;
use Illuminate\Http\Request;

class TransferBatchController extends Controller
{
    private $transferService;

    public function __construct(TransferBatchService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function index()
    {
        // Ambil semua item dengan batch gudang aktif
        $items = Item::with(['batches' => function ($q) {
            $q->where('location_code', 'WH')->where('qty_on_hand', '>', 0);
        }])->get();

        return view('inventory.transfer.index', compact('items'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:inventory_batches,id',
            'qty'      => 'required|numeric|min:0.01',
        ]);

        $this->transferService->transferToDisplay($request->batch_id, $request->qty);

        return redirect()->back()->with('status', 'Stok berhasil dipindahkan ke etalase');
    }
}
