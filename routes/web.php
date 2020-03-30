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
});

Route::group(['middleware' => ['auth', 'user'], 'prefix' => 'user', 'as' => 'user.'], function (){
	//dashboard
	Route::get('/', 'Ticketing\DashController@userIndex')->name('dashboard');
	//resolvedtickets
	Route::get('/showresolved', 'Ticketing\UserResolvedController@indexResolved')->name('sresolved');
	Route::post('/getrestickets', 'Ticketing\UserResolvedController@getResolved')->name('resolved');
	//newtickets
	Route::get('/newticket', 'Ticketing\CreateTicketController@indexCreate')->name('newticket');
	Route::post('/submitticket', 'Ticketing\CreateTicketController@storeTicket')->name('submit');
	//pop modal
	Route::post('/showticket', 'Ticketing\PopTicketController@showTicket')->name('sticket');
	Route::post('/editticket', 'Ticketing\PopTicketController@editTicket')->name('edit');
	Route::post('/deleteticket', 'Ticketing\PopTicketController@deleteTicket')->name('delete');
	//pending tickets
	Route::get('/showpending', 'Ticketing\UserPendingController@indexPending')->name('spending');
	Route::post('/getpentickets', 'Ticketing\UserPendingController@getPending')->name('pending');
	//deletedtickets
	Route::get('/showdeleted', 'Ticketing\UserDeletedController@indexDeleted')->name('sdeleted');
	Route::post('/getdeleted', 'Ticketing\UserDeletedController@getDeleted')->name('deleted');
	//resolving tickets
	Route::post('/issueresolved', 'Ticketing\ThreadController@resolveTicket')->name('complete');
	//reopening tickets
	Route::post('/reopenticket', 'Ticketing\ThreadController@reopenTicket')->name('reopen');
	//thread
	Route::post('/submitcomment', 'Ticketing\ThreadController@addComment')->name('comment')->middleware('thread');
	Route::get('/showthread/{id}', 'Ticketing\ThreadController@userThread')->name('thread')->middleware('thread');
});

Route::group(['middleware' => ['auth','admin'], 'prefix' => 'admin', 'as' => 'admin.'], function(){
	//dashboard
	Route::get('/', 'Ticketing\AdminDashController@adminIndex')->name('dashboard');
	Route::post('/gettickets', 'Ticketing\AdminDashController@getTickets')->name('tickets');
	//assigned tickets
	Route::get('/showassigned', 'Ticketing\AdminAssigmentController@indexAssignments')->name('sassignment');
	Route::post('/getassigned', 'Ticketing\AdminAssigmentController@getAssignments')->name('assignment');
	//pop modal
	Route::post('/showticket', 'Ticketing\PopTicketController@showTicket')->name('sticket');
	Route::post('/pickupticket', 'Ticketing\PopTicketController@pickupTicket')->name('pickup');
	//resolvedtickets
	Route::get('/showresolved', 'Ticketing\AdminResolvedController@indexResolved')->name('sresolved');
	Route::post('/getrestickets', 'Ticketing\AdminResolvedController@getResolved')->name('resolved');
	//deletedtickets
	Route::get('/showdeleted', 'Ticketing\AdminDeletedController@indexDeleted')->name('sdeleted');
	Route::post('/getdeleted', 'Ticketing\AdminDeletedController@getDeleted')->name('deleted');
	//thread
	Route::post('/submitcomment', 'Ticketing\ThreadController@addComment')->name('comment')->middleware('thread');
	Route::get('/showthread/{id}', 'Ticketing\ThreadController@adminThread')->name('thread')->middleware('thread');
});

Route::fallback(function () {
	abort(404);
});//fallback
