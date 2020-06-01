<?php

namespace App\Repositories\Equipment;

interface EquipmentsRepositoryInterface {
	public function createEquipment($req, $userId);
	public function getListEquipmentByUser($userId);
	public function editEquipment($req, $userId);
}