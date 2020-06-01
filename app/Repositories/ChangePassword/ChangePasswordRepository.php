<?php

namespace App\Repositories\ChangePassword;

use App\Repositories\Repository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\PasswordPolicies;
use App\Models\PasswordHistory;
use Carbon\Carbon;
use Auth;
use Log;
use Validator;
use Hash;

class ChangePasswordRepository extends Repository implements ChangePasswordRepositoryInterface {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\User::class;
    }

    public function userChangePassword($req, $user, $forgotPassword = false)
    {
        $data = $req->all();

        // check new password
        if ($forgotPassword == true) {
            // change forgot password
            $checkNewPassword = $this->checkPolicySettings($data['new-password'], $data, $user);
        } else {
            $checkNewPassword = $this->checkPasswordPolicies($data['new-password'], $data, $user);
        }

        return $checkNewPassword;
    }

    protected function checkPasswordPolicies($newPass, $data, $user)
    {
        $rules = [
            'old-password' => function ($attribute, $newPass, $fail) {
                $newPass = $newPass . env('SALT');
                if (!Hash::check($newPass, Auth::user()->password)) {
                    $fail('Old password does not match');
                }
            },
        ];

        $validator = $this->checkPolicySettings($newPass, $data, $user, $rules);

        return $validator;
    }

    public function updatePasswordForUser($req)
    {
        $currentUser = Auth::user();
        $data['password'] = bcrypt($req['new-password'] . env('SALT'));
        $data['password_expiry'] = $this->setUserExpiryPassword();

        $passwordHistory = PasswordHistory::create([
            'user_id'  => $currentUser->id,
            'password' => $data['password']
        ]);

        return $this->model()::where('id', '=', $currentUser->id)->update($data);
    }

    protected function setUserExpiryPassword()
    {
        $days = 0;
        $passPolicies = PasswordPolicies::all();
        if (isset($passPolicies[0]->pass_period) && !empty($passPolicies[0]->pass_period)) {
            $days = $passPolicies[0]->pass_period;
        }

        return Carbon::now()->addDays($days);
    }

    protected function checkPolicySettings($newPass, $data, $user, $rules = [])
    {
        $passPolicies = PasswordPolicies::all();
        $messages = [];
        $checkHistory = true;

        if (count($passPolicies) > 0) {
            $passPolicies = $passPolicies[0];

            // validate password
            // validate password length
            if ($passPolicies->pass_length != 0) {
                $length = $passPolicies->pass_length;
                $rules = array_merge($rules, [
                    'new-password' => function ($attribute, $newPass, $fail) use ($length) {
                        if ($length > strlen($newPass)) {
                            $checkHistory = false;
                            $fail('Minimum length is ' . $length);
                        }
                    },
                ]);
            }

            // validate password special character
            if ($passPolicies->special_character != 0) {
                $rules = array_merge($rules, [
                    'new-password-1' => function ($attribute, $newPass, $fail) {
                        if (!preg_match("#\W+#", $newPass)) {
                            $checkHistory = false;
                            return $fail('At least 1 special character');
                        }
                    },
                ]);
            }

            // validate password capital letter
            if ($passPolicies->capital_letter != 0) {
                $rules = array_merge($rules, [
                    'new-password-2' => function ($attribute, $newPass, $fail) {
                        if (!preg_match("#[A-Z]+#", $newPass)) {
                            $checkHistory = false;
                            return $fail('At least 1 capital letter');
                        }
                    },
                ]);
            }

            // validate password number
            if ($passPolicies->number != 0) {
                $rules = array_merge($rules, [
                    'new-password-3' => function ($attribute, $newPass, $fail) {
                        if (!preg_match("#[0-9]+#", $newPass)) {
                            return $fail('At least 1 number');
                        }
                    },
                ]);
            }

            if ($checkHistory == true)
                $this->checkPasswordHistory($newPass, $user, $passPolicies->password_history, $rules);
        }

        if ($newPass !== $data['confirm-password']) {
            $rules = array_merge($rules, [
                'confirm-password' => 'same:new-password',
            ]);

            $messages = array_merge($messages, [
                'confirm-password.same' => "Confirm password does not match",
            ]);
        }

        $validator = Validator::make($data, $rules, $messages);

        return $validator;
    }

    public function checkPasswordHistory($newPass, $user, $passwordHistoryNum, &$rules)
    {
        //Check Password History
        $passwordHistories = $user->passwordHistories()->take($passwordHistoryNum)->orderBy('created_at', 'desc')->get();
        $newPass = $newPass . env('SALT');
        foreach($passwordHistories as $passwordHistory){
            if (Hash::check($newPass, $passwordHistory->password)) {
                // The passwords matches
                $rules = array_merge($rules, [
                    'password-history' => function ($attribute, $newPass, $fail) {
                        return $fail('Your new password can not be same as any of your recent passwords. Please choose a new password.');
                    },
                ]);
            }
        }
    }
}