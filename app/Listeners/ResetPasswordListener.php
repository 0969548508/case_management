<?php

namespace App\Listeners;

use App\Models\PasswordHistory;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PasswordReset $passwordReset)
    {
        $passwordHistory = PasswordHistory::create([
            'user_id'  => $passwordReset->user->id,
            'password' => $passwordReset->user->password
        ]);
    }
}
