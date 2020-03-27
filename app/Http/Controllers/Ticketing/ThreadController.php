<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\Comment;

class ThreadController extends Controller
{
    protected function userThread($id) {
    	$data = Comment::where('ticket_id', $id)->with('ticket.user')->paginate(7);
        return view('user.thread', compact('data'));
    }

    protected function adminThread($id) {
        $data = Comment::where('ticket_id', $id)->with('ticket.user')->paginate(7);
        return view('admin.thread', compact('data'));
    }

	protected function addComment(Request $request) {
		// adding comments
		$comment = new Comment();
		$comment->content = $request->comment;
        $comment->user_id = auth()->user()->id;
        $comment->ticket_id = $request->id;
		$comment->save();
        $comment = $comment->where('ticket_id', $request->id)->paginate(7);
        return $request->id . '?page=' . $comment->lastPage();
	}

    protected function resolveTicket(Request $request){
        //updating completed field
        $data = Ticket::find($request->id);
        $data->isCompleted = 1;
        $data->status = 'resolved';
        $data->save();
        //logs
        $log = new Comment();
        $log->content = "Ticket marked solved - by: ".auth()->user()->name;
        $log->ticket_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->isLog = 1;
        $log->save();
    }

    protected function reopenTicket(Request $request){
        //updating completed field
        $data = Ticket::find($request->id);
        $data->isCompleted = 0;
        $data->isDeleted = 0;
        $data->status = 'open';
        $data->save();
        //logs
        $log = new Comment();
        $log->content = "Ticket re-opened - by: ".auth()->user()->name;
        $log->ticket_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->isLog = 1;
        $log->save();
    }
}
