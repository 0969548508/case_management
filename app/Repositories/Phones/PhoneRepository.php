<?php

namespace App\Repositories\Phones;

use App\Repositories\Repository;
use App\Repositories\Phones\PhoneRepositoryInterface;
use Log;
use App\Models\Phones;
use App\User;

use Illuminate\Support\Facades\Hash;

class PhoneRepository extends Repository implements PhoneRepositoryInterface {

    public function model()
    {
        return \App\Models\Phones::class;
    }

    public function storePhone($userId, $data)
    {
        $phoneInfo = new Phones;
        $phoneInfo['phone_number'] = $data['phone_number'];
        $phoneInfo['type_name'] = $data['type-phone'];
        $phoneInfo['user_id'] = $userId;

        if ($this->duplicatePhone($data['phone_number'])) {
            $response = [
                'message'       => 'This phone number already exists!',
                'alert-type'    => 'errors'
            ];
            return $response;
        }

        if($data['is_primary'] == 1) {
            // update phone's is_primary to 0
            Phones::where('user_id', $userId)->update(['is_primary' => 0]);

            $phoneInfo['is_primary'] = $data['is_primary'];

            if ($phoneInfo->save()) {
                $response = [
                    'message'       => 'Add Successfully!',
                    'alert-type'    => 'success'
                ];
            } else {
                $response = [
                    'message'       => 'Add phone error!',
                    'alert-type'    => 'errors'
                ];
            }
            return $response;
        }
        $phoneInfo['is_primary'] = $data['is_primary'];

        if ($phoneInfo->save()) {
            $response = [
                'message'       => 'Add Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Add phone error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function updatePhone($id, $data)
    {
        $phoneData = $data->all();
        if($phoneData['is_primary'] == 1) {
            // update phone's is_primary to 0
            Phones::where('user_id', $phoneData['user_id'])->update(['is_primary' => 0]);
        }

        $phoneInfo = Phones::find($id);
        $phoneInfo['phone_number'] = $phoneData['phone-number'];
        $phoneInfo['type_name'] = $phoneData['type-name'];
        $phoneInfo['user_id'] = $phoneData['user_id'];
        $phoneInfo['is_primary'] = $phoneData['is_primary'];

        if ($this->duplicatePhoneUpdate($id, $phoneData['phone-number'])) {
            $response = [
                'message'       => 'This phone number already exists!',
                'alert-type'    => 'errors'
            ];
            return $response;
        }

        if ($phoneInfo->save()) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Update phone error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function getListPhoneByUser($userId)
    {
        $getListPhones = Phones::where('user_id', $userId)
                        ->get()->toArray();
        return $getListPhones;
    }

    public function deletePhone($id)
    {
        if ($this->delete($id)) {
            $response = [
                'message'       => 'Delete Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Delete phone error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    protected function duplicatePhone($phone)
    {
        $isExist = Phones::cursor()->filter(function($record) use ($phone) {
            try {
                if ($record->phone_number == $phone) {
                    return $record;
                }
            } catch (DecryptException $e) {
                //
            }
        });

        if (count($isExist) > 0) {
            return true;
        }
        return false;
    }

    protected function duplicatePhoneUpdate($id, $phone)
    {
        $isExist = Phones::cursor()->filter(function($record) use ($phone, $id) {
            try {
                if ($record->phone_number == $phone && $record->id != $id) {
                    return $record;
                }
            } catch (DecryptException $e) {
                //
            }
        });

        if (count($isExist) > 0) {
            return true;
        }
        return false;
    }
}