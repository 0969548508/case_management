<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Str;
use App\Repositories\Users\UserRepository as UserRepository;
use App\Jobs\SendEmailJob;
use App\User;
use App\Models\PasswordResets;
use Carbon\Carbon;
use Log;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @var user
    */
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $email = $request->email;
        $checkUser = $this->validateEmail($email);

        if ($checkUser->isEmpty()) {
            $response = [
                'message'       => 'Email is not available.',
                'alert-type'    => 'error'
            ];

            return back()->with($response);
        }

        // send link forgot password
        $this->sendMail($email);

        return redirect()->route('showViewMessage', $email);
    }

    public function resendLinkResetPassword(Request $request, $email)
    {
        $this->validateEmail($request);

        $email = $request->email;
        $checkUser = $this->validateEmail($email);

        $email = $request->email;
        $checkUser = $this->validateEmail($email);

        $response['success'] = __('Resend Successfully.');
        if ($checkUser->isEmpty()) {
            $response['errors'] = __('Email is not available.');

            return response()->json($response);
        }

        // send link forgot password
        $this->sendMail($email);

        return response()->json($response);
    }

    public function showViewMessage(Request $request, $email)
    {
        $this->validateEmail($request);

        $email = $request->email;
        $checkUser = $this->validateEmail($email);

        if ($checkUser->isEmpty()) abort(404);

        return view('auth.passwords.email-message', array('email' => $email));
    }

    public function showViewForgotPassword(Request $request, $token = null)
    {
        $data = PasswordResets::all()->filter(function($record) use($token) {
            try {
                if ($record->token == $token) {
                    return $record->email;
                }
            } catch (DecryptException $e) {
                Log::error('ERROR: sendResetLinkEmail function => ForgotPasswordController');
            }
        })->first();

        if (empty($data)) abort(404);

        $data = $data->toArray();

        // check session link forgot password
        $days = config('sessions.lifetime_link_forgot_password');
        if ($data['created_at'] < Carbon::now()->subDays($days)) {
            abort(404);
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $data['email']]
        );
    }

    protected function sendMail($email)
    {
        // To set link forgot password
        $token = hash_hmac('sha256', Str::random(40), env('APP_KEY'));
        PasswordResets::create(['email' => $email, 'token' => $token, 'created_at' => now()]);

        // To generate password
        $generatedPassword = $this->user->generatePassword();

        $job = new SendEmailJob(array('email' => $email, 'token' => $token));
        dispatch($job);
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
