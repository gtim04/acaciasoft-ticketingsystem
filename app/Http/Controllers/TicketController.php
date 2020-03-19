<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Ticket;

class TicketController extends Controller
{
    public function indexUser(){
    	// dd(Datatables::of(Ticket::get()));
    	
    	$id = auth()->user()->id;
        $tickets = Ticket::with('user')->where('user_id', $id)->get();
        // dd(Datatables::of($tickets)->toJson());
        // $tickets->

        // $tickets = Ticket::
        //             select('code')
        //             ->where('user_id', 1)
        //             ->get();
        // $tickets = Ticket::
        //             select('name', 'code', 'description', 'ticket_handler', 'created_at')
        //             ->where('user_id', 1)
        //             ->get();
        // $data = merge([$user, $tickets]);

    	// dd($tickets);
    	// dd($id);
    	// dd(Datatables::of($data));
    	return Datatables::of($tickets)->toJson();
    }
}
