<?php

use Illuminate\Support\Facades\Route;
use Braintree\Gateway;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/cart', 'GuestController@cart')->name('cart');
Route::post('/cart', 'GuestController@checkout')->name('cart.checkout');
Route::get('/home', 'HomeController@index')->name('home');
