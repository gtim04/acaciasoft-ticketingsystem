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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/getUserTickets', 'TicketController@indexUser')->name('datatables.ticket')->middleware('auth');

Route::get('/newTicket', 'TicketController@indexCreate')->name('index.ticket')->middleware('auth');

Route::post('/submitTicket', 'TicketController@storeTicket')->name('store.ticket')->middleware('auth');

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::fallback(function () {
    abort(404);
});