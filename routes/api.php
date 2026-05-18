<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Tanpa middleware auth — cukup untuk internal POS yang sudah login via web session
Route::get('/products/by-kode/{kode}', [ProductController::class, 'byKode']);