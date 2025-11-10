<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class MarketplaceCartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('item')
            ->get();

        $rows = [];
        $total = 0;

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem->item) {  // Pastikan item exists
                    $price = (float) ($cartItem->item->selling_price ?? 0);
                    $qty = (int) $cartItem->quantity;
                    $subtotal = $price * $qty;

                    $rows[] = [
                        'item' => $cartItem->item,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ];
                    
                    $total += $subtotal;
                }
            }
        }

        return view('marketplace.cart', compact('rows', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $item = Item::findOrFail($data['item_id']);

        // Validasi stok
        if ($data['qty'] > (int) $item->stock) {
            return back()->withErrors(['qty' => 'Jumlah melebihi stok tersedia.']);
        }

        // Cek apakah item sudah ada di keranjang
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            // Update quantity jika item sudah ada
            $newQty = $cartItem->quantity + $data['qty'];
            
            if ($newQty > (int) $item->stock) {
                return back()->withErrors(['qty' => 'Total jumlah melebihi stok tersedia.']);
            }
            
            $cartItem->update(['quantity' => $newQty]);
        } else {
            // Buat item baru di keranjang
            CartItem::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'quantity' => $data['qty']
            ]);
        }

        if ($request->query('checkout')) {
            return redirect()->route('marketplace.checkout');
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('item_id', $data['item_id'])
            ->firstOrFail();

        $item = Item::findOrFail($data['item_id']);
        
        if ($data['qty'] > (int) $item->stock) {
            return back()->withErrors(['qty' => 'Jumlah melebihi stok tersedia.']);
        }

        $cartItem->update(['quantity' => $data['qty']]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);

        CartItem::where('user_id', Auth::id())
            ->where('item_id', $data['item_id'])
            ->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
