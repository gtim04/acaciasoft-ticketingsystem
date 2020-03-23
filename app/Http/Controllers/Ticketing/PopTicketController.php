<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;

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
        // return true;
    }

    protected function completeTicket(Request $request){
    	//inserting the new record
    	$data = Ticket::find($request->id);
        $data->completed = 1;
        $data->save();
        // return true;
    }
}