<?php

namespace App\Repositories\ChangePassword;

interface ChangePasswordRepositoryInterface {
	public function userChangePassword($req, $user);
	public function updatePasswordForUser($req);
}