<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Comment;

class CreateTicketController extends Controller
{

    protected function indexCreate(){
        return view('user.create');
    }

    protected function storeTicket(Request $request){
        
        //validating the request
        $request->validate([
            'importance' => 'required',
            'title' => 'required',
            'description' => ['required', function($attribute, $value, $fail){
                                if($value == '<p><br></p>'){
                                    $fail('The :attribute field is empty.');
                                }
                            }],
            'date' => 'required',
            'time' => 'required'
        ]);

        //getting index for last ticket
        $lastTicket = Ticket::latest()->first();
        if($lastTicket){
            $lastTicket = $lastTicket->id + 1;
        } else {
            $lastTicket = 1;
        }

        //converting variables to be inserted
        $datetime = date('Y-m-d H:i:s', strtotime('$request->date $request->time'));
        $code = 'AST-' .preg_replace('/[\s-:]+/', '' ,$datetime). '-' . strtoupper($request->importance). '-' .$lastTicket;

        //mass assignment
        $ticket = Ticket::create([
            'code' => $code,
            'importance' => $request->importance,
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'issue_date' => $datetime
        ]);

        //updating logs
        Comment::create([
            'content' => "Ticket opened - by: ".auth()->user()->name,
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'isLog' => 1
        ]);

        //redirecting the page
        return route('user.dashboard');
    }
}