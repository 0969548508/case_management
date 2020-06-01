<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stancl\Tenancy\Tenant;
use Log;

class TenantController extends Controller
{
    public function showCreateTenant()
    {
        return view('tenant.create-tenant');
    }

    public function createTenant(Request $req)
    {
        $data = $req->all();
        $domains = [
            $data['sub-domain-name'] . '.' . env('ROOT_DOMAIN')
        ];

        $dataDomain = [
            'manager'       => $data['manager'],
            'email'         => $data['email'],
        ];
        if (Tenant::create($domains, $dataDomain)) {
           $response = [
                'message'       => 'Create new tenant success!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Create new tenant error!',
                'alert-type'    => 'error'
            ];
        }

        return redirect()->route('showCreateTenant')->with($response);
    }
}
