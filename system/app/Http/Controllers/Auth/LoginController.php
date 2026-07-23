<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override attemptLogin method to explicitly prevent Remember Me functionality
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Only authenticate using credentials, no remember token will be generated
        return \Illuminate\Support\Facades\Auth::attempt($credentials);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password harus diisi',
            ]
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $request->flash();
        
        throw ValidationException::withMessages([
            $this->username() => ['Email atau password salah'],
        ]);
    }
}
