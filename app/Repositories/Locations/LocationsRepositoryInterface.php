<?php

namespace App\Repositories\Locations;

interface LocationsRepositoryInterface {
	public function createContactList($req);
	public function editLocationInformation($req, $locationId);
	public function updateTitleLocation($data, $clientId, $locationId, $columnName);
}