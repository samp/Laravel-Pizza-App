<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

//Route::get('/', function () {return view('welcome');});

Auth::routes();
Route::redirect('/', '/order');
Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('order', [App\Http\Controllers\OrderController::class, 'index']);
Route::get('cart', [App\Http\Controllers\CartController::class, 'index']);
Route::get('success', [App\Http\Controllers\CartController::class, 'success'])->middleware('auth');;

Route::post('order', [App\Http\Controllers\OrderController::class, 'addtocart']);
Route::post('cart', [App\Http\Controllers\CartController::class, 'submitorder'])->middleware('auth');
