<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\MarketplaceOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $customers = User::where('role', 'customer')
            ->where('is_active', true)
            ->select([
                'users.*',
                DB::raw('(SELECT COUNT(*) FROM transactions t WHERE t.user_id = users.id) as pos_transactions_count'),
                DB::raw('(SELECT COUNT(*) FROM marketplace_orders mo WHERE mo.user_id = users.id) as marketplace_orders_count'),
                DB::raw('(SELECT COALESCE(SUM(td.item_price * td.qty), 0) FROM transactions t 
                         JOIN transaction_details td ON t.id = td.transaction_id 
                         WHERE t.user_id = users.id) as total_pos_spent'),
                DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM marketplace_orders 
                         WHERE user_id = users.id) as total_marketplace_spent'),
            ])
            ->withCount(['transactions', 'marketplaceOrders'])
            ->get()
            ->map(function ($user) {
                $user->total_transactions = $user->pos_transactions_count + $user->marketplace_orders_count;
                $user->total_spent = ($user->total_pos_spent ?? 0) + ($user->total_marketplace_spent ?? 0);
                return $user;
            });

        return view('customer.index', [
            'customers' => $customers,
            'type' => 'show'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customer.form', [
            'type' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'phone' => 'required|numeric|unique:users,phone',
            'address' => 'required|string'
        ]);

        // create a user with role 'customer'
        User::create(array_merge($request->only(['name','phone','address']), [
            'role' => 'customer',
            'is_active' => true,
            // password intentionally left blank/random; if customers need login flow, set appropriately
            'password' => bcrypt(\Illuminate\Support\Str::random(32)),
        ]));

        return redirect()->route('customer.index')->with('status', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer): View
    {
        return view('customer.show', [
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer): View
    {
        return view('customer.form', [
            'customer' => $customer,
            'type' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $customer): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'phone' => 'required|numeric|unique:users,phone,' . $customer->id,
            'address' => 'required|string'
        ]);

        $customer->update($request->only(['name','phone','address']));

        return redirect()->route('customer.index')->with('status', 'Pelanggan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        
        // Cek apakah customer memiliki transaksi yang belum selesai
        $hasActiveTransactions = $user->transactions()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
            
        $hasActiveMarketplaceOrders = $user->marketplaceOrders()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasActiveTransactions || $hasActiveMarketplaceOrders) {
            return redirect()->route('customer.index')
                ->with('error', 'Pelanggan tidak dapat dinonaktifkan karena masih memiliki transaksi yang aktif');
        }

        // Nonaktifkan user
        $user->is_active = false;
        $user->save();

        return redirect()->route('customer.index')
            ->with('status', 'Akun pelanggan berhasil dinonaktifkan');
    }
}
