<?php

namespace App\Repositories\License;

interface LicensesRepositoryInterface {
	public function createLicense($req, $userId);
	public function getListLicenseByUser($userId);
	public function deleteLicense($req, $userId);
	public function editLicense($req, $userId);
}