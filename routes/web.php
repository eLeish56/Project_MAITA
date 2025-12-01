<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewPurchaseOrderController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceCartController;
use App\Http\Controllers\MarketplaceOrderController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSupervisor;
use App\Http\Middleware\IsAdminOrSupervisor;
use App\Http\Middleware\IsCashier;

use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\StockMovementController;


Route::middleware('auth')->group(function () {
    // Stock Movement Analysis Routes under inventory
    // Stock Movement Analysis Routes (Independent Menu)
    Route::prefix('stock-movement')->name('stock-movement.')->middleware(IsAdminOrSupervisor::class)->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/fast-moving', [StockMovementController::class, 'fastMoving'])->name('fast-moving');
        Route::get('/slow-moving', [StockMovementController::class, 'slowMoving'])->name('slow-moving');
        Route::get('/settings', [StockMovementController::class, 'settings'])->name('settings');
        Route::put('/settings', [StockMovementController::class, 'updateSettings'])->name('settings.update');
        Route::get('/analyze', [StockMovementController::class, 'analyze'])->name('analyze');
        Route::get('/export', [StockMovementController::class, 'export'])->name('export');
    });
    
    // Procurement Management Routes
    Route::prefix('procurement')->group(function () {
        // Dashboard Overview (admin or supervisor)
        Route::get('/', [ProcurementController::class, 'index'])
            ->name('procurement.index')
            ->middleware(IsAdminOrSupervisor::class);
        
        // Purchase Request Routes
        Route::prefix('purchase-requests')->name('purchase-requests.')->group(function () {
            // List and Form Routes
            Route::get('/', [PurchaseRequestController::class, 'index'])->name('index');
            Route::get('/create', [PurchaseRequestController::class, 'create'])->name('create');
            Route::post('/', [PurchaseRequestController::class, 'store'])->name('store');
            Route::get('/{purchase_request}', [PurchaseRequestController::class, 'show'])->name('show');
            Route::get('/{purchase_request}/pdf', [PurchaseRequestController::class, 'generatePDF'])->name('pdf');
            
            // Supplier Products API
            Route::get('/get-supplier-items/{supplier}', [PurchaseRequestController::class, 'getSupplierItems'])
                ->name('get.supplier.items');
            
            // Alternative API endpoint for supplier products
            Route::get('/api/suppliers/{supplier}/products', [PurchaseRequestController::class, 'getSupplierItems'])
                ->name('api.supplier.products');
            
            // Approval Routes (Supervisor Only)
            Route::middleware(IsSupervisor::class)->group(function () {
                Route::post('/{purchase_request}/approve', [PurchaseRequestController::class, 'approve'])
                    ->name('approve');
                Route::post('/{purchase_request}/reject', [PurchaseRequestController::class, 'reject'])
                    ->name('reject');
            });
        });
    });

    // New Purchase Order System (admin/supervisor)
    Route::prefix('new-purchase-orders')->name('new-purchase-orders.')->middleware(IsAdminOrSupervisor::class)->group(function () {
        Route::get('/', [NewPurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create-direct/{purchaseRequest}', [NewPurchaseOrderController::class, 'createDirectPO'])->name('create-direct');
        Route::get('/{purchaseOrder}', [NewPurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{purchaseOrder}/pdf', [NewPurchaseOrderController::class, 'generatePDF'])->name('pdf');
        Route::post('/{purchaseOrder}/mark-sent', [NewPurchaseOrderController::class, 'markAsSent'])->name('mark-sent');
    });

    // Goods Receipts
    // Provide custom routes for creating and storing goods receipts tied to a purchase order.
    Route::get('goods-receipts/{purchaseOrder}/create', [GoodsReceiptController::class, 'create'])
        ->name('goods-receipts.create')
        ->middleware(IsAdminOrSupervisor::class);
    Route::post('goods-receipts/{purchaseOrder}', [GoodsReceiptController::class, 'store'])
        ->name('goods-receipts.store')
        ->middleware(IsAdminOrSupervisor::class);
    Route::get('goods-receipts/{goodsReceipt}/document/{type?}', [GoodsReceiptController::class, 'downloadDocument'])
        ->name('goods-receipts.document')
        ->middleware(IsAdminOrSupervisor::class);
    Route::resource('goods-receipts', GoodsReceiptController::class)->except(['create', 'store'])->middleware(IsAdminOrSupervisor::class);

    // Invoices
    // Provide custom routes for creating and storing invoices tied to a purchase order.
    Route::get('invoices/{purchaseOrder}/create', [InvoiceController::class, 'create'])
        ->name('invoices.create')
        ->middleware(IsAdminOrSupervisor::class);
    Route::post('invoices/{purchaseOrder}', [InvoiceController::class, 'store'])
        ->name('invoices.store')
        ->middleware(IsAdminOrSupervisor::class);
    Route::resource('invoices', InvoiceController::class)->except(['create', 'store'])->middleware(IsAdminOrSupervisor::class);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])
        ->name('invoices.mark-paid')
        ->middleware(IsAdminOrSupervisor::class);

    // Inventory Records (Pemeriksaan & Pencatatan)
    Route::get('inventory-records/{goods_receipt}/create', [\App\Http\Controllers\InventoryRecordController::class, 'create'])
        ->name('inventory-records.create')
        ->middleware(IsAdminOrSupervisor::class);
    Route::post('inventory-records/{goods_receipt}', [\App\Http\Controllers\InventoryRecordController::class, 'store'])
        ->name('inventory-records.store')
        ->middleware(IsAdminOrSupervisor::class);
    Route::resource('inventory-records', \App\Http\Controllers\InventoryRecordController::class)->except(['create', 'store'])->middleware(IsAdminOrSupervisor::class);
});

// Removed old PO delete-all route
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

/* ============================
   MARKETPLACE (Publik)
   ============================ */

// Etalase marketplace (publik)
Route::get('/customer/dashboard', [MarketplaceController::class, 'index'])->name('customer.dashboard');
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// Detail produk
Route::get('/marketplace/items/{item}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Keranjang
Route::middleware('auth')->group(function () {
    Route::get('/marketplace/cart', [MarketplaceCartController::class, 'index'])->name('marketplace.cart');
    Route::post('/marketplace/cart/add', [MarketplaceCartController::class, 'add'])->name('marketplace.cart.add');
    Route::post('/marketplace/cart/update', [MarketplaceCartController::class, 'update'])->name('marketplace.cart.update');
    Route::post('/marketplace/cart/remove', [MarketplaceCartController::class, 'remove'])->name('marketplace.cart.remove');
    Route::post('/marketplace/cart/clear', [MarketplaceCartController::class, 'clear'])->name('marketplace.cart.clear');
});

// Checkout (hanya customer terautentikasi)
Route::middleware(['auth', \App\Http\Middleware\IsCustomer::class])->group(function () {
    Route::get('/marketplace/checkout', [MarketplaceOrderController::class, 'create'])->name('marketplace.checkout');
    Route::post('/marketplace/checkout', [MarketplaceOrderController::class, 'store'])->name('marketplace.checkout.store');
    Route::get('/marketplace/orders', [MarketplaceOrderController::class, 'index'])->name('marketplace.order.index');
    Route::get('/marketplace/order/{code}', [MarketplaceOrderController::class, 'show'])->name('marketplace.order.show');

    // Unified logout for all users with proper session cleanup
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
         ->name('logout')
         ->middleware('web');
});

/* ============================
   GUEST (Register & Login)
   ============================ */
// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware(['web', 'auth']);

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'createCustomer'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'storeCustomer'])->name('register.post');
});

// Marketplace Order Processing Routes (POS staff / cashier)
Route::middleware(['auth', IsCashier::class])->group(function() {
    Route::prefix('transaction')->name('transaction.')->group(function () {
        // Online Orders
        Route::get('/online-orders', [TransactionController::class, 'onlineOrders'])->name('online');
        Route::get('/online-orders/{order}/items', [TransactionController::class, 'onlineOrderItems'])->name('online.items');
        Route::post('/online-orders/{order}/process', [TransactionController::class, 'processOnline'])->name('online.process');
        
        // Marketplace Orders
        Route::get('/marketplace-orders', [TransactionController::class, 'marketplaceOnlineOrders'])
            ->name('marketplace.orders');
        Route::get('/marketplace-orders/{order}/items', [TransactionController::class, 'marketplaceOrderItems'])
            ->name('marketplace.order.items');
        Route::post('/marketplace-orders/{order}/process', [TransactionController::class, 'processMarketplaceOrder'])
            ->name('marketplace.order.process');
    });
});


/* ============================
   AUTH (internal apps)
   ============================ */
Route::middleware('auth')->group(function () {
    // Halaman utama: customer diarahkan ke dashboard marketplace, lainnya ke dashboard internal
Route::get('/', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    if ($user->role === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    
    // Semua pegawai (owner, admin, cashier, supervisor) diarahkan ke dashboard admin
    if (in_array($user->role, ['owner', 'admin', 'cashier', 'supervisor'])) {
        return app(DashboardController::class)->index();
    }
    
    return redirect()->route('marketplace.index');
})->name('dashboard');    /*
     * ONLINE ORDERS
     * Kasir melihat daftar pesanan online (status 'debt') dan memprosesnya menjadi 'paid'.
     */
    // Online Orders & Marketplace Routes (internal POS staff)
    Route::prefix('transaction')->name('transaction.')->middleware(IsCashier::class)->group(function () {
        // Marketplace Orders
        Route::get('/marketplace-orders', [TransactionController::class, 'marketplaceOnlineOrders'])->name('marketplace.orders');
        Route::get('/marketplace-orders/{order}/items', [TransactionController::class, 'marketplaceOrderItems'])->name('marketplace.order.items');
        Route::post('/marketplace-orders/{order}/process', [TransactionController::class, 'processMarketplaceOrder'])->name('marketplace.order.process');
        
        // Legacy Online Orders
        Route::get('/online-orders', [TransactionController::class, 'onlineOrders'])->name('online');
        Route::get('/online-orders/{order}/items', [TransactionController::class, 'onlineOrderItems'])->name('online.items');
        Route::post('/online-orders/{order}/process', [TransactionController::class, 'processOnline'])->name('online.process');
    });


    /*
     * TRANSACTIONS
     * Resource transaksi dengan pengecualian create/edit/update, serta rute tambahan
     * untuk get-invoice, get-items dan save transaksi yang dikelompokkan dalam prefix 'transaction'.
     */
    Route::prefix('transaction')->group(function () {
        Route::get('/get-invoice', [TransactionController::class, 'get_invoice'])->name('transaction.get_invoice');
        Route::get('/get-items', [TransactionController::class, 'get_items'])->name('transaction.get_items');
        Route::post('/save', [TransactionController::class, 'save_transaction'])->name('transaction.save');
        Route::get('/{transaction}/print-receipt', [TransactionController::class, 'printReceipt'])->name('transaction.print-receipt');
    });
    Route::resource('transaction', TransactionController::class)->except(['create','edit','update']);

    // API endpoints
    Route::get('/api/transaction-by-invoice/{invoice}', [TransactionController::class, 'getTransactionByInvoice']);
    Route::get('/api/last-transaction', [TransactionController::class, 'getLastTransaction']);

    /*
     * INVENTORY
     */
    Route::prefix('inventory')->middleware(IsAdminOrSupervisor::class)->group(function () {
        Route::resource('category', CategoryController::class)->except('show');
        Route::resource('supplier', SupplierController::class);
        Route::resource('item', ItemController::class);
        
        // Batch management routes
        Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
        Route::get('/batches/item/{item}', [BatchController::class, 'itemBatches'])->name('batches.item');
        Route::post('/batches/update-status', [BatchController::class, 'updateExpiryStatus'])->name('batches.update-status');
    });

    /*
     * USER MANAGEMENT (SUPERVISOR ONLY)
     */
    Route::resource('user', UserController::class)
         ->except('show')
         ->middleware(IsSupervisor::class);

    /*
     * CUSTOMER CRUD (internal)
     */
    Route::resource('customer', CustomerController::class)->middleware(IsAdmin::class);

    /*
     * REPORT
     */
    Route::prefix('report')->middleware(IsAdminOrSupervisor::class)->group(function () {
        Route::get('/transaction/filter', [ReportController::class, 'filter'])->name('report.transaction.filter');
        Route::get('/export-sale', [ReportController::class, 'exportSale'])->name('report.export-sale');
        Route::resource('transaction', ReportController::class)
             ->names('report.transaction')
             ->only(['index','show']);
             
        // Purchase Reports
        Route::get('/purchases', [PurchaseReportController::class, 'index'])->name('reports.purchases.index');
        Route::get('/purchases/export', [PurchaseReportController::class, 'export'])->name('reports.purchases.export');
        Route::get('/purchases/{id}', [PurchaseReportController::class, 'show'])->name('reports.purchases.show');
    });

    /*
     * ABSENCE & PAYMENT METHOD
     */
    Route::resource('absence', AbsenceController::class)->except('show')->middleware(IsAdminOrSupervisor::class);
    Route::resource('payment-method', PaymentMethodController::class)->except('show')->middleware(IsAdminOrSupervisor::class);

    /*
     * PROFILE
     */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
     * ITEM ENDPOINTS for POS
     */
    Route::prefix('inventory')->middleware(IsCashier::class)->group(function () {
        Route::get('/item/{item}', [ItemController::class, 'show']);
    });

    /*
     * CART internal POS
     */
    Route::prefix('cart')->middleware(IsCashier::class)->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'store'])->name('cart.store');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/count-stock/{item}', [CartController::class, 'count_stock']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
        Route::get('/{item:code}', [ItemController::class, 'show']);
        Route::put('/{cart}', [CartController::class, 'update']);
    });

    /*
     * API & PURCHASE ORDERS
     */
    Route::get('/api/suppliers/{supplier}/items', [App\Http\Controllers\Api\SupplierProductController::class, 'index'])
        ->name('api.supplier.products');
    
    // Error logging endpoint
    Route::post('/api/log-error', [App\Http\Controllers\Api\ErrorLogController::class, 'store'])
        ->name('api.log-error');
        
    // Monthly Income Chart Data
    Route::get('/api/monthly-income', [DashboardController::class, 'getMonthlyIncome'])
        ->name('api.monthly-income');

    // New Purchase Order System
    Route::prefix('new-purchase-orders')->name('new-purchase-orders.')->middleware(IsAdminOrSupervisor::class)->group(function () {
        Route::get('/', [NewPurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create-direct/{purchaseRequest}', [NewPurchaseOrderController::class, 'createDirectPO'])->name('create-direct');
        Route::get('/{po}', [NewPurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{po}/pdf', [NewPurchaseOrderController::class, 'generatePDF'])->name('pdf');
        Route::post('/{po}/confirm', [NewPurchaseOrderController::class, 'confirm'])->name('confirm');
        Route::post('/{purchaseOrder}/create-gr', [GoodsReceiptController::class, 'createGR'])->name('create-gr');
        Route::post('/{po}/create-invoice', [NewPurchaseOrderController::class, 'createInvoice'])->name('create-invoice');
        
        // Invoice file download routes
        Route::get('/invoice/{invoice}/download', [NewPurchaseOrderController::class, 'downloadInvoiceFile'])->name('invoice.download');
        Route::get('/invoice/{invoice}/payment-proof', [NewPurchaseOrderController::class, 'downloadPaymentProof'])->name('payment-proof.download');
        
        // Price management routes
        Route::get('/{po}/prices/edit', [NewPurchaseOrderController::class, 'editPrices'])->name('prices.edit');
        Route::put('/{po}/prices', [NewPurchaseOrderController::class, 'updatePrices'])->name('prices.update');
        Route::post('/{po}/prices/confirm', [NewPurchaseOrderController::class, 'confirmPrices'])->name('prices.confirm');
    });
});

// Auth scaffolding
require __DIR__.'/auth.php';
