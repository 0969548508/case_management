<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChangePassword\ChangePasswordRepository as ChangePasswordRepository;
use Auth;
use Log;

class ChangeController extends Controller
{
    protected $change_password;

    public function __construct(ChangePasswordRepository $change_password)
    {
        $this->change_password = $change_password;
    }

    public function showViewChangePassword()
    {
        return view('users.change-password');
    }

    public function changePassword(Request $req)
    {
        $response = [];
        $response = $this->change_password->userChangePassword($req, Auth::user());
        if ($response->fails()) {
            return redirect()->route('showViewChangePassword')
                        ->withErrors($response)
                        ->withInput();
        } else {
            if ($this->change_password->updatePasswordForUser($req->all())) {
            	$response = [
	                'message'       => 'Change password success!',
	                'alert-type'    => 'success'
	            ];

            	return redirect()->route('home')->with($response);
            } 
        }
    }
}
