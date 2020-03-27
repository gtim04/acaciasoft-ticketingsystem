<?php

namespace App\Http\Middleware;

use Closure;
use App\Ticket;

class CheckThread
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ticketid = Ticket::find($request->id); 
        if($ticketid->user_id == auth()->user()->id || $ticketid->ticket_handler == auth()->user()->id){
            return $next($request);
        }
        abort(404);
    }
}
