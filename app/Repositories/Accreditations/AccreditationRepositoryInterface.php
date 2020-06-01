<?php

namespace App\Repositories\Accreditations;

interface AccreditationRepositoryInterface {

	public function storeAccreditation($userId, $data);

    public function updateAccreditation($id, $data);

    public function getListAccreditationByUser($userId);

    public function deleteAccreditation($userId, $id);

    public static function getLinkFile($fileName, $userId, $accreditationsId);
}