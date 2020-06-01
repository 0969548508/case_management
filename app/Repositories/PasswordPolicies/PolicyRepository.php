<?php

namespace App\Repositories\PasswordPolicies;

use App\Repositories\Repository;
use App\Jobs\SendEmailJob;
use Validator;
use Log;

class PolicyRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\PasswordPolicies::class;
    }

    public function getPasswordPolicySettings($id)
    {
        $result = $this->findById($id);

        if (isset($result['alert-type']) && $result['alert-type'] == 'error') {
            $result = array();
        }

        return $result;
    }

    public function saveSettingsPolicies($request, $id = 0)
    {
        isset($request->special_character) ? $request->merge(['special_character' => 1]) : $request->merge(['special_character' => 0]);
        isset($request->capital_letter) ? $request->merge(['capital_letter' => 1]) : $request->merge(['capital_letter' => 0]);
        isset($request->number) ? $request->merge(['number' => 1]) : $request->merge(['number' => 0]);
        // Remove _token param
        isset($request->_token) ? $data = $request->except('_token') : $data = $request->all();

        if ($id == 0) {
            // Insert data to database
            $result = $this->store($data);
        } else {
            // Update data to database
            $result = $this->update($data, $id);
        }

        $response = [
            'message' => __('Save password policy settings fail!'),
            'alert-type' => 'error'
        ];

        if ($result) {
            $response = [
                'message' => __('Save success!'),
                'alert-type' => 'success'
            ];
        }

        return $response;
    }
}