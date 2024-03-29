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
Route::get('/deals', [App\Http\Controllers\DealController::class, 'index'])->name('deals');
Route::get('/success', [App\Http\Controllers\CartController::class, 'success'])->name('success')->middleware('auth');;

Route::post('order', [App\Http\Controllers\OrderController::class, 'AddToCart']);
Route::post('deals', [App\Http\Controllers\DealController::class, 'AddRemoveDeal']);
Route::post('cart', [App\Http\Controllers\CartController::class, 'SubmitOrder'])->middleware('auth');

Route::post('savecart', [App\Http\Controllers\CartController::class, 'SaveCart'])->middleware('auth');
Route::post('loadcart', [App\Http\Controllers\CartController::class, 'LoadCart'])->middleware('auth');
Route::post('deletecart', [App\Http\Controllers\CartController::class, 'DeleteCart'])->middleware('auth');

require __DIR__.'/auth.php';
