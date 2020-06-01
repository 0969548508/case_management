<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PasswordPolicies\PolicyRepository as PolicyRepository;
use Log;

class PasswordPoliciesController extends Controller
{
	/**
     * @var policySettings
    */
    protected $policySettings;

	public function __construct(PolicyRepository $policySettings)
    {
        $this->policySettings = $policySettings;
    }

	public function index()
    {
    	$dataSettings = $this->policySettings->getPasswordPolicySettings(1);

    	return view('policies.browse', ['dataSettings' => $dataSettings]);
    }

    public function savePolicies(Request $request)
    {
    	$response = $this->policySettings->saveSettingsPolicies($request);

    	return redirect()->route('policies.index')->with($response);
    }

    public function updatePolicies(Request $request, $id)
    {
    	$response = $this->policySettings->saveSettingsPolicies($request, $id);

    	return redirect()->route('policies.index')->with($response);
    }
}
