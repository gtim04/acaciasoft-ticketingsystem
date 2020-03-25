<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use Yajra\Datatables\Datatables;

class AdminResolvedController extends Controller
{
    protected function indexResolved(){
    	return view('admin.resolved');
    }

    protected function getResolved(){
        $id = auth()->user()->id; //get current user id
        $tickets = Ticket::with('user')
                    ->where([['ticket_handler', '=', $id], ['isCompleted', '=', 1], ['isDeleted', '=', 0]])
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
