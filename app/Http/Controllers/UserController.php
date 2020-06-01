<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Users\UserRepository as UserRepository;
use App\Repositories\Roles\RolesRepository as RolesRepository;
use App\Repositories\Emails\EmailRepository as EmailRepository;
use App\Repositories\Phones\PhoneRepository as PhoneRepository;
use App\Repositories\Addresses\AddressRepository as AddressRepository;
use App\Repositories\Accreditations\AccreditationRepository as AccreditationRepository;
use App\Repositories\License\LicensesRepository as LicensesRepository;
use App\Repositories\Libraries\LibrariesRepository as LibrariesRepository;
use App\Repositories\SpecificMatters\SpecificMattersRepository as SpecificMattersRepository;
use App\Repositories\Offices\OfficeRepository as OfficeRepository;
use App\Repositories\Equipment\EquipmentsRepository as EquipmentsRepository;
use App\Models\Emails;
use App\Models\UserType;
use App\Models\UserOffice;
use Validator;
use DB;
use Log;

class UserController extends Controller
{
    /**
     * @var user
    */
    protected $user, $role, $email, $phone, $accreditation, $license, $libraries, $specificMatter, $office, $equipments;

    public function __construct(UserRepository $user, RolesRepository $role,
                                EmailRepository $email, PhoneRepository $phone,
                                AddressRepository $address, AccreditationRepository $accreditation, LicensesRepository $license, LibrariesRepository $libraries,
                                SpecificMattersRepository $specificMatter, OfficeRepository $office, EquipmentsRepository $equipments)
    {
        $this->user = $user;
        $this->role = $role;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->accreditation = $accreditation;
        $this->license = $license;
        $this->libraries = $libraries;
        $this->specificMatter = $specificMatter;
        $this->office = $office;
        $this->equipments = $equipments;
    }

    public function showCreateUser()
    {
        $allRole = $this->user->getAllRole();
        $listOffices = $this->office->getListOffices();
        $listSpecificMatters = $this->specificMatter->getListTypes();

        return view('users.create-user')->with([
            'allRole'     => $allRole,
            'listOffices' => $listOffices,
            'slug'        => 'user-create',
            'listSpecificMatters' => $listSpecificMatters
        ]);
    }

    public function createUser(Request $req)
    {
        $response = $this->user->createNewUser($req);
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showListUser')->with($response);
    }

    public function showListUser()
    {
        $listUser = $this->user->getAllUser();
        $listRole = $this->role->getListRoles();
        $listSpecificMatters = $this->specificMatter->getListTypes();
        $listOffices = $this->office->getListOffices();

        if (isset($listUser['message'])) {
            return redirect()->route('showListUser')->with($listUser);
        }

        return view('users.list-user', compact('listUser', 'listRole', 'listSpecificMatters', 'listOffices'));
    }

    public function showDetailUser(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        if (isset($detailUser['message'])) {
            return redirect()->route('showListUser')->with($detailUser);
        }

        $emailInfo = $this->email->getListEmailByUser($id);
        $phoneInfo = $this->phone->getListPhoneByUser($id);
        $addressInfo = $this->address->getListAddressByUser($id);
        $accreditationInfo = $this->accreditation->getListAccreditationByUser($id);
        $allRole = $this->user->getAllRole();
        $licenseInfo = $this->license->getListLicenseByUser($id);

        $listCountries = DB::table('countries')->get()->toArray();
        $listSpecificMattersByUser = $this->specificMatter->getListTypesByUser($detailUser->id);
        $listOfficesByUser = $this->office->getListOfficesByUser($detailUser->id);
        $listSpecificMatters = $this->specificMatter->getListTypes();
        $listOffices = $this->office->getListOffices();
        $equipmentInfo = $this->equipments->getListEquipmentByUser($id);
        $listRoleByUser = $this->user->getRolesByUserId($id);

        return view('users.detail-user', compact('detailUser', 'emailInfo', 'phoneInfo', 'addressInfo', 'allRole', 'listCountries', 'accreditationInfo', 'listCountries', 'licenseInfo', 'listSpecificMattersByUser', 'listOfficesByUser', 'listSpecificMatters', 'listOffices', 'equipmentInfo', 'listRoleByUser'))->with(['slug' => 'user-detail']);
    }
    // Fetching states
    public function getStates($countryId)
    {
        $states = DB::table('states')->where('country_id', $countryId)
                        ->get()->toArray();

        return response()->json($states);
    }

    // Fetching cities
    public function getCities($stateId)
    {
        $cities = DB::table('cities')->where('state_id', $stateId)
                        ->get()->toArray();

        return response()->json($cities);
    }

    public function updateDetailUser(Request $req, $id)
    {
        $response = $this->user->updateUser($id, $req);
        $detailUser = $this->user->getDetailUserById($id);

        return response()->json($response);
    }

    public function storeEmail(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        $validateData = Validator::make($req->all(), [
            'email' => 'required|email',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Not enter email yet or Wrong format!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->email->storeEmail($id, $req);
        }

        $emailInfo = $this->email->getListEmailByUser($id);

        return view('users.show-mail-user', compact('detailUser', 'emailInfo', 'response'));
    }

    public function updateUserEmail(Request $req)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);

        $validateData = Validator::make($req->all(), [
            'email' => 'required|email',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Not enter email yet or Wrong format!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->email->updateEmail($req->id, $req);
        }

        $emailInfo = $this->email->getListEmailByUser($req->user_id);

        return view('users.show-mail-user', compact('detailUser', 'emailInfo', 'response'));
    }

    public function storePhone(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        $validateData = Validator::make($req->all(), [
            'phone_number' => 'required|numeric',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Not enter phone number yet or Wrong phone!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->phone->storePhone($id, $req);
        }

        $phoneInfo = $this->phone->getListPhoneByUser($id);

        return view('users.show-phone-user', compact('detailUser', 'phoneInfo', 'response'));
    }

    public function updateUserPhone(Request $req)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);

        $validateData = Validator::make($req->all(), [
            'phone-number' => 'required|numeric',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Not enter phone number yet or Wrong phone!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->phone->updatePhone($req->id, $req);
        }

        $phoneInfo = $this->phone->getListPhoneByUser($req->user_id);

        return view('users.show-phone-user', compact('detailUser', 'phoneInfo', 'response'));
    }

    public function deleteUserPhone(Request $req, $id)
    {
        $response = $this->phone->deletePhone($id);
        $phoneInfo = $this->phone->getListPhoneByUser($req->user_id);

        return view('users.show-phone-user', compact('phoneInfo', 'response'));
    }

    public function deleteUserEmail(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);
        $response = $this->email->deleteEmail($id);
        $emailInfo = $this->email->getListEmailByUser($req->user_id);

        return view('users.show-mail-user', compact('detailUser', 'emailInfo', 'response'));
    }

    public static function getRoleNameByUserId($id)
    {
        $roleName = UserRepository::getRoleNameByUserId($id);

        return $roleName;
    }

    public static function getPrimaryPhoneByUserId($id)
    {
        $phoneNumber = UserRepository::getPrimaryPhoneByUserId($id);

        return $phoneNumber;
    }

    public function changeStatusUser(Request $request, $id)
    {
        $response = $this->user->changeStatusUser($id, $request);

        return response()->json($response);
    }

    public function showListTrashUser()
    {
        $listUser = $this->user->getAllUser();
        $listRole = $this->role->getListRoles();
        $slug = 'user-trash';

        if (isset($listUser['message'])) {
            return redirect()->route('showListUser')->with($listUser);
        }

        return view('users.trash', compact('listUser', 'listRole', 'slug'));
    }

    public function restoreUser(Request $request, $id)
    {
        $result = $this->user->restoreUser($id);

        return redirect()->route('showListTrashUser')->with($result);
    }

    public function deletePermanentlyUser(Request $request, $id)
    {
        $result = $this->user->deletePermanentlyUser($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListTrashUser')->with($result);
    }

    public function deleteUser(Request $request, $id)
    {
        $result = $this->user->deleteUser($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListUser')->with($result);
    }

    public function updateImageUser(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->user->updateImageUser($data, $id);
        $response[$result['alert-type']] = $result['message'];

        return response()->json($response);
    }

    public static function loadImageUserLogin()
    {
        $image = UserRepository::loadImageUserLogin();

        return $image;
    }

    public function storeAddress(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        $validateData = Validator::make($req->all(), [
            'type_address' => 'required',
            'address'      => 'required',
            'country'      => 'required',
            'postcode'     => 'required'
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Data is missing!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->address->storeAddress($id, $req);
        }
        $addressInfo = $this->address->getListAddressByUser($id);
        $listCountries = DB::table('countries')->get()->toArray();

        return view('users.show-address-user', compact('detailUser', 'addressInfo', 'response', 'listCountries'));
    }

    public function deleteUserAddress(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);
        $response = $this->address->deleteAddress($id);
        $addressInfo = $this->address->getListAddressByUser($req->user_id);
        $listCountries = DB::table('countries')->get()->toArray();

        return view('users.show-address-user', compact('detailUser', 'addressInfo', 'response', 'listCountries'));
    }

    public function updateAddress(Request $req)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);

        $validateData = Validator::make($req->all(), [
            'type_address' => 'required',
            'address'      => 'required',
            'country'      => 'required',
            'postcode'     => 'required'
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Data is missing!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->address->updateAddress($req->id, $req);
        }
        $addressInfo = $this->address->getListAddressByUser($req->user_id);
        $listCountries = DB::table('countries')->get()->toArray();

        return view('users.show-address-user', compact('detailUser', 'addressInfo', 'response', 'listCountries'));
    }

    public function storeAccreditation(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        $validateData = Validator::make($req->all(), [
            'qualification' => 'required',
            'date-acquired' => 'required',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Data is missing!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->accreditation->storeAccreditation($id, $req);
        }

        $accreditationInfo = $this->accreditation->getListAccreditationByUser($id);

        $view = view('users.show-accreditation-user', compact('detailUser', 'accreditationInfo', 'response'))->render();

        return response()->json(['html' => $view]);
    }

    public function updateAccreditation(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($id);

        $validateData = Validator::make($req->all(), [
            'qualification' => 'required',
            'date-acquired' => 'required',
        ]);

        if ($validateData->fails()) {
            $response = [
                'message'       => 'Data is missing!',
                'alert-type'    => 'errors'
            ];
        } else {
            $response = $this->accreditation->updateAccreditation($id, $req->all());
        }

        $accreditationInfo = $this->accreditation->getListAccreditationByUser($id);

        $view = view('users.show-accreditation-user', compact('detailUser', 'accreditationInfo', 'response'))->render();

        return response()->json(['html' => $view]);
    }

    public function deleteUserAccreditation(Request $req, $id)
    {
        $detailUser = $this->user->getDetailUserById($req->user_id);
        $response = $this->accreditation->deleteAccreditation($req->user_id, $id);
        $accreditationInfo = $this->accreditation->getListAccreditationByUser($req->user_id);

        $view = view('users.show-accreditation-user', compact('detailUser', 'response', 'accreditationInfo'))->render();

        return response()->json(['html' => $view]);
    }

    public function deleteImageAccreditation(Request $req, $userId)
    {
        $response = $this->accreditation->deleteImageAccreditation($req->all(), $userId);
        $detailUser = $this->user->getDetailUserById($userId);
        $accreditationInfo = $this->accreditation->getListAccreditationByUser($userId);
        return view('users.show-accreditation-user', compact('detailUser', 'response', 'accreditationInfo'));
    }

    public function createUserLicense(Request $req, $userId)
    {
        $data = $req->all();
        $isSuccess = false;

        $result = $this->license->createLicense($data, $userId);

        if ($result) {
            if (isset($data['file'])) {
                $isSuccess = $this->libraries->createLibraries($data['file'], $userId, $result->id);
            }
        } else {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Fail to create license',
            ];
            return $response;
        }

        if ($isSuccess == true) {
            $response = [
                'alert-type' => 'success',
                'message'    => 'Save Successfully',
            ];
        } else {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Fail to create license',
            ];
        }

        $detailUser = $this->user->getDetailUserById($userId);
        $licenseInfo = $this->license->getListLicenseByUser($userId);
        $listCountries = DB::table('countries')->get()->toArray();

        $view = view('users.show-license-user', compact('detailUser', 'licenseInfo', 'response', 'listCountries'))->render();

        return response()->json(['html' => $view]);
    }

    public function deleteLicense(Request $req, $userId)
    {
        $result = $this->license->deleteLicense($req->all(), $userId);
        if ($result) {
            $response = $this->libraries->deleteLicenseLibrary($userId, $req->all()['license_id']);
        } else {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Fail to delete license',
            ];
        }
        $detailUser = $this->user->getDetailUserById($userId);
        $licenseInfo = $this->license->getListLicenseByUser($userId);
        $listCountries = DB::table('countries')->get()->toArray();
        return view('users.show-license-user', compact('detailUser', 'licenseInfo', 'response', 'listCountries'));
    }

    public function editLicense(Request $req, $userId)
    {
        $data = $req->all();
        if (!empty($data['file'])) {
            $result = $this->libraries->editLicenseLibrary($userId, $data['id'], $data['file']);
            if ($result) {
                $response = $this->license->editLicense($data, $userId);
            } else {
                $response = [
                    'alert-type' => 'errors',
                    'message'    => 'Fail to create license',
                ];
            }
        } else {
            $response = $this->license->editLicense($data, $userId);
        }

        $detailUser = $this->user->getDetailUserById($userId);
        $licenseInfo = $this->license->getListLicenseByUser($userId);
        $listCountries = DB::table('countries')->get()->toArray();

        $view = view('users.show-license-user', compact('detailUser', 'licenseInfo', 'response', 'listCountries'))->render();
        return response()->json(['html' => $view]);
    }

    public function deleteImageLicense(Request $req, $userId)
    {
        $response = $this->libraries->deleteImageLicense($req->all(), $userId);
        $detailUser = $this->user->getDetailUserById($userId);
        $licenseInfo = $this->license->getListLicenseByUser($userId);
        $listCountries = DB::table('countries')->get()->toArray();

        $view = view('users.show-license-user', compact('detailUser', 'licenseInfo', 'response', 'listCountries'))->render();
        return response()->json(['html' => $view]);
    }

    public function createUserEquipment(Request $req, $userId)
    {
        $data = $req->all();
        $response = $this->equipments->createEquipment($data, $userId);
        $detailUser = $this->user->getDetailUserById($userId);
        $equipmentInfo = $this->equipments->getListEquipmentByUser($userId);

        $view = view('users.show-equipments-user', compact('detailUser', 'equipmentInfo', 'response'))->render();
        return response()->json(['html' => $view]);
    }

    public function editUserEquipment(Request $req, $userId)
    {
        $data = $req->all();
        $response = $this->equipments->editEquipment($data, $userId);
        $detailUser = $this->user->getDetailUserById($userId);
        $equipmentInfo = $this->equipments->getListEquipmentByUser($userId);

        $view = view('users.show-equipments-user', compact('detailUser', 'equipmentInfo', 'response'))->render();
        return response()->json(['html' => $view]);
    }

    public function deleteUserEquipment(Request $req, $userId)
    {
        $data = $req->all();
        $response = $this->equipments->deleteEquipment($data, $userId);
        $detailUser = $this->user->getDetailUserById($userId);
        $equipmentInfo = $this->equipments->getListEquipmentByUser($userId);

        $view = view('users.show-equipments-user', compact('detailUser', 'equipmentInfo', 'response'))->render();
        return response()->json(['html' => $view]);
    }

    public function updateUserType(Request $req, $userId)
    {
        $detailUser = $this->user->getDetailUserById($userId);
        $dataMatter = $req->all();
        $finalTypes = array();
        $finalOffices = array();

        // delete existing types, offices
        $this->deleteUserType($userId);
        $this->deleteUserOffice($userId);

        if(!empty($dataMatter['select-matter'])) {
            foreach ($dataMatter['select-matter'] as $typeId) {
                array_push($finalTypes, array(
                    'type_id' => $typeId,
                    'user_id' => $userId
                ));
            }
        }

        if(!empty($dataMatter['select-roles'])) {
            $this->user->assignRoleForUser($dataMatter['select-roles'], $userId);
        }

        if(!empty($dataMatter['select-office'])) {
            foreach ($dataMatter['select-office'] as $officeId) {
                array_push($finalOffices, array(
                    'office_id' => $officeId,
                    'user_id' => $userId
                ));
            }
        }

        if (UserType::insert($finalTypes) && UserOffice::insert($finalOffices)) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Update type of user error!',
                'alert-type'    => 'errors'
            ];
        }

        return redirect()->route('showDetailUser', $userId)->with($response);
    }

    public function deleteUserType($userId)
    {
        UserType::where('user_id', $userId)->delete();
    }

    public function deleteUserOffice($userId)
    {
        UserOffice::where('user_id', $userId)->delete();
    }

    public static function getListOfficesByUser($userId)
    {
        $listOfficesByUser = $this->office->getListOfficesByUser($userId);

        return $listOfficesByUser;
    }

    public static function getListSpecificMattersByUser($userId)
    {
        $listSpecificMattersByUser = $this->specificMatter->getListTypesByUser($userId);

        return $listSpecificMattersByUser;
    }
}
