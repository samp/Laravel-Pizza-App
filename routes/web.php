<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/order');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order');

Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');

require __DIR__.'/auth.php';
