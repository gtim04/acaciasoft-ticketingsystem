<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckUser
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
        if (Auth::check()) 
        {
            if (auth()->user()->role == 'admin'){
                return redirect('admin');
            } 
            else if (auth()->user()->role == 'client') 
            {
                return $next($request);
            }
        }
        abort(404);
    }
}
