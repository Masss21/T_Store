<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest dapat akses - Browsing Produk)
|--------------------------------------------------------------------------
*/

// Home & Product Browsing (No Auth Required)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User harus login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard Redirect
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| User Routes (Butuh Login - Cart, Wishlist, Checkout)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    // Cart (Harus Login)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    // Wishlist (Harus Login)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/{wishlist}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    
    // Checkout (Harus Login)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.applyVoucher');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Vouchers
    Route::resource('vouchers', VoucherController::class);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/payment', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
    Route::post('/customers/{customer}/update-status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}/detail', [CustomerController::class, 'detail'])->name('customers.detail'); // ✅ BARU
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show'); // Tetap ada untuk backup
    // ... existing routes ...
    });