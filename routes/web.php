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

Route::get('/', [FrontController::class, 'index'])->name('welcome')->middleware('ip.restriction:frontend');
Route::get('/sub', [FrontController::class, 'subPage'])->name('front.sub')->middleware('ip.restriction:frontend');
Route::get('/legal', [FrontController::class, 'legal'])->name('legal')->middleware('ip.restriction:frontend');
Route::get('/privacy', [FrontController::class, 'privacy'])->name('privacy')->middleware('ip.restriction:frontend');

// 商品関連のルート
Route::prefix('products')->name('products.')->middleware('ip.restriction:frontend')->group(function() {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/featured', [ProductController::class, 'featured'])->name('featured');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// カテゴリー関連のルート
Route::prefix('categories')->name('categories.')->middleware('ip.restriction:frontend')->group(function() {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
});

// カート関連のルート
Route::prefix('cart')->name('cart.')->middleware('ip.restriction:frontend')->group(function() {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// 配送先住所関連のルート
Route::middleware(['auth', 'ip.restriction:frontend'])->prefix('shipping-addresses')->name('shipping_addresses.')->group(function() {
    Route::get('/', [ShippingAddressController::class, 'index'])->name('index');
    Route::get('/create', [ShippingAddressController::class, 'create'])->name('create');
    Route::post('/', [ShippingAddressController::class, 'store'])->name('store');
    Route::get('/{shippingAddress}/edit', [ShippingAddressController::class, 'edit'])->name('edit');
    Route::patch('/{shippingAddress}', [ShippingAddressController::class, 'update'])->name('update');
    Route::delete('/{shippingAddress}', [ShippingAddressController::class, 'destroy'])->name('destroy');
    Route::patch('/{shippingAddress}/set-default', [ShippingAddressController::class, 'setDefault'])->name('set_default');
});

// チェックアウト関連のルート
Route::middleware(['auth', 'ip.restriction:frontend'])->prefix('checkout')->name('checkout.')->group(function() {
    Route::get('/address', [CheckoutController::class, 'address'])->name('address');
    Route::post('/address', [CheckoutController::class, 'selectAddress'])->name('select_address');
    Route::get('/shipping', [CheckoutController::class, 'shipping'])->name('shipping');
    Route::post('/shipping', [CheckoutController::class, 'selectShipping'])->name('select_shipping');
    Route::get('/payment-method', [CheckoutController::class, 'paymentMethod'])->name('payment-method');
    Route::post('/payment-method', [CheckoutController::class, 'storePaymentMethod'])->name('payment-method.store');
    Route::get('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/complete/{order}', [CheckoutController::class, 'complete'])->name('complete');
});

// 注文関連のルート
Route::middleware(['auth', 'ip.restriction:frontend'])->prefix('orders')->name('orders.')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::get('/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('invoice');
    Route::get('/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('receipt');
});

Route::get('/mypage', function () {
    return view('mypage');
})->middleware(['auth', 'verified', 'ip.restriction:frontend'])->name('mypage');

Route::middleware(['auth', 'ip.restriction:frontend'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 管理者ルート
Route::prefix(ltrim(config('app.admin_url', 'admin'), '/'))->name('admin.')->middleware(['auth', 'admin', 'ip.restriction:admin'])->group(function () {
    // ダッシュボード
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // 商品管理
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // カテゴリー管理
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // 注文管理
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/confirm-bank-transfer', [App\Http\Controllers\Admin\OrderController::class, 'confirmBankTransfer'])->name('orders.confirm-bank-transfer');
    Route::post('/orders/{order}/mark-bank-transfer-expired', [App\Http\Controllers\Admin\OrderController::class, 'markBankTransferExpired'])->name('orders.mark-bank-transfer-expired');
    
    // 銀行振込管理
    Route::prefix('bank-transfers')->name('bank-transfers.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\BankTransferController::class, 'index'])->name('index');
        Route::get('/{bankTransfer}', [App\Http\Controllers\Admin\BankTransferController::class, 'show'])->name('show');
        Route::patch('/{bankTransfer}/confirm', [App\Http\Controllers\Admin\BankTransferController::class, 'confirm'])->name('confirm');
        Route::patch('/{bankTransfer}/mark-expired', [App\Http\Controllers\Admin\BankTransferController::class, 'markExpired'])->name('mark-expired');
        Route::post('/bulk-mark-expired', [App\Http\Controllers\Admin\BankTransferController::class, 'bulkMarkExpired'])->name('bulk-mark-expired');
    });
    
    // ユーザー管理
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
    
    // 売上レポート
    Route::get('/reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/products', [App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
    
    
    // ショップ設定
    Route::prefix('shop-settings')->name('shop-settings.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\ShopSettingController::class, 'index'])->name('index');
        
        // 基本情報
        Route::get('/basic-info', [App\Http\Controllers\Admin\ShopSettingController::class, 'basicInfo'])->name('basic-info');
        Route::patch('/basic-info', [App\Http\Controllers\Admin\ShopSettingController::class, 'updateBasicInfo'])->name('basic-info.update');
        
        // 配送設定
        Route::get('/shipping', [App\Http\Controllers\Admin\ShopSettingController::class, 'shipping'])->name('shipping');
        Route::get('/shipping/create', [App\Http\Controllers\Admin\ShopSettingController::class, 'createShipping'])->name('shipping.create');
        Route::get('/shipping/{id}/edit', [App\Http\Controllers\Admin\ShopSettingController::class, 'editShipping'])->name('shipping.edit');
        Route::patch('/shipping', [App\Http\Controllers\Admin\ShopSettingController::class, 'updateShipping'])->name('shipping.update');
        Route::patch('/shipping/{id}/toggle', [App\Http\Controllers\Admin\ShopSettingController::class, 'toggleShippingStatus'])->name('shipping.toggle');
        Route::delete('/shipping', [App\Http\Controllers\Admin\ShopSettingController::class, 'deleteShipping'])->name('shipping.delete');
        
        // 特定商取引法
        Route::get('/legal', [App\Http\Controllers\Admin\ShopSettingController::class, 'legal'])->name('legal');
        Route::patch('/legal', [App\Http\Controllers\Admin\ShopSettingController::class, 'updateLegal'])->name('legal.update');
        
        // プライバシーポリシー
        Route::get('/privacy', [App\Http\Controllers\Admin\ShopSettingController::class, 'privacy'])->name('privacy');
        Route::patch('/privacy', [App\Http\Controllers\Admin\ShopSettingController::class, 'updatePrivacy'])->name('privacy.update');
        
        // 決済設定
        Route::get('/payment', [App\Http\Controllers\Admin\ShopSettingController::class, 'payment'])->name('payment');
        Route::post('/payment/toggle', [App\Http\Controllers\Admin\ShopSettingController::class, 'togglePaymentMethod'])->name('payment.toggle');
        Route::get('/payment/{method}', [App\Http\Controllers\Admin\ShopSettingController::class, 'paymentMethod'])->name('payment.method');
        Route::patch('/payment/{method}', [App\Http\Controllers\Admin\ShopSettingController::class, 'updatePaymentMethod'])->name('payment.method.update');
    });
    
    // システム設定
    Route::prefix('system')->name('system.')->group(function() {
        // システム情報
        Route::get('/info', [App\Http\Controllers\Admin\SystemController::class, 'info'])->name('info');
        Route::get('/info/details', [App\Http\Controllers\Admin\SystemController::class, 'infoDetails'])->name('info.details');
        
        // メンテナンスモード
        Route::get('/maintenance', [App\Http\Controllers\Admin\SystemController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance/toggle', [App\Http\Controllers\Admin\SystemController::class, 'toggleMaintenance'])->name('maintenance.toggle');
        
        // ログイン履歴
        Route::get('/login-history', [App\Http\Controllers\Admin\SystemController::class, 'loginHistory'])->name('login-history');
        
        // セキュリティ監視
        Route::get('/security', [App\Http\Controllers\Admin\SystemController::class, 'security'])->name('security');
        
        // セキュリティ設定
        Route::get('/security-settings', [App\Http\Controllers\Admin\SystemController::class, 'securitySettings'])->name('security-settings');
        Route::post('/security-settings', [App\Http\Controllers\Admin\SystemController::class, 'updateSecuritySettings'])->name('security-settings.update');
    });
    
    // メール設定
    Route::prefix('email-settings')->name('email-settings.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('update');
        Route::post('/reset', [App\Http\Controllers\Admin\EmailSettingController::class, 'reset'])->name('reset');
        Route::post('/toggle', [App\Http\Controllers\Admin\EmailSettingController::class, 'toggle'])->name('toggle');
        Route::get('/{type}', [App\Http\Controllers\Admin\EmailSettingController::class, 'edit'])->name('edit');
        Route::patch('/{type}', [App\Http\Controllers\Admin\EmailSettingController::class, 'updateSingle'])->name('update-single');
    });
});

require __DIR__.'/auth.php';
