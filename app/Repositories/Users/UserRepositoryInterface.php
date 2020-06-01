<?php

namespace App\Repositories\Users;

interface UserRepositoryInterface {

    public function createNewUser($data);

    public function updateUser($id, $data);

    public static function getDetailUserById($id);

    public function getAllUser();

    public function getAllRole();

    public static function getRoleNameByUserId($userId);

    public static function getPrimaryPhoneByUserId($userId);

    public function assignRoleForUser($roleName, $userId);

    public function changeStatusUser($id, $data);

    public static function loadImageUserLogin();

    public function ajaxGetListUserByOfficeAndType($officeId, $typeId);
}