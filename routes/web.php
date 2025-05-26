<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingAddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontController::class, 'index'])->name('welcome');
Route::get('/sub', [FrontController::class, 'subPage'])->name('front.sub');

// 商品関連のルート
Route::prefix('products')->name('products.')->group(function() {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/featured', [ProductController::class, 'featured'])->name('featured');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// カテゴリー関連のルート
Route::prefix('categories')->name('categories.')->group(function() {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
});

// カート関連のルート
Route::prefix('cart')->name('cart.')->group(function() {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// 配送先住所関連のルート
Route::middleware('auth')->prefix('shipping-addresses')->name('shipping_addresses.')->group(function() {
    Route::get('/', [ShippingAddressController::class, 'index'])->name('index');
    Route::get('/create', [ShippingAddressController::class, 'create'])->name('create');
    Route::post('/', [ShippingAddressController::class, 'store'])->name('store');
    Route::get('/{shippingAddress}/edit', [ShippingAddressController::class, 'edit'])->name('edit');
    Route::patch('/{shippingAddress}', [ShippingAddressController::class, 'update'])->name('update');
    Route::delete('/{shippingAddress}', [ShippingAddressController::class, 'destroy'])->name('destroy');
    Route::patch('/{shippingAddress}/set-default', [ShippingAddressController::class, 'setDefault'])->name('set_default');
});

// チェックアウト関連のルート
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function() {
    Route::get('/address', [CheckoutController::class, 'address'])->name('address');
    Route::post('/address', [CheckoutController::class, 'selectAddress'])->name('select_address');
    Route::get('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/complete/{order}', [CheckoutController::class, 'complete'])->name('complete');
});

// 注文関連のルート
Route::middleware('auth')->prefix('orders')->name('orders.')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
