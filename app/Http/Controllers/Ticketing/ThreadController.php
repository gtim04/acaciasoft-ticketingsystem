<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use App\User;
use App\Comment;

class ThreadController extends Controller
{
    protected function indexThread($id) {
    	return view('admin.thread', [
    		'data' => $id
    	]);
    }

    protected function indexUserThread($id) {
        return view('user.thread')->with('data', $id);
    }


     protected function getThread(Request $request) {
        $comments = Comment::with('ticket', 'user')
                    ->where('ticket_id', $request->id)
                    ->get();
    	return $comments;
    }


	protected function addComment(Request $request) {
		// adding comments
		$comment = new Comment();
		$comment->content = $request->comment;
        $comment->user_id = auth()->user()->id;
        $comment->ticket_id = $request->id;
		$comment->save();
	}

    protected function resolveTicket(Request $request){
        //updating completed field
        $data = Ticket::find($request->id);
        $data->isCompleted = 1;
        $data->status = 'resolved';
        $data->save();
    }

    protected function reopenTicket(Request $request){
        //updating completed field
        $data = Ticket::find($request->id);
        $data->isCompleted = 0;
        $data->isDeleted = 0;
        $data->status = 'open';
        $data->save();
    }
}
