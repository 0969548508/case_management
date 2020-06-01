<?php

namespace App\Repositories\Users;

use App\Repositories\Repository;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Phones\PhoneRepository;
use App\Repositories\Clients\ClientsRepository;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Models\Clients;
use App\Models\Emails;
use App\Models\Phones;
use App\Models\Roles;
use App\Models\PasswordHistory;
use App\Models\Offices;
use App\Models\UserType;
use App\Models\UserOffice;
use App\Models\SpecificMatters;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use DateTime;
use Log;
use Auth;
use DB;

class UserRepository extends Repository implements UserRepositoryInterface {
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\User::class;
    }

    public function createNewUser($data)
    {
        $finalTypes = array();
        $finalOffices = array();
        //Validate duplicate email
        if ($this->duplicateEmail($data->email)) {
            $response = [
                'message'       => 'This email already exists!',
                'alert-type'    => 'error'
            ];

            return $response;
        }

        // To generate password
        $generatedPassword = $this->generatePassword();
        $data->request->add(['password' => $generatedPassword]);
        $passForSendMail = $data['password'];
        $data['password'] = Hash::make($data['password'] . env('SALT'));

        // Insert data to database
        $result = $this->store($data->all());

        if(!empty($data['select-office'])) {
            foreach ($data['select-office'] as $officeId) {
                array_push($finalOffices, array(
                    'office_id' => $officeId,
                    'user_id' => $result->id
                ));
            }
        }

        if(!empty($data['select-matter'])) {
            foreach ($data['select-matter'] as $typeId) {
                array_push($finalTypes, array(
                    'type_id' => $typeId,
                    'user_id' => $result->id
                ));
            }
        }

        if ($result && UserOffice::insert($finalOffices) && UserType::insert($finalTypes)) {
            if (!empty($data['password'])) {
                $job = new SendEmailJob(array('email' => $data->email, 'password' => $passForSendMail));
                dispatch($job);
            }

            if(!empty($data['select-roles'])) {
                $this->assignRoleForUser($data['select-roles'], $result->id);
            }

            $response = [
                'message'       => 'Sent Successfully!',
                'alert-type'    => 'success'
            ];

            // notification for create new user
            $dataNotify = array(
                'slug'    => 'user-create',
                'info' => [
                    'id'           => $result->id,
                    'name'         => $result->name,
                    'family_name'  => $result->family_name
                ]
            );
            $result->notify(new UserNotification($result, $dataNotify));

            return $response;
        } else {
            $response = [
                'message'       => 'Create user error!',
                'alert-type'    => 'error'
            ];

            return $response;
        }
    }

    public function updateUser($id, $data)
    {
        $userInfo = User::find($id);
        $userData = $data->all();

        if (empty($userInfo)) {
            $userInfo = [
                'message'       => 'User not found!',
                'alert-type'    => 'errors'
            ];
        }

        if (isset($userData['name'])) $userInfo->name = $userData['name'];
        if (isset($userData['middle_name'])) $userInfo->middle_name = $userData['middle_name'];
        if (isset($userData['family_name'])) $userInfo->family_name = $userData['family_name'];
        if (isset($userData['date_of_birth'])) {
            $dateOfBirth = Carbon::createFromFormat('d/m/Y', $userData['date_of_birth'])->format('Y-m-d');
            $userInfo->date_of_birth = $dateOfBirth;
        }

        if ($userInfo->save()) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];

            // notification for update user
            if ($userInfo->id != auth()->user()->id) {
                $dataNotify = array(
                    'slug'    => 'user-update',
                    'info' => [
                        'id'           => $userInfo->id,
                        'name'         => $userInfo->name,
                        'family_name'  => $userInfo->family_name
                    ]
                );
                $userInfo->notify(new UserNotification($userInfo, $dataNotify));
            }
        } else {
            $response = [
                'message'       => 'Update user error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    /**
     * To random password for new user
     *
     * @return String
     */
    public function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890~!@#$%^&*';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    // get detail of user by id
    public static function getDetailUserById($id)
    {
        $response = User::find($id);
        if (empty($response)) {
            $response = [
                'message'       => 'User not found!',
                'alert-type'    => 'error'
            ];
        } else {
            if (!empty($response->image)) {
                $tenantDetail = tenancy()->getTenant();
                $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
                $path = $pathTenant . "users/$id/";
                $response->image = self::loadImage($path, $response->image);
            }
        }

        return $response;
    }

    // get all user
    public function getAllUser()
    {
        $listUsers = User::orderBy('name', 'ASC')->get();
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";

        foreach ($listUsers as &$user) {
            if (!empty($user->image)) {
                $path = $pathTenant . "users/$user->id/";
                $user->image = $this->loadImage($path, $user->image);
            }
        }

        return $listUsers;
    }

    protected function duplicateEmail($email)
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

    // get all role
    public function getAllRole()
    {
        $response = Roles::all();
        return $response;
    }

    public static function getRoleNameByUserId($userId)
    {
        $roleName = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('users.id', $userId)->pluck('roles.name');

        $roleName = (!$roleName->isEmpty()) ? implode(', ', $roleName->toArray()) : '';
        return $roleName;
    }

    public static function getRolesByUserId($userId)
    {
        $roleNames = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('users.id', $userId)->get()->toArray();

        return $roleNames;
    }

    public static function getPrimaryPhoneByUserId($userId)
    {
        $phoneDetail = Phones::where(['user_id' => $userId, 'is_primary' => 1])->get()->toArray();
        $phoneNumber = (!empty($phoneDetail)) ? $phoneDetail[0]['phone_number'] : '';

        return $phoneNumber;
    }

    public function assignRoleForUser($roleArray, $userId)
    {
        $response = [
            'message'       => 'Sent Successfully!',
            'alert-type'    => 'success'
        ];

        $user = $this->findById($userId);
        $user->syncRoles($roleArray);

        // notification for assign role by user
        if ($user->id != auth()->user()->id) {
            $dataNotify = array(
                'slug'    => 'user-assign-role',
                'info' => [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'family_name'  => $user->family_name,
                    'roleName'     => $roleArray
                ]
            );
            $user->notify(new UserNotification($user, $dataNotify));
        }

        return $response;
    }

    public function changeStatusUser($id, $data)
    {
        $userInfo = User::find($id);
        $userData = $data->all();

        if($userData['status'] == 'inactive' || $userData['status'] == null) {
            $userInfo->status = 'active';
        } else {
            $userInfo->status = 'inactive';
        }

        if ($userInfo->save()) {
            $response = [
                'message'       => 'Change Status Successfully!',
                'alert-type'    => 'success',
                'status'        => $userData['status']
            ];
        } else {
            $response = [
                'message'       => 'Change Status Fail!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    public function deleteUser($userId)
    {
        if (auth()->user()->id == $userId) {
            return $respone = [
                'alert-type' => 'error',
                'message'    => 'User in use, cannot be deleted',
            ];
        }

        $userInfo = User::find($userId);
        $userInfo->in_trash = 1;
        $userInfo->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($userInfo->name) . ' has been moved to Deleted users list',
        ];
    }

    public function restoreUser($userId)
    {
        $userInfo = User::find($userId);
        $userInfo->in_trash = 0;
        $userInfo->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($userInfo->name) . ' has been restored',
        ];
    }

    public function deletePermanentlyUser($userId)
    {
        if (auth()->user()->id == $userId) {
            return $respone = [
                'alert-type' => 'error',
                'message'    => 'User in use, cannot be deleted',
            ];
        }

        $listClientId = Clients::where('user_id', $userId)->pluck('id');
        if (!empty($listClientId)) {
            foreach ($listClientId as $clientId) {
                ClientsRepository::deletePermanentlyClient($clientId);
            }
        }

        //delete all password histories
        PasswordHistory::where('user_id', $userId)->delete();

        //delete all emails
        Emails::where('user_id', $userId)->delete();

        //delete all phones
        Phones::where('user_id', $userId)->delete();

        //delete all offices
        UserOffice::where('user_id', $userId)->delete();

        //delete all types
        UserType::where('user_id', $userId)->delete();

        //delete all permissions
        DB::table('model_has_permissions')->where('model_id', $userId)->delete();

        //delete all roles
        DB::table('model_has_roles')->where('model_id', $userId)->delete();

        $userInfo = User::find($userId);
        $userName = $userInfo->name;
        $userInfo->delete();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($userName) . ' has been deleted permanently from the system',
        ];
    }

    /**
     * To upload image
     */
    private function uploadImage(UploadedFile $file, $imagePath, $oldItem = null)
    {
        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
        $disk = Storage::disk(env('DISK_STORAGE'));

        if (!empty($oldItem)) {
            $disk->deleteDirectory($imagePath);
        }

        //create new image
        $disk->putFileAs($imagePath, $file, $fileName);
        return response()->json();
    }

    /**
     * To load image
     */
    private static function loadImage($imagePath, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($imagePath . $imageName);

        return $url;
    }

    public function updateImageUser($data, $userId)
    {
        $response = [
            'alert-type' => 'success',
            'message'    => 'Save Successfully',
        ];

        $userDetail = User::find($userId);

        if (isset($data['image']) && !empty($data['image'])) {
            $file = $data['image'];
            $path = "users/$userId/";

            $oldImage = (!empty($userDetail->image)) ? $userDetail->image : null;
            $uploadResponse = $this->uploadImage($file, $path, $oldImage);

            if($uploadResponse->status() == 200) {
                $fileName = str_replace(" ", "_", $file->getClientOriginalName());
                $userDetail->image = $fileName;
            } else {
                $response = [
                    'alert-type' => 'errors',
                    'message'    => 'Fail to update image',
                ];

                return $response;
            }
        }

        $userDetail->save();

        return $response;
    }

    public static function loadImageUserLogin($userId = null)
    {
        if (empty($userId)) {
            $userDetail = auth()->user();
        } else {
            $userDetail = User::find($userId);
        }

        if (!empty($userDetail->image)) {
            $tenantDetail = tenancy()->getTenant();
            $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
            $path = $pathTenant . "users/$userDetail->id/";
            $userDetail->image = self::loadImage($path, $userDetail->image);
        }

        return $userDetail->image;
    }

    public function ajaxGetListUserByOfficeAndType($officeId = null, $typeId = null)
    {
        if (!empty($officeId) && !empty($typeId)) {
            $listUserByOfficeId = Offices::where('id', $officeId)->with('users')->first();

            $listUserByTypeId = SpecificMatters::where('id', $typeId)->with('users')->first();

            $listUser = $listUserByOfficeId->users;
            $listUser = $listUser->intersect($listUserByTypeId->users);
        } else {
            $listUser = array();
        }

        return $listUser;
    }
}