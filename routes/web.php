<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS — semua role (admin & kasir)
    Route::get('pos', [TransactionController::class, 'index'])->name('pos.index');
    Route::post('pos', [TransactionController::class, 'store'])->name('pos.store');
    Route::get('pos/history', [TransactionController::class, 'history'])->name('pos.history');
    Route::get('pos/{transaction}/receipt', [TransactionController::class, 'show'])->name('pos.receipt');
    Route::get('members/{member}/poin', [MemberController::class, 'poinHistory'])->name('members.poin');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('members', MemberController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('stock', [StockController::class, 'store'])->name('stock.store');
        Route::get('stock/{product}/history', [StockController::class, 'history'])->name('stock.history');
    });
});

require __DIR__.'/auth.php';