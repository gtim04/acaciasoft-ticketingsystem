<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected function login(Request $request)
    {   
        $input = $request->all();

        $this->validate($request, [

            'email' => 'required|email',

            'password' => 'required',

        ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            if (auth()->user()->user_type == 'admin') {
                return redirect()->route('admin.dashboard');
            }elseif (auth()->user()->user_type == 'client'){
                return redirect()->route('user.dashboard');
            }
        }else{
            return redirect()->route('login')
            ->with('message','Email-Address And Password Are Wrong.');
        }
    }

    protected function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}