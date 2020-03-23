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
            if (auth()->user()->user_type == 'admin')
            {
                return $next($request);
            } 
            else if (auth()->user()->user_type == 'client') 
            {
                return redirect('user');
            }
        }
        abort(404);
    }
}
