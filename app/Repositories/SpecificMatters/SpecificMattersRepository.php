<?php

namespace App\Repositories\SpecificMatters;

use App\Repositories\Repository;
use App\Models\SpecificMatters;
use App\User;
use DB;
use Log;

use Illuminate\Support\Facades\Hash;

class SpecificMattersRepository extends Repository {
    public function model()
    {
        return \App\Models\SpecificMatters::class;
    }

    public static function getListTypesByUser($userId)
    {
        $listTypes = SpecificMatters::join('users_belong_types', 'users_belong_types.type_id', '=', 'types.id')
            ->where('users_belong_types.user_id', '=', $userId)
            ->with('parent')->get()->toArray();

        return $listTypes;
    }

    public function getListTypes()
    {
        $listTypes = SpecificMatters::whereNull('parent_id')
                        ->with(array('children' => function($relation) {
                            $relation->orderBy('name', 'ASC');
                        }))
                        ->orderBy('name', 'ASC')
                        ->get();

        return $listTypes;
    }

    public function addType($data)
    {
        $response = [
            'message'    => 'Add type faild!',
            'alert-type' => 'error'
        ];

        $name = isset($data->name) ? ltrim($data->name, ' ') : '';

        if ($name == "") {
            $response = [
                'message'    => 'Please input name type.',
                'alert-type' => 'error'
            ];

            return $response;
        }

        $parentId = isset($data->parent_id) ? $data->parent_id : null;

        if ($this->validTypeName($name, $parentId) > 0) {
            $response = [
                'message'    => 'Name type is duplicate.',
                'alert-type' => 'error'
            ];

            return $response;
        }

        $typeDetail = new SpecificMatters;
        $typeDetail->name = $name;
        $typeDetail->parent_id = isset($data->parent_id) ? $data->parent_id : null;
        if ($typeDetail->save()) {
            $response = [
                'message'    => 'Add type successfully',
                'alert-type' => 'success',
                'typeDetail' => $typeDetail
            ];
        }

        return $response;
    }

    public function editType($typeId, $data)
    {
        $response = [
            'message'       => 'Edit type successfully',
            'alert-type'    => 'success'
        ];

        $newName = isset($data->name) ? ltrim($data->name, ' ') : '';

        if ($newName == "") {
            $response = [
                'message'       => 'Please input name type.',
                'alert-type'    => 'error'
            ];

            return $response;
        }

        $typeDetail = SpecificMatters::find($typeId);
        $parentId = !empty($typeDetail->parent_id) ? $typeDetail->parent_id : null;

        if ($this->validTypeName($newName, $parentId, $typeId) > 0) {
            $response = [
                'message'       => 'Name type is duplicate.',
                'alert-type'    => 'error'
            ];

            return $response;
        }

        $typeDetail->name = $newName;
        $typeDetail->save();

        return $response;
    }

    public function validTypeName($name, $parentId, $typeId = null)
    {
        $sql = SpecificMatters::where('name', $name);

        if (!empty($typeId))
            $sql = $sql->where('id', '<>', $typeId);

        if (!empty($parentId))
            $sql = $sql->where('parent_id', $parentId);

        $count = $sql->count();

        return $count;
    }

    public function deleteType($typeId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Delete (sub)type successfully!'
        ];

        $typeDetail = SpecificMatters::find($typeId);
        $checkParent = false;

        if (empty($typeDetail->parent_id)) {
            $checkParent = true;
        }

        $checkInUse = self::checkInUseForType($typeId, $checkParent);

        if ($checkInUse) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'This (sub)type is available. Can not delete'
            ];
        } else {
            if (empty($typeDetail->parent_id)) {
                SpecificMatters::where('parent_id', $typeId)->delete();
            }
            $typeDetail->delete();
        }

        return $response;
    }

    public static function checkInUseForType($typeId, $checkParent = false)
    {
        if ($checkParent) {
            $listChild = SpecificMatters::where('parent_id', $typeId)->pluck('id');

            if (empty($listChild)) {
                return false;
            }

            $checkUser = DB::table('users_belong_types')->whereIn('type_id', $listChild)->count();
            $checkMatter = DB::table('cases')->whereIn('type_id', $listChild)->count();

            $checkInUse = ($checkUser == 0 && $checkMatter == 0) ? false : true;
        } else {
            $checkUser = DB::table('users_belong_types')->where('type_id', $typeId)->count();
            $checkMatter = DB::table('cases')->where('type_id', $typeId)->count();

            $checkInUse = ($checkUser == 0 && $checkMatter == 0) ? false : true;
        }
        return $checkInUse;
    }

    public static function getTypeBySubType($subtypeId)
    {
        $listTypes = SpecificMatters::where('id', $subtypeId)
                            ->with('parent')->first();

        return $listTypes;
    }
}