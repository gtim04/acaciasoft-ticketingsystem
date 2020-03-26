<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
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
        $tcomment = Ticket::where('id', $request->id)->with('comment.user')->get();
    	return $tcomment;
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
