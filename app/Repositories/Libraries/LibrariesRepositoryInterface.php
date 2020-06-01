<?php

namespace App\Repositories\Libraries;

interface LibrariesRepositoryInterface {
	public function createLibraries($req, $userId, $licenseId);
}