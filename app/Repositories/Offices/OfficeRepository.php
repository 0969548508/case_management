<?php

namespace App\Repositories\Offices;

use App\Repositories\Repository;
use App\Models\Offices;
use App\User;
use DB;
use Log;

use Illuminate\Support\Facades\Hash;

class OfficeRepository extends Repository {

    public function model()
    {
        return \App\Models\Offices::class;
    }

    public static function getListOfficesByUser($userId, $pluck = false)
    {
        $sql = Offices::join('users_belong_offices', 'users_belong_offices.office_id', '=', 'offices.id')
            ->where('users_belong_offices.user_id', '=', $userId);

        if (!$pluck)
            $listOffices = $sql->select('*')->get()->toArray();
        else
            $listOffices = $sql->pluck('name');

        return $listOffices;
    }

    public function getListOffices()
    {
        $allOffices = $this->getall();

        return $allOffices;
    }

    public function storeOffice($data)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Create office error!',
        ];

        if ($this->validOfficeName($data['name']) > 0) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Office name is duplicate',
            ];

            return $response;
        }

        $result = $this->store($data);
        if ($result) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Create office successfully!',
            ];
        }

        return $response;
    }

    public function updateOffice($request, $officeId)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Update office error!',
        ];

        if ($this->validOfficeName($request->name, $officeId) > 0) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Office name is duplicate',
            ];

            return $response;
        }

        isset($request->_token) ? $data = $request->except('_token') : $data = $request->all();

        $result = $this->update($data, $officeId);

        if ($result) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Update office successfully!',
            ];
        }

        return $response;
    }

    public function deleteOffice($officeId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Delete office successfully!',
        ];

        $checkInUse = DB::table('users_belong_offices')->where('office_id', $officeId)->count();

        if ($checkInUse > 0) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'This office is available. Can not delete',
            ];
        } else {
            $officeInfo = Offices::find($officeId);
            $officeInfo->delete();
        }

        return $response;
    }

    public function getOfficeDetail($officeId)
    {
        $officeDetail = $this->findById($officeId);

        return $officeDetail;
    }

    public static function getStateDetail(array $columns, $stateId)
    {
        $stateDetail = DB::table('states')->select($columns)->where('id', $stateId)
                        ->get()->toArray();

        return $stateDetail;
    }

    public function validOfficeName($officeName, $officeId = null)
    {
        $sql = Offices::where('name', $officeName);

        if (!empty($officeId))
            $sql = $sql->where('id', '<>', $officeId);

        $count = $sql->count();

        return $count;
    }
}