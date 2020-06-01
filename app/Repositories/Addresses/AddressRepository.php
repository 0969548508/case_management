<?php

namespace App\Repositories\Addresses;

use App\Repositories\Repository;
use App\Repositories\Addresses\AddressRepositoryInterface;
use App\Models\Addresses;
use App\User;
use DB;
use Log;

use Illuminate\Support\Facades\Hash;

class AddressRepository extends Repository implements AddressRepositoryInterface {

    public function model()
    {
        return \App\Models\Addresses::class;
    }

    public function storeAddress($userId, $data)
    {
        $addressInfo = new Addresses;
        $addressInfo['type_name'] = $data['type_address'];
        $addressInfo['address'] = $data['address'];
        $addressInfo['country'] = $data['country'];
        $addressInfo['state'] = $data['state'];
        $addressInfo['city'] = $data['city'];
        $addressInfo['postal_code'] = $data['postcode'];
        $addressInfo['user_id'] = $userId;

        if($data['is_primary'] == 1) {
            // update address's is_primary to 0
            Addresses::where('user_id', $userId)->update(['is_primary' => 0]);

            $addressInfo['is_primary'] = $data['is_primary'];

            if ($addressInfo->save()) {
                $response = [
                    'message'       => 'Add Successfully!',
                    'alert-type'    => 'success'
                ];
            } else {
                $response = [
                    'message'       => 'Add address error!',
                    'alert-type'    => 'errors'
                ];
            }
            return $response;
        }
        $addressInfo['is_primary'] = $data['is_primary'];

        if ($addressInfo->save()) {
            $response = [
                'message'       => 'Add Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Add address error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function getListAddressByUser($userId)
    {
        $getListAddresses = Addresses::where('user_id', $userId)
                        ->get()->toArray();

        return $getListAddresses;
    }

    public function deleteAddress($id)
    {
        if ($this->delete($id)) {
            $response = [
                'message'       => 'Delete Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Delete address error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function updateAddress($id, $data)
    {
        $addressData = $data->all();

        if($addressData['is_primary'] == 1) {
            // update address's is_primary to 0
            Addresses::where('user_id', $addressData['user_id'])->update(['is_primary' => 0]);
        }
        $addressInfo = Addresses::find($id);
        $addressInfo['type_name'] = $addressData['type_address'];
        $addressInfo['address'] = $addressData['address'];
        $addressInfo['country'] = $addressData['country'];
        $addressInfo['state'] = $addressData['state'];
        $addressInfo['city'] = $addressData['city'];
        $addressInfo['postal_code'] = $addressData['postcode'];
        $addressInfo['user_id'] = $addressData['user_id'];
        $addressInfo['is_primary'] = $addressData['is_primary'];

        if ($addressInfo->save()) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Update address error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public static function getCountryIdByName($countryName)
    {
        $countryId = DB::table('countries')->where('name', $countryName)->get();
        if (!$countryId->isEmpty())
            $countryId = $countryId[0]->id;
        else
            $countryId = 0;

        return $countryId;
    }

    public static function getStateIdByName($stateName)
    {
        $stateId = DB::table('states')->where('name', $stateName)->get();
        if (!$stateId->isEmpty())
            $stateId = $stateId[0]->id;
        else
            $stateId = 0;

        return $stateId;
    }

    public static function getCityIdByName($cityName)
    {
        $cityId = DB::table('cities')->where('name', $cityName)->get();
        if (!$cityId->isEmpty())
            $cityId = $cityId[0]->id;
        else
            $cityId = 0;

        return $cityId;
    }
}