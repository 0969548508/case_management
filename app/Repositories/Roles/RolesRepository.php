<?php

namespace App\Repositories\Roles;

use App\Repositories\Repository;
use App\Models\Roles;
use Spatie\Permission\Models\Permission;
use DB;
use Log;

class RolesRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Roles::class;
    }

    public function getListRoles()
    {
        $listRoles = $this->getall();
        $listRoles = json_decode(json_encode($listRoles));

        return $listRoles;
    }

    public function getListPermissionAssigned($roleId)
    {
        $listPermissionAssigned = DB::table('role_has_permissions')
                                    ->where('role_id', $roleId)
                                    ->pluck('permission_id')->toArray();

        return $listPermissionAssigned;
    }

    public function getRoleDetail()
    {
        $listPermission = config('role.list_permission');
        foreach ($listPermission as &$permission) {
            foreach ($permission['list_sub'] as $keySub => &$sub) {
                if ($sub['slug'] === 'matter management') {
                    $keyMilestone = null;
                    $sub['action'] = $this->getAction($sub['slug']);
                    foreach ($sub['action'] as $keyAction => &$action) {
                        if ($action['name'] == 'edit matters milestone')
                            $keyMilestone = $keyAction;

                        if (in_array($action['name'], array('due date', 'internal due date', 'date received', 'date of referral', 'date invoiced', 'date report sent', 'date file returned', 'date interim report sent'))) {
                            $listActionMilestone[] = $action;
                            unset($sub['action'][$keyAction]);
                        }
                    }

                    if (!empty($keyMilestone))
                        $sub['action'][$keyMilestone]['sub_action'] = $listActionMilestone;
                } else {
                    $sub['action'] = $this->getAction($sub['slug']);
                }
            }
        }

        return $listPermission;
    }

    public function getAction($value)
    {
        $action = Permission::select(['id', 'name'])
                            ->where('slug', $value)
                            ->get()->toArray();

        return $action;
    }

    public function saveRole($data, $roleId = null)
    {
        $respone = [
            'alert-type' => 'success',
            'message'    => 'Save Successfully',
        ];

        $permissionList = array();
        $role = new Roles;

        if (!empty($roleId)) {
            $role = Roles::find($roleId);
        } else {
            $count = $this->validRoleName($data['name']);
            if ($count > 0) {
                $respone = [
                    'alert-type' => 'error',
                    'message'    => 'Role name is duplicate',
                ];

                return $respone;
            }

            $role->name = $data['name'];
            $role->description = isset($data['description']) ? $data['description'] : null;
        }

        if (isset($data['permission'])) {
            $permissionList = $data['permission'];
        }
        $role->save();

        $role->syncPermissions($permissionList);
        
        return $respone;
    }

    public function updateContentRole($data, $roleId, $column = 'name')
    {
        $respone = [
            'alert-type' => 'success',
            'message'    => 'Save Successfully',
        ];

        $role = Roles::find($roleId);

        switch ($column) {
            case 'description':
                $role->description = $data['description'];
                break;
            default:
                if (isset($data['name']) && !empty($data['name'])) {
                    $count = $this->validRoleName($data['name'], $roleId);
                    if ($count > 0) {
                        $respone = [
                            'alert-type' => 'errors',
                            'message'    => 'Role name is duplicate',
                        ];

                        return $respone;
                    }

                    $role->name = $data['name'];
                } else {
                    $respone = [
                        'alert-type' => 'errors',
                        'message'    => 'Please input role name',
                    ];

                    return $respone;
                }
                break;
        }

        $role->save();

        return $respone;
    }

    public function validRoleName($roleName, $roleId = null)
    {
        $query = Roles::where('name', $roleName);

        if (!empty($roleId)) {
            $query->where('id', '<>', $roleId);
        }

        $count = $query->count();

        return $count;
    }

    public function deleteRole($roleId)
    {
        $count = DB::table('model_has_roles')
                ->where('role_id', $roleId)
                ->count();

        if ($count > 0) {
            return $respone = [
                'alert-type' => 'error',
                'message'    => 'Role in use, cannot be deleted',
            ];
        }

        DB::table('role_has_permissions')
                    ->where('role_id', $roleId)->delete();

        $role = Roles::find($roleId);
        $role->delete($roleId);

        return $respone = [
            'alert-type' => 'success',
            'message'    => 'Delete Role Successfully',
        ];
    }
}