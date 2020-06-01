<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\ChangePassword\ChangePasswordRepository as ChangePasswordRepository;
use App\Models\PasswordPolicies;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Log;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $forgot_password;

    public function __construct(ChangePasswordRepository $forgot_password)
    {
        $this->forgot_password = $forgot_password;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $data = $request->all();

        $email = $data['email'];
        $checkUser = $this->validateEmail($email);

        if ($checkUser->isEmpty()) {
            $response = [
                'message'       => 'Email is not available.',
                'alert-type'    => 'error'
            ];

            return back()->with($response);
        }

        $checkUser = $checkUser->first();

        $response = $this->forgot_password->userChangePassword($request, $checkUser, true);

        if ($response->fails()) {
            return back()->withInput()->withErrors($response);
        }

        // chage password
        $this->resetPassword($checkUser, $request['new-password']);
        $response = [
            'message'       => 'Change password successfully',
            'alert-type'    => 'success'
        ];

        return redirect()->route('login')->with($response);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $this->setUserExpiryPassword($user);

        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Set the user's expiry password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function setUserExpiryPassword($user)
    {
        $days = 0;
        $passPolicies = PasswordPolicies::all();
        if (isset($passPolicies[0]->pass_period) && !empty($passPolicies[0]->pass_period)) {
            $days = $passPolicies[0]->pass_period;
        }

        $user->password_expiry = Carbon::now()->addDays($days);
    }

    /**
     * Set the user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function setUserPassword($user, $password)
    {
        $user->password = bcrypt($password . env('SALT'));
    }

    protected function validateEmail($email)
    {
        $checkUser = User::all()->filter(function($record) use($email) {
            try {
                if ($record->email == $email) {
                    return $record;
                }
            } catch (DecryptException $e) {
                Log::error('ERROR: sendResetLinkEmail function => ForgotPasswordController');
            }
        });

        return $checkUser;
    }
}
