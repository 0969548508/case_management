<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TwoFactor\TwoFactorRepository as TwoFactorRepository;
use App\Jobs\TwoFactorAuthJob;
use App\Models\Ips;
use Carbon\Carbon;
use Log;

class TwoFactorController extends Controller
{
	/**
     * @var twoFactor
    */
    protected $twoFactor;

	public function __construct(TwoFactorRepository $twoFactor)
	{
        $this->twoFactor = $twoFactor;
    }

    // show the two factor auth form
	public function show2faForm()
	{
	    return view('google2fa.index');
	}

	// post token to the backend for check
	public function verifyToken(Request $request)
	{
		$response = $this->twoFactor->verifyToken($request);
	    if($response['alert-type'] == 'success'){
			return redirect()->intended('/home');
	    }

	    return redirect()->route('show2faForm')->with($response);
	}

	public function resendCode(Request $request)
	{
		try {
			$this->twoFactor->resendCode($request);
			$response['success'] = __('Resend Code Successfully.');
		} catch (DecryptException $e) {
			$response['errors'] = __('Resend Code Fail.');
		}

	    return response()->json($response);
	}

}
