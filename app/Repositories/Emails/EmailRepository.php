<?php

namespace App\Repositories\Emails;

use App\Repositories\Repository;
use App\Repositories\Emails\EmailRepositoryInterface;
use Log;
use App\Models\Emails;
use App\User;

use Illuminate\Support\Facades\Hash;

class EmailRepository extends Repository implements EmailRepositoryInterface {

    public function model()
    {
        return \App\Models\Emails::class;
    }

    public function storeEmail($userId, $data)
    {
        $emailInfo = new Emails;
        $emailInfo['email'] = $data['email'];
        $emailInfo['type_name'] = $data['type-email'];
        $emailInfo['user_id'] = $userId;
        
        if ($this->duplicateEmail($data['email']) || 
            $this->checkDuplicateWithEmailUser($data['email'])) {
            $response = [
                'message'       => 'This email already exists!',
                'alert-type'    => 'errors'
            ];
            return $response;
        }

        if ($emailInfo->save()) {
            $response = [
                'message'       => 'Add Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Add email error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function updateEmail($id, $data)
    {
        $emailInfo = Emails::find($id);
        $emailData = $data->all();

        $emailInfo['email'] = $emailData['email'];
        $emailInfo['type_name'] = $emailData['type-name'];

        if ($this->duplicateEmailUpdate($id, $emailData['email']) || 
            $this->checkDuplicateWithEmailUser($emailData['email'])) {
            $response = [
                'message'       => 'This email already exists!',
                'alert-type'    => 'errors'
            ];
            return $response;
        }

        if ($emailInfo->save()) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Update email error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function getListEmailByUser($userId)
    {
        $getListEmails = Emails::where('user_id', $userId)
                        ->get()->toArray();
        return $getListEmails;
    }

    public function deleteEmail($id)
    {
        if ($this->delete($id)) {
            $response = [
                'message'       => 'Delete Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Delete email error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    protected function duplicateEmail($email)
    {
        $isExist = Emails::cursor()->filter(function($record) use ($email) {
            try {
                if ($record->email == $email) {
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

    protected function checkDuplicateWithEmailUser($email)
    {
        $isExist = User::cursor()->filter(function($record) use ($email) {
            try {
                if ($record->email == $email) {
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

    protected function duplicateEmailUpdate($id, $email)
    {
        $isExist = Emails::cursor()->filter(function($record) use ($email, $id) {
            try {
                if ($record->email == $email && $record->id != $id) {
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