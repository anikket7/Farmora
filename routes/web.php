<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Consumer;
use App\Http\Controllers\Farmer;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Language switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'hi'])) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password', [PasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Pending approval page
Route::get('/pending-approval', function () {
    return view('auth.pending-approval');
})->middleware('auth')->name('pending.approval');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/approve', [Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [Admin\UserController::class, 'reject'])->name('users.reject');
    Route::post('/users/{user}/suspend', [Admin\UserController::class, 'suspend'])->name('users.suspend');

    // Category management
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/{category}/toggle', [Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Product management
    Route::get('/products', [Admin\ProductController::class, 'index'])->name('products.index');
    Route::post('/products/{product}/approve', [Admin\ProductController::class, 'approve'])->name('products.approve');
    Route::delete('/products/{product}', [Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Bid management
    Route::get('/bids', [Admin\BidController::class, 'index'])->name('bids.index');
    Route::post('/bids/{bidSession}/close', [Admin\BidController::class, 'forceClose'])->name('bids.close');
    Route::post('/bids/{bidSession}/cancel', [Admin\BidController::class, 'cancel'])->name('bids.cancel');
});

/*
|--------------------------------------------------------------------------
| Farmer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('farmer')->middleware(['auth', 'farmer', 'approved'])->name('farmer.')->group(function () {
    Route::get('/dashboard', [Farmer\DashboardController::class, 'index'])->name('dashboard');

    // Product CRUD
    Route::get('/products', [Farmer\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [Farmer\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [Farmer\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [Farmer\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [Farmer\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [Farmer\ProductController::class, 'destroy'])->name('products.destroy');

    // Bid management
    Route::get('/bids', [Farmer\BidController::class, 'index'])->name('bids.index');
    Route::post('/bids/{bidSession}/close', [Farmer\BidController::class, 'close'])->name('bids.close');

    // Order management
    Route::get('/orders', [Farmer\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [Farmer\OrderController::class, 'updateStatus'])->name('orders.status');
});

/*
|--------------------------------------------------------------------------
| Consumer / Marketplace Routes
|--------------------------------------------------------------------------
*/
Route::get('/marketplace', [Consumer\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/{product}', [Consumer\MarketplaceController::class, 'show'])->name('marketplace.show');

Route::middleware(['auth', 'consumer', 'approved'])->group(function () {
    // Bidding
    Route::post('/bids/{bidSession}', [Consumer\BidController::class, 'place'])->name('bids.place');
    Route::get('/consumer/bids', [Consumer\BidController::class, 'index'])->name('consumer.bids');

    // Cart
    Route::get('/cart', [Consumer\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [Consumer\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [Consumer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [Consumer\CartController::class, 'remove'])->name('cart.remove');

    // Orders
    Route::get('/checkout', [Consumer\OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [Consumer\OrderController::class, 'store'])->name('orders.store');
    Route::get('/consumer/orders', [Consumer\OrderController::class, 'index'])->name('consumer.orders');
    Route::post('/consumer/orders/{order}/cancel', [Consumer\OrderController::class, 'cancel'])->name('consumer.orders.cancel');
});
