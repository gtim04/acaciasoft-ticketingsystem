<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use Yajra\Datatables\Datatables;

class UserDeletedController extends Controller
{
    protected function indexDeleted(){
    	return view('user.deleted');
    }

    protected function getDeleted(){
        $id = auth()->user()->id; //get current user id
        $tickets = Ticket::with('user')
                    ->where([['user_id', '=', $id], ['status', '=', 'deleted'], ['isDeleted', '=', 1]])
                    ->get(); //join 2 tables
        return DataTables::of($tickets)
        ->addColumn('viewBtn', '<input type="button" class="view btn-primary" value="View Ticket">')
        ->rawColumns(['viewBtn'])
        ->editColumn('created_at', function ($tickets) {
          return date('F, d Y, g:i a', strtotime($tickets->created_at));
      })
        ->make(true); //return modified datatables
    }
}
