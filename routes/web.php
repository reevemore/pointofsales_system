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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'    => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

/*
|--------------------------------------------------------------------------
| Superadmin Routes
| Hanya superadmin yang boleh akses, tidak ada toko_id
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('stores')
    ->name('stores.')
    ->group(function () {
        Route::get('/',          [StoreController::class, 'index'])->name('index');
        Route::get('/create',    [StoreController::class, 'create'])->name('create');
        Route::post('/',         [StoreController::class, 'store'])->name('store');
        Route::get('/{store}/edit',   [StoreController::class, 'edit'])->name('edit');
        Route::patch('/{store}',      [StoreController::class, 'update'])->name('update');
        Route::delete('/{store}',     [StoreController::class, 'destroy'])->name('destroy');

        // Toggle aktif/nonaktif toko
        Route::patch('/{store}/toggle', [StoreController::class, 'toggle'])->name('toggle');
    });

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Admin & Kasir)
| Wajib login + punya toko_id + bukan superadmin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'toko.scope', 'superadmin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile
        Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        /*
        |----------------------------------------------------------------------
        | POS — Admin & Kasir
        |----------------------------------------------------------------------
        */
        Route::get('pos',                          [TransactionController::class, 'index'])->name('pos.index');
        Route::post('pos',                         [TransactionController::class, 'store'])->name('pos.store');
        Route::get('pos/history',                  [TransactionController::class, 'history'])->name('pos.history');
        Route::get('pos/{transaction}/receipt',    [TransactionController::class, 'show'])->name('pos.receipt');
        Route::get('members/{member}/poin',        [MemberController::class, 'poinHistory'])->name('members.poin');

        // Payment Midtrans
        Route::post('payment/transfer',            [PaymentController::class, 'createTransfer'])->name('payment.transfer');
        Route::post('payment/qris',                [PaymentController::class, 'createQris'])->name('payment.qris');
        Route::get('payment/status/{order}',       [PaymentController::class, 'checkStatus'])->name('payment.status');

        /*
        |----------------------------------------------------------------------
        | Admin Only
        |----------------------------------------------------------------------
        */
        Route::middleware('role:admin')->group(function () {

            // Master Data
            Route::resource('members',    MemberController::class)->except(['show']);
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('products',   ProductController::class)->except(['show']);

            // Laporan
            Route::prefix('laporan')->name('laporan.')->group(function () {
                Route::get('/',                    [LaporanController::class, 'index'])->name('index');
                Route::get('export-pdf',           [LaporanController::class, 'exportPdf'])->name('pdf');
                Route::get('export-excel',         [LaporanController::class, 'exportExcel'])->name('excel');
                Route::get('profit',               [LaporanController::class, 'profit'])->name('profit');
                Route::get('profit/export-excel',  [LaporanController::class, 'profitExportExcel'])->name('profit.excel');
                Route::get('profit/export-pdf',    [LaporanController::class, 'profitExportPdf'])->name('profit.pdf');
            });

            // Users (manajemen kasir oleh admin toko)
            Route::resource('users', UserController::class)->except(['show']);

            // Stok
            Route::get('stock',                    [StockController::class, 'index'])->name('stock.index');
            Route::post('stock',                   [StockController::class, 'store'])->name('stock.store');
            Route::get('stock/{product}/history',  [StockController::class, 'history'])->name('stock.history');
        });
    });

/*
|--------------------------------------------------------------------------
| Midtrans Webhook — Harus publik (tanpa CSRF)
|--------------------------------------------------------------------------
*/
Route::post('payment/notification', [PaymentController::class, 'notification'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('payment.notification');

require __DIR__.'/auth.php';