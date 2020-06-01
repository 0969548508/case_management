<?php

namespace App\Repositories\Addresses;

interface AddressRepositoryInterface {

    public function storeAddress($userId, $data);

    public function updateAddress($id, $data);

    public function getListAddressByUser($userId);

    public function deleteAddress($id);

    public static function getCountryIdByName($countryName);

    public static function getStateIdByName($stateName);

    public static function getCityIdByName($cityName);
}