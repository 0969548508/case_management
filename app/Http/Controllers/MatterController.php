<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Users\UserRepository as UserRepository;
use App\Repositories\Clients\ClientsRepository as ClientsRepository;
use App\Repositories\Locations\LocationsRepository as LocationsRepository;
use App\Repositories\Matter\MatterRepository as MatterRepository;
use App\Repositories\SpecificMatters\SpecificMattersRepository as SpecificMattersRepository;
use App\Repositories\Offices\OfficeRepository as OfficeRepository;
use App\Repositories\MatterUser\MatterUserRepository as MatterUserRepository;
use App\Repositories\Roles\RolesRepository as RolesRepository;
use App\Repositories\Notations\NotationsRepository as NotationsRepository;
use View;
use Auth;
use Log;
use DB;
use App\Models\Matter;

class MatterController extends Controller
{
	/**
     * @var clients
    */
    protected $clients;

    /**
     * @var locations
    */
    protected $locations;

    /**
     * @var matter
    */
    protected $matter;
    /**
     * @var matter
    */
    protected $user;
    /**
     * @var specificMatter
    */
    protected $specificMatter;

    /**
     * @var office
    */
    protected $office;
    protected $matterUser;
    protected $roles;
    protected $notations;

    /**
     * @var user
    */

    public function __construct(UserRepository $user, ClientsRepository $clients, LocationsRepository $locations, MatterRepository $matter, SpecificMattersRepository $specificMatter, OfficeRepository $office, MatterUserRepository $matterUser, RolesRepository $roles, NotationsRepository $notations)
    {
        $this->user = $user;
        $this->clients = $clients;
        $this->locations = $locations;
        $this->matter = $matter;
        $this->user = $user;
        $this->specificMatter = $specificMatter;
        $this->office = $office;
        $this->matterUser = $matterUser;
        $this->roles = $roles;
        $this->notations = $notations;
    }

    public function getListMatter()
    {
		$listClient = $this->clients->getListClientsForMatter();
		$listMatter = $this->matter->getListMatter();
        $listTypes = $this->specificMatter->getListTypes();
        $listOffices = $this->office->getListOfficesByUser(auth()->user()->id);

        // get fillters
        $listTypeIdByMatter = $this->matter->getListTypeIdByMatter();
        $listLocationByMatter = $this->matter->getListLocationByMatter();
        $listAssigneeByMatter = $this->matter->getListAssigneeByMatter();

		return view('matter.browse', compact(
			'listClient',
			'listMatter',
            'listTypes',
            'listOffices',
            'listTypeIdByMatter',
            'listLocationByMatter',
            'listAssigneeByMatter'
		));
    }

    public function ajaxGetListLocation(Request $request)
    {
		$listLocation = $this->locations->getListLocationsByClientForMatter($request->clientId);

		$view = View::make('matter.locations-item', [
			'listLocation' => $listLocation
		])->render();

        return response()->json(['html' => $view]);
    }

    public function createMatter(Request $request)
    {
		$response = $this->matter->createMatter($request->all());

		if ($response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        return redirect()->route('getListMatter')->with($response);
    }

    public function getMatterDetail($matterId)
    {
        return $this->showTab('information', $matterId);
    }

    public function getListNotations($matterId)
    {
        return $this->showTab('notations', $matterId);
    }

    public function getListFiles($matterId)
    {
        return $this->showTab('files', $matterId);
    }

    protected function showTab($tab, $matterId)
    {
        $slug = 'matter-detail-information';
        $detailMatter = $this->matter->detailMatter($matterId)->toArray();
        $listLocation = $this->locations->getListLocationsByClient($detailMatter['client_id']);
        $currentUserId = auth()->user()->id;
        $listOffices = $this->office->getListOfficesByUser($currentUserId);
        $listSpecificMatter = $this->specificMatter->getListTypesByUser($currentUserId);

        // get list matter user
        $listMatterUser = $this->matterUser->getListMatterUser($matterId)->toArray();
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
        $path = $pathTenant . "users/".auth()->user()->id."/";
        foreach ($listMatterUser as &$value) {
            if (!empty($value['userofmatter'][0]['image'])) {
                $value['userofmatter'][0]['image'] = $this->loadImage($path, $value['userofmatter'][0]['image']);
            }
            $value['rolesuser'] = $this->user->getRolesByUserId($value['user_id']);
        }

        // end get list matter user

        $listRole = $this->roles->getListRoles();

        $assignToMe = $this->matter->getAssignToMe($matterId);
        $milestones = $this->matter->getMilestones($matterId);

        switch ($tab) {
            case 'information':
                $activeTab = 'information';
                $listUser = $this->user->getAllUser()->toArray();
                $listCountries = DB::table('countries')->get()->toArray();

                // get account/instructing client
                $accountClientInfo = $this->matter->getAccountClientInformation($matterId);
                $accountClientLocationInfo = $this->matter->getAccountClientLocationInformation($matterId);
                $accountContactInfo = $this->matter->getAccountContactInformation($matterId);

                $instructingClientInfo = $this->matter->getInstructingClientInformation($matterId);
                $instructingClientLocationInfo = $this->matter->getInstructingClientLocationInformation($matterId);
                $instructingContactInfo = $this->matter->getInstructingContactInformation($matterId);
                // end get account/instructing client

                $insurerInfo = $this->matter->getInsurerInformation($matterId);

                return view('matter.matter-detail-information.detail-information', compact([
                    'slug',
                    'activeTab',
                    'detailMatter',
                    'listLocation',
                    'listUser',
                    'listOffices',
                    'listSpecificMatter',
                    'listMatterUser',
                    'listRole',
                    'listCountries',
                    'accountClientInfo',
                    'accountClientLocationInfo',
                    'accountContactInfo',
                    'instructingClientInfo',
                    'instructingClientLocationInfo',
                    'instructingContactInfo',
                    'insurerInfo',
                    'assignToMe',
                    'milestones'
                ]));
                break;

            case 'notations':
                $checkViewTrash = false;
                $activeTab = 'notations';
                $listCategories = DB::table('notation_categories')->get()->toArray();
                $listNotations = $this->notations->listNotationsByMatter($matterId);
                $listTrashNotations = $this->notations->listTrashNotations($matterId);

                return view('matter.matter-detail-notations.detail-notation', compact([
                    'slug',
                    'activeTab',
                    'detailMatter',
                    'listLocation',
                    'listOffices',
                    'listSpecificMatter',
                    'listMatterUser',
                    'listRole',
                    'assignToMe',
                    'listCategories',
                    'listNotations',
                    'listTrashNotations',
                    'checkViewTrash',
                    'milestones'
                ]));
                break;

            case 'trash':
                $checkViewTrash = true;
                $activeTab = 'notations';
                $detailMatter = $this->matter->detailMatter($matterId)->toArray();

                $listCategories = DB::table('notation_categories')->get()->toArray();
                $listNotations = $this->notations->listNotationsByMatter($matterId);
                $listTrashNotations = $this->notations->listTrashNotations($matterId);

                return view('matter.matter-detail-notations.trash-notation', compact([
                    'slug',
                    'activeTab',
                    'detailMatter',
                    'listLocation',
                    'listOffices',
                    'listSpecificMatter',
                    'listMatterUser',
                    'listRole',
                    'assignToMe',
                    'listCategories',
                    'listNotations',
                    'listTrashNotations',
                    'checkViewTrash',
                    'milestones'
                ]));
                break;

            case 'files':
                $activeTab = 'files';
                $path = "matters/$matterId/";
                $fileManagers = $this->matter->getFileManagers($matterId, $path);

                return view('matter.matter-detail-files.detail-file', compact([
                    'slug',
                    'activeTab',
                    'detailMatter',
                    'listLocation',
                    'listOffices',
                    'listSpecificMatter',
                    'listMatterUser',
                    'listRole',
                    'assignToMe',
                    'fileManagers',
                    'milestones'
                ]));
                break;

            default:
                # code...
                break;
        }
    }

    public function assignInvestigator(Request $req, $matterId)
    {
        $response = $this->matter->assignInvestigator($req->all(), $matterId);
        $activeTab = $req->all()['acctive-tab'];
        return $this->redirectFuntion($activeTab, $matterId, $response);
    }

    public function getListUserByOfficeAndType(Request $request)
    {
        $listUsers = array();
        if (!empty($request->officeId) && !empty($request->typeId))
            $listUsers = $this->user->ajaxGetListUserByOfficeAndType($request->officeId, $request->typeId);

        $view = View::make('matter.users-assign-item', [
            'listUsers' => $listUsers
        ])->render();

        return response()->json(['html' => $view]);
    }

    public function editGeneralInfo(Request $req, $matterId)
    {
        $response = $this->matter->editGeneralInfo($req->all(), $matterId);
        $activeTab = $req->all()['acctive-tab'];
        return $this->redirectFuntion($activeTab, $matterId, $response);
    }

    private function loadImage($imagePath, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($imagePath . $imageName);

        return $url;
    }

    public function createAccountInstructingInformation(Request $req, $matterId)
    {
        $response = $this->matter->handleAccountInstructingInformation($req->all(), $matterId);
        return redirect()->route('getMatterDetail', $matterId)->with($response);
    }

    public function editAccountInstructingInformation(Request $req, $id, $matterId)
    {
        $response = $this->matter->handleEditAccountInstructingInformation($req->all(), $id);
        return redirect()->route('getMatterDetail', $matterId)->with($response);
    }

    public function getListUserByRoleId($roleId, $matterId)
    {
        $listUserExistedMatter = DB::table('users')
            ->join('cases_users', 'cases_users.user_id', '=', 'users.id')
            ->where('cases_users.case_id','=', $matterId)
            ->where('cases_users.role_id','=', $roleId)
            ->select(['users.id', 'users.name'])
            ->get()->toArray();

        $listUsersByRoleId = DB::table('model_has_roles')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->select('users.*')
            ->where('users.status', "active")
            ->where('model_has_roles.role_id', $roleId)
            ->get()->toArray();

        $users = array();
        if (!empty($listUserExistedMatter)) {
            foreach ($listUserExistedMatter as $userExistedMatter) {
                foreach ($listUsersByRoleId as $key => &$usersByRoleId) {
                    if ($userExistedMatter->id == $usersByRoleId->id) {
                        unset($listUsersByRoleId[$key]);
                    }
                }
            }
        }

        $users = $listUsersByRoleId;

        $view = view('matter.matter-detail-information.matter-detail-information-list-user-by-role', compact('users'))->render();

        return response()->json(['html' => $view]);
    }

    public function createInsurerInformation(Request $req, $matterId)
    {
        $response = $this->matter->createInsurerInformation($req->all(), $matterId);
        return redirect()->route('getMatterDetail', $matterId)->with($response);
    }

    public function editInsurerInformation(Request $req, $insurerId, $matterId)
    {
        $response = $this->matter->editInsurerInformation($req->all(), $insurerId);
        return redirect()->route('getMatterDetail', $matterId)->with($response);
    }

    public function deleteUserMatter($roleId, $matterId, $userId)
    {
        $result = $this->matter->deleteUserMatter($roleId, $matterId, $userId);
    }

    public function deleteNotation(Request $req, $notationId, $matterId)
    {
        $result = $this->notations->deleteNotation($notationId, $matterId);
        $result = json_encode($result);
        $listNotations = $this->notations->listNotationsByMatter($matterId);
        $detailMatterId = $matterId;

        return view('matter.matter-detail-notations.show-notations-table', compact('result', 'listNotations', 'detailMatterId'));
    }

    public function restoreNotation(Request $req, $notationId, $matterId)
    {
        $result = $this->notations->restoreNotation($notationId, $matterId);

        return redirect()->route('getListTrashNotations', $matterId)->with($result);
    }

    public function deletePermanentlyNotation(Request $req, $notationId, $matterId)
    {
        $result = $this->notations->deletePermanentlyNotation($notationId, $matterId);

        return redirect()->route('getListTrashNotations', $matterId)->with($result);
    }

    public function updateNotation(Request $req)
    {
        $result = $this->notations->updateNotation($req);
        $result = json_encode($result);
        $listNotations = $this->notations->listNotationsByMatter($req->matter_id);
        $detailMatterId = $req->matter_id;

        return view('matter.matter-detail-notations.show-notations-table', compact('result', 'listNotations', 'detailMatterId'));
    }

    public function storeNotation(Request $req, $matterId)
    {
        $result = $this->notations->storeNotation($matterId, $req);
        $result = json_encode($result);
        $listNotations = $this->notations->listNotationsByMatter($matterId);
        $detailMatterId = $matterId;

        $view = view('matter.matter-detail-notations.show-notations-table', compact('result', 'listNotations', 'detailMatterId'))->render();
        return response()->json(['html' => $view]);
    }

    public function getListTrashNotations($matterId)
    {
        return $this->showTab('trash', $matterId);
    }

    public function openFolder(Request $request)
    {
        $detailMatter = $this->matter->detailMatter($request->matterId)->toArray();

        $fileManagers = $this->matter->getFileManagers($request->matterId, $request->path, false);
        $pathToBack = $this->matter->getPathToBack($request->path);

        $view = View::make('matter.matter-detail-files.files-datatable', [
            'detailMatter' => $detailMatter,
            'fileManagers' => $fileManagers,
            'pathToBack'   => $pathToBack,
            'currentPath'  => $request->path
        ])->render();

        return response()->json(['html' => $view]);
    }


    public function addDate(Request $req, $matterId)
    {
        $response = $this->matter->addMilestone($req, $matterId);
        $activeTab = $req->all()['acctive-tab'];
        return $this->redirectFuntion($activeTab, $matterId, $response);
    }

    public function createFolder(Request $request)
    {
        $result = $this->matter->createFolder($request);
        if ($result['alert-type'] == 'error') {
            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->path, $result);
    }

    public function uploadFiles(Request $request)
    {
        $isSuccess = false;
        if (isset($request->file)) {
            $isSuccess = $this->matter->uploadFiles($request);
        }

        if ($isSuccess == true) {
            $result = [
                'alert-type' => 'success',
                'message'    => 'Upload File(s) Successfully',
            ];
        } else {
            $result = [
                'alert-type' => 'error',
                'message'    => 'Fail to upload file(s)',
            ];

            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->path, $result);
    }

    public function deleteFolder(Request $request)
    {
        $result = $this->matter->deleteFolder($request->path, $request->matterId, true);

        if ($result['alert-type'] == 'error') {
            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->currentPath, $result);
    }

    public function deleteFile(Request $request)
    {
        $result = $this->matter->deleteFile($request->path, $request->matterId, true);

        if ($result['alert-type'] == 'error') {
            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->currentPath, $result);
    }

    protected function getViewCurrent($matterId, $path, $result)
    {
        $detailMatter = $this->matter->detailMatter($matterId)->toArray();

        $fileManagers = $this->matter->getFileManagers($matterId, $path, false);
        $pathToBack = $this->matter->getPathToBack($path);

        $view = View::make('matter.matter-detail-files.files-datatable', [
            'detailMatter' => $detailMatter,
            'fileManagers' => $fileManagers,
            'pathToBack'   => $pathToBack,
            'currentPath'  => $path,
            'result'       => $result
        ])->render();

        return response()->json(['html' => $view]);
    }

    public function editFolder(Request $request)
    {
        $result = $this->matter->editFolder($request);

        if ($result['alert-type'] == 'error') {
            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->currentPath, $result);
    }

    public function editFile(Request $request)
    {
        $result = $this->matter->editFile($request);

        if ($result['alert-type'] == 'error') {
            return $result;
        }

        return $this->getViewCurrent($request->matterId, $request->currentPath, $result);
    }

    public function redirectFuntion($activeTab, $matterId, $response)
    {
        if (!empty($activeTab)) {
            if ($activeTab == "information") {
                return redirect()->route('getMatterDetail', $matterId)->with($response);
            } elseif ($activeTab == "notations") {
                return redirect()->route('getListNotations', $matterId)->with($response);
            } elseif ($activeTab == "trash") {
                return redirect()->route('getListTrashNotations', $matterId)->with($response);
            } elseif ($activeTab == "files") {
                return redirect()->route('getListFiles', $matterId)->with($response);
            }
        } else {
            return redirect()->route('getMatterDetail', $matterId)->with($response);
        }
    }
}
