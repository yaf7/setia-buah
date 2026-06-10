<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetaniController;

Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Authentication Routes untuk Petani & Admin
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Authentication Routes untuk Pembeli
Route::get('/buyer/login', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'showLoginForm'])->name('buyer.login');
Route::post('/buyer/login', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'login'])->name('buyer.login.post');
Route::get('/buyer/register', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'showRegisterForm'])->name('buyer.register');
Route::post('/buyer/register', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'register'])->name('buyer.register.post');
Route::post('/buyer/logout', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'logout'])->name('buyer.logout');

// ROUTE SHOP ECOMMERCE PEMBELI (Browsing Tanpa Login)
Route::group(['middleware' => [\Illuminate\Session\Middleware\StartSession::class ?? 'web']], function () {
    Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
});

// ROUTE KERANJANG, CHECKOUT & ORDER (Wajib Login Pembeli)
Route::group(['middleware' => [\Illuminate\Session\Middleware\StartSession::class ?? 'web', 'auth:buyer']], function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/shipping-rates', [\App\Http\Controllers\CheckoutController::class, 'shippingRates'])->name('checkout.shipping-rates');
    
    Route::get('/track/{order}', [\App\Http\Controllers\BuyerOrderController::class, 'track'])->name('orders.track');
});

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransCallbackController::class, 'handle'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
    ->name('midtrans.callback');

// Buyer Dashboard Routes
Route::middleware(['auth:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\BuyerOrderController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\BuyerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\BuyerProfileController::class, 'update'])->name('profile.update');
    Route::post('/orders/{order}/receive', [\App\Http\Controllers\BuyerOrderController::class, 'markReceived'])->name('orders.receive');
});

Route::middleware(['auth:petani', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\PetaniController::class, 'dashboard'])->name('dashboard');
    Route::post('/location', [\App\Http\Controllers\PetaniController::class, 'updateLocation'])->name('location.update');
    Route::resource('products', \App\Http\Controllers\PetaniController::class)->except(['index', 'show']);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\AdminDashboardController::class)->name('dashboard');
    
    // === SUPPLY CHAIN FLOW ROUTES ===
    
    // 1. Estimasi Panen → Persetujuan Admin
    Route::get('/procurement/pending', [\App\Http\Controllers\AdminProcurementController::class, 'pendingApproval'])->name('procurement.pending');
    Route::post('/procurement/{estimate}/approve', [\App\Http\Controllers\AdminProcurementController::class, 'approve'])->name('procurement.approve');
    Route::post('/procurement/{estimate}/reject', [\App\Http\Controllers\AdminProcurementController::class, 'reject'])->name('procurement.reject');
    
    // 2. Transaksi Pengadaan
    Route::get('/procurement', [\App\Http\Controllers\AdminProcurementController::class, 'index'])->name('procurement.index');
    Route::get('/procurement/{estimate}/create', [\App\Http\Controllers\AdminProcurementController::class, 'create'])->name('procurement.create');
    Route::post('/procurement/{estimate}/store', [\App\Http\Controllers\AdminProcurementController::class, 'store'])->name('procurement.store');
    Route::get('/procurement/{procurement}/show', [\App\Http\Controllers\AdminProcurementController::class, 'show'])->name('procurement.show');
    
    // 3. Pengiriman / Penjemputan
    Route::post('/procurement/{procurement}/ship', [\App\Http\Controllers\AdminProcurementController::class, 'markShipping'])->name('procurement.ship');
    
    // 4. Penerimaan Gudang
    Route::post('/procurement/{procurement}/receive', [\App\Http\Controllers\AdminProcurementController::class, 'markReceived'])->name('procurement.receive');
    
    // 5. QC (Quality Control)
    Route::get('/qc-queue', \App\Http\Controllers\AdminQcQueueController::class)->name('qc.queue');
    Route::get('/qc/{product}/create', [\App\Http\Controllers\QcController::class, 'create'])->name('qc.create');
    Route::post('/qc/{product}/store', [\App\Http\Controllers\QcController::class, 'store'])->name('qc.store');
    
    // 6. Inventory & Katalog
    Route::get('/inventory', [\App\Http\Controllers\AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::delete('/inventory/{inventory}', [\App\Http\Controllers\AdminInventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // === PETANI MANAGEMENT ===
    Route::get('/petani', [\App\Http\Controllers\AdminPetaniController::class, 'index'])->name('petani.index');
    Route::post('/tambah-petani', [\App\Http\Controllers\AdminPetaniController::class, 'store'])->name('petani.store');
    Route::get('/petani/{user}/edit', [\App\Http\Controllers\AdminPetaniController::class, 'edit'])->name('petani.edit');
    Route::put('/petani/{user}', [\App\Http\Controllers\AdminPetaniController::class, 'update'])->name('petani.update');
    Route::delete('/petani/{user}', [\App\Http\Controllers\AdminPetaniController::class, 'destroy'])->name('petani.destroy');
    
    // === ORDERS (PENJUALAN) ===
    Route::get('/orders', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/history', [\App\Http\Controllers\AdminOrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [\App\Http\Controllers\AdminOrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [\App\Http\Controllers\AdminOrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/shipped', [\App\Http\Controllers\AdminOrderController::class, 'markShipped'])->name('orders.shipped');
    Route::post('/orders/{order}/payment-success', [\App\Http\Controllers\AdminOrderController::class, 'markPaymentSuccess'])->name('orders.payment-success');
    Route::post('/orders/{order}/check-payment-status', [\App\Http\Controllers\AdminOrderController::class, 'checkPaymentStatus'])->name('orders.check-payment-status');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Orders Mock Route for visually demonstrating Receipt
    Route::get('/orders/{order}/receipt', function (\App\Models\Order $order) {
        return view('admin.orders.receipt', compact('order'));
    })->name('orders.receipt');
});
