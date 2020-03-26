<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class PopTicketController extends Controller
{
    protected function showTicket(Request $request){
    	$tickets = Ticket::with('user')->where('code', $request->code)->first();
    	return $tickets->toJson();
    }

    protected function editTicket(Request $request){
    	//updating the new record
  		$datetime = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));
    	$data = Ticket::find($request->id);
        $data->importance = $request->importance;
        $data->title = $request->title;
        $data->description = $request->body;
        $data->issue_date = $datetime;
        $data->save();
        // logs
        $log = new Comment();
        $log->content = "Ticket edited - by: ".auth()->user()->name;
        $log->ticket_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->isLog = 1;
        $log->save();
    }

    protected function deleteTicket(Request $request){
    	//updating completed field
    	$data = Ticket::find($request->id);
        $data->isDeleted = 1;
        $data->status = 'deleted';
        $data->save();
        // logs
        $log = new Comment();
        $log->content = "Ticket deleted - by: ".auth()->user()->name;
        $log->ticket_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->isLog = 1;
        $log->save();
    }

    protected function pickupTicket(Request $request){
        // updating handler field
        $data = Ticket::find($request->id);
        $data->ticket_handler = Auth::id();
        $data->status = 'Pending';
        $data->save();
        //logs
        $log = new Comment();
        $log->content = "Ticket fetched - by: ".auth()->user()->name;
        $log->ticket_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->isLog = 1;
        $log->save();
    }
}
