<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Roles\RolesRepository as RolesRepository;
use App\Models\Roles;
use Log;

class RolesController extends Controller
{
    /**
     * @var roles
    */
    protected $roles;

	public function __construct(RolesRepository $roles)
	{
        $this->roles = $roles;
    }

    public function getListRoles()
    {
    	$listRoles = $this->roles->getListRoles();

    	return view('roles.browse', ['listRoles' => $listRoles]);
    }

    public function getRoleDetail($id)
    {
    	$listPermission = $this->roles->getRoleDetail();
    	$roleDetails = Roles::find($id);

    	if (empty($roleDetails)) {
    		return redirect()->route('showListRoles')->with([
                'message'       => 'Role does not exist',
                'alert-type'    => 'error'
            ]);
    	}

    	$roleDetails = $roleDetails->toArray();
    	$listPermissionAssigned = $this->roles->getListPermissionAssigned($id);

    	return view('roles.read', [
            'slug'                   => 'role-detail',
    		'roleId'                 => $id,
    		'listPermission'         => $listPermission,
    		'listPermissionAssigned' => $listPermissionAssigned,
    		'roleDetails'            => $roleDetails
    	]);
    }

    public function viewcreateRole()
    {
    	$listPermission = $this->roles->getRoleDetail();

        return view('roles.edit-add', [
            'slug'           => 'role-create',
            'listPermission' => $listPermission
        ]);
    }

    public function createRole(Request $request)
    {
    	$data = $request->all();
    	$result = $this->roles->saveRole($data);

    	if ($result['alert-type'] == 'error') {
    		return redirect()->back()->withInput()->with($result);
    	}

    	return redirect()->route('showListRoles')->with($result);
    }

    public function updateContentRole(Request $request, $id)
    {
    	$data = $request->all();
    	$result = $this->roles->updateContentRole($data, $id, $data['column']);
    	$response[$result['alert-type']] = $result['message'];

    	return response()->json($response);
    }

    public function updateRole(Request $request, $id)
    {
    	$data = $request->all();
    	$result = $this->roles->saveRole($data, $id);

    	return redirect()->route('getRoleDetail', $id)->with($result);
    }

    public function deleteRole(Request $request, $id)
    {
        $result = $this->roles->deleteRole($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListRoles')->with($result);
    }
}
