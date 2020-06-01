<?php

namespace App\Repositories\Emails;

interface EmailRepositoryInterface {

	public function storeEmail($userId, $data);

    public function updateEmail($id, $data);

    public function getListEmailByUser($userId);

    public function deleteEmail($id);
}