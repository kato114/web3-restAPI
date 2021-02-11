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

Route::any('/create', 'ApiController@create')->name('create');
Route::any('/transfer', 'ApiController@transfer')->name('transfer');
Route::any('/transaction', 'ApiController@transaction')->name('transaction');
Route::any('/acclist', 'ApiController@acclist')->name('acclist');

