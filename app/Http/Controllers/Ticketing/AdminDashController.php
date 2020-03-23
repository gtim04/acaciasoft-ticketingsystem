<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ticket;
use Yajra\Datatables\Datatables;

class AdminDashController extends Controller
{
    protected function getTickets(){
        $tickets = Ticket::with('user')->get(); //join 2 tables
        return DataTables::of($tickets)
        ->addColumn('pickBtn', '<input type="button" class="pick btn-primary" value="Pickup Ticket">')
        ->rawColumns(['pickBtn'])
        ->editColumn('created_at', function ($tickets) {
          return date('F, d Y, g:i a', strtotime($tickets->created_at));
      })
  		  ->make(true); //return modified datatables
    }
}
