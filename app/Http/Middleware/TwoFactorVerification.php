<?php

namespace App\Http\Middleware;

use Closure;
use App\Jobs\TwoFactorAuthJob;
use App\User;
use App\Models\Ips;
use App\Models\UserIp;
use Carbon\Carbon;
use DB;
use Log;

class TwoFactorVerification
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
        $isInsert = true;
        $user = auth()->user();
        $clientIp = $request->getClientIp();
        $listIp = User::select('ips.id', 'ips.ip_name')
                        ->where('users.id', $user->id)
                        ->join('users_ips','users_ips.user_id','=','users.id')
                        ->join('ips','users_ips.ip_id','=','ips.id')
                        ->get()->toArray();
        $ipData = new Ips();

        foreach ($listIp as $ip) {
            if ($ip['ip_name'] == $clientIp) {
                $ipId = $ip['id'];
                $isInsert = false;
                break;
            }
        }

        if ($isInsert == false) {
            $ipData = Ips::find($ipId);
        } else {
            // insert data ip to Ips table
            $ipData->two_factor_expiry = Carbon::now();
            $ipData->ip_name = $clientIp;
            $ipData->save();
            $ipId = $ipData->id;

            // insert data mapping user-ip
            $userIp = new UserIp();
            $userIp->user_id = $user->id;
            $userIp->ip_id = $ipId;
            $userIp->save();
        }

        if ($ipData->two_factor_expiry > Carbon::now()) {
            return $next($request);
        }

        // Send mail
        $ipData->email_verified_at = Carbon::now()->addMinutes(config('sessions.lifetime_otp'));
        $ipData->two_factor_token = mt_rand(100000, 999999);
        $ipData->save();

        $job = new TwoFactorAuthJob(array('email' => $user->email, 'token' => $ipData->two_factor_token));
        dispatch($job);

        return redirect()->route('show2faForm');
    }
}
