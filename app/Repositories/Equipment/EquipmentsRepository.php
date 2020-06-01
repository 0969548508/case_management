<?php

namespace App\Repositories\Equipment;

use App\Repositories\Repository;
use App\Repositories\Equipment\EquipmentsRepositoryInterface;
use App\Models\Equipments;
use Carbon\Carbon;
use DB;
use Log;

class EquipmentsRepository extends Repository implements EquipmentsRepositoryInterface {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Equipments::class;
    }

    public function createEquipment($data, $userId)
    {
        $checkModel = $this->checkDuplicateModel($userId, $data, "add");

        if (!$checkModel) {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Save fail. Model equipment already!',
            ];

            return $response;
        }

        $response = [
            'alert-type' => 'errors',
            'message'    => 'Fail to create equipment',
        ];

        $dataEquipment['user_id'] = $userId;
        $dataEquipment['type'] = $data['type'];
        $dataEquipment['model'] = $data['model'];
        if ($this->store($dataEquipment)) {
            $response = [
                'alert-type' => 'success',
                'message'    => 'Save Successfully',
            ];
        }

        return $response;
    }

    public function getListEquipmentByUser($userId) {
        $getListEquipments = Equipments::where('user_id', $userId)->get()->toArray();
        return $getListEquipments;
    }

    public function editEquipment($data, $userId)
    {
        $checkModel = $this->checkDuplicateModel($userId, $data, "edit");

        if (!$checkModel) {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Save fail. Model equipment already!',
            ];

            return $response;
        }

        $response = [
            'alert-type' => 'errors',
            'message'    => 'Fail to update equipment',
        ];

        $dataEquipment = Equipments::find($data['id']);
        if (isset($dataEquipment)) {
            $dataEquipment['user_id'] = $userId;
            $dataEquipment['type'] = $data['type'];
            $dataEquipment['model'] = $data['model'];
            if ($dataEquipment->save()) {
                $response = [
                    'alert-type' => 'success',
                    'message'    => 'Update Successfully',
                ];
            } 
        }

        return $response;
    }

    public function deleteEquipment($data, $userId)
    {
        $response = [
            'alert-type' => 'errors',
            'message'    => 'Fail to delete equipment',
        ];

        if (Equipments::where('id', $data['id'])->delete()) {
            $response = [
                'alert-type' => 'success',
                'message'    => 'Delete Successfully',
            ];
        }

        return $response;
    }

    public function checkDuplicateModel($userId, $data, $type) {
        $getListEquipments = $this->getListEquipmentByUser($userId);
        if (isset($getListEquipments) && !empty($getListEquipments)) {
            // check duplicate model equipment
            if ($type == "edit") {
                foreach ($getListEquipments as $equipments) {
                    if ($equipments['id'] != $data['id'] && strtolower($equipments['model']) == strtolower($data['model'])) {
                        return false;
                    }
                }
            } else {
                foreach ($getListEquipments as $equipments) {
                    if (strtolower($equipments['model']) == strtolower($data['model'])) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}