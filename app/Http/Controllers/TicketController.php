<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Ticket;

class TicketController extends Controller
{
    public function indexUser(){
    	// dd(Datatables::of(Ticket::get()));
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
        $id = auth()->user()->id; //get current user id
        $tickets = Ticket::with('user')->where('user_id', $id)->get(); //join 2 tables
        return Datatables::of($tickets)->toJson(); //pass data tables
    }

    public function indexCreate(){
        return view('user.create');
    }

    public function storeTicket(Request $request){
        
        $lastTicket = Ticket::latest()->first();

        if($lastTicket){
            $lastTicket = $lastTicket->id + 1;
        } else {
            $lastTicket = 1;
        }//getting index for last ticket

        //converting variables to be inserted
        $datetime = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));
        $dtCode = preg_replace('/[\s-:]+/', '' ,$datetime);
        $data = new Ticket();
        $code = 'AST-' .$dtCode. '-' . strtoupper($request->importance). '-' .$lastTicket;

        //inserting the new record
        $data->code = $code;
        $data->importance = $request->importance;
        $data->user_id = auth()->user()->id;
        $data->title = $request->title;
        $data->description = $request->body;
        $data->issue_date = $datetime;
        $data->save();
    }
}
