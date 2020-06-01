<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use App\User;
use App\Models\PasswordResets;
use Carbon\Carbon;
use Crypt;
use Session;
use DB;
use Log;
use Illuminate\Contracts\Encryption\DecryptException;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Password + salt
        $request->merge(['password' => $request->password . env('SALT')]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if(method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->attempt($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function authenticated(Request $request, $user)
    {
        if (empty($user->password_expiry) || $user->password_expiry < Carbon::now()) {
            // To set link forgot password
            $token = hash_hmac('sha256', Str::random(40), env('APP_KEY'));
            PasswordResets::create(['email' => $user->email, 'token' => $token, 'created_at' => now()]);

            // Logout
            auth()->logout();

            return redirect()->route('showViewForgotPassword', $token);
        }
    }

    protected function attempt($request) {
        $isLogin = false;
        $email = $request->email;
        $password = $request->password;
        $users = User::all()->filter(function($record) use($email, $password, &$isLogin) {
            try {
                if ($record->email == $email && password_verify($password, $record->password) && $record->status == 'active' && $record->in_trash == 0) {

                    auth()->login($record);
                    if (empty($record->password_expiry) || $record->password_expiry < Carbon::now()) {
                        $isLogin = true;
                    } else {
                        $isLogin = false;
                    }
                }
            } catch (DecryptException $e) {
                Log::error('ERROR: attempt function => LoginController');
            }
        });

        return $isLogin;
    }
}
