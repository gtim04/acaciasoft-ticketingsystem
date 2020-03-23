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

Auth::routes();

Route::get('/', function () {
	Auth::logout();
	return redirect('login');
})->middleware('auth');

Route::group(['middleware' => ['auth', 'user'], 'prefix' => 'user', 'as' => 'user.'], function (){
	Route::get('/', 'Ticketing\HomeController@userIndex')->name('dashboard');
	Route::get('/gettickets', 'Ticketing\DashController@getTickets')->name('tickets');
	Route::get('/newticket', 'Ticketing\CreateTicketController@indexCreate')->name('newticket');
	Route::post('/submitticket', 'Ticketing\CreateTicketController@storeTicket')->name('submit');
	Route::post('/showticket', 'Ticketing\PopTicketController@showTicket')->name('sticket');
	Route::post('/editticket', 'Ticketing\PopTicketController@editTicket')->name('edit');
	Route::post('/resolveticket', 'Ticketing\PopTicketController@completeTicket')->name('resolved');
});

Route::group(['middleware' => ['auth','admin'], 'prefix' => 'admin', 'as' => 'admin.'], function(){
	Route::get('/', 'Ticketing\HomeController@adminIndex')->name('dashboard');
	Route::get('/gettickets', 'Ticketing\AdminDashController@getTickets')->name('tickets');
});

Route::fallback(function () {
	abort(404);
});//fallback
