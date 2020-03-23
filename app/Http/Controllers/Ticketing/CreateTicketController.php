<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;

class CreateTicketController extends Controller
{

    protected function storeTicket(Request $request){
        
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