<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckAdmin
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
        if(Auth::check())
        {
            if (auth()->user()->role == 'admin')
            {
                return $next($request);
            } 
            else if (auth()->user()->role == 'client') 
            {
                return redirect('user');
            }
        }
        abort(404);
    }
}
