<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
