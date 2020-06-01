<?php

namespace App\Repositories\TwoFactor;

use App\Repositories\Repository;
use App\Jobs\TwoFactorAuthJob;
use App\User;
use App\Models\Ips;
use App\Models\UserIp;
use Carbon\Carbon;
use Validator;
use Session;
use Log;

class TwoFactorRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Ips::class;
    }

    public function verifyToken($request)
    {
        $user = auth()->user();
        $clientIp = $request->getClientIp();
        $ipId = $this->getIpIdByUser($user->id, $clientIp);
        $result = array(
            'alert-type' => 'error',
            'message'    => 'Incorrect token.'
        );

        $ipData = Ips::find($ipId);
        // Check session token
        if ($ipData->email_verified_at < Carbon::now()) {
            $ipData->two_factor_token = null;
            $ipData->save();
            $result = array(
                'alert-type' => 'error',
                'message'    => 'This code has expired. Please click resend code.'
            );
        }

        // Check token
        if ($ipData->two_factor_token == $request->token) {
            $ipData->two_factor_expiry = Carbon::now()->addDays(config('sessions.lifetime'));
            $ipData->save();
            $result = array(
                'alert-type' => 'success',
                'message'    => 'Successfully'
            );
        }

        return $result;
    }

    public function resendCode($request)
    {
        $user = auth()->user();
        $clientIp = $request->getClientIp();
        $ipId = $this->getIpIdByUser($user->id, $clientIp);

        $ipData = Ips::find($ipId);

        // Send mail
        $ipData->email_verified_at = Carbon::now()->addMinutes(config('sessions.lifetime_otp'));
        $ipData->two_factor_token = mt_rand(100000, 999999);
        $ipData->save();

        $job = new TwoFactorAuthJob(array('email' => $user->email, 'token' => $ipData->two_factor_token));
        dispatch($job);
    }

    public function getIpIdByUser($userId, $clientIp){
        $ipId = 0;
        $listIp = User::select('ips.id', 'ips.ip_name')
                        ->where('users.id', $userId)
                        ->join('users_ips','users_ips.user_id','=','users.id')
                        ->join('ips','users_ips.ip_id','=','ips.id')
                        ->get()->toArray();

        foreach ($listIp as $ip) {
            if ($ip['ip_name'] == $clientIp) {
                $ipId = $ip['id'];
                break;
            }
        }

        return $ipId;
    }
}