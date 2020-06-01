<?php

namespace App\Repositories\Phones;

interface PhoneRepositoryInterface {

    public function storePhone($userId, $data);

    public function updatePhone($id, $data);

    public function getListPhoneByUser($userId);

    public function deletePhone($id);
}