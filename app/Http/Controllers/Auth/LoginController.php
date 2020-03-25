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
        //getting array of data
        $input = $request->all();

        //validation
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //route decisions
        if(Auth::attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            if (auth()->user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }elseif (auth()->user()->role == 'client'){
                return redirect()->route('user.dashboard');
            }
        }else{
            return redirect()->route('login')
            ->withErrors(['password'=>'Email address or password does not match our records.']);
        }
    }

    protected function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}