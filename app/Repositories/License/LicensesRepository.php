<?php

namespace App\Repositories\License;

use App\Repositories\Repository;
use App\Repositories\License\LicensesRepositoryInterface;
use Illuminate\Http\UploadedFile;
use App\Models\Licenses;
use App\Models\Libraries;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use Log;

class LicensesRepository extends Repository implements LicensesRepositoryInterface {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Licenses::class;
    }

    public function createLicense($data, $userId)
    {
        $result = '';
        if (isset($data['type-license']) || isset($data['state-license']) || isset($data['country-license']) || isset($data['number-license']) || isset($data['expiration'])) {
            $dataLicense['user_id'] = $userId;
            $dataLicense['type'] = $data['type-license'];
            $dataLicense['state'] = $data['state-license'];
            $dataLicense['country'] = $data['country-license'];
            $dataLicense['number'] = $data['number-license'];
            if (isset($data['expiration'])) {
                $expiration = Carbon::createFromFormat('d/m/Y', $data['expiration'])->format('Y-m-d');
                $dataLicense['expiration'] = $expiration;
            }

            $result = $this->store($dataLicense);
        }

        return $result;
    }

    public function getListLicenseByUser($userId)
    {
        $getListLicense = Licenses::where('user_id', $userId)->get()->toArray();

        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
        foreach ($getListLicense as &$license) {
            $libraries = Libraries::where('license_id', '=', $license['id'])->get()->toArray();
            foreach ($libraries as &$library) {
                $path = $pathTenant . "users/$userId/licenses/".$license['id']."/";
                $library['image'] = self::loadImage($path, $library['image']);
            }
            $license['image_info'] = $libraries;
        }

        return $getListLicense;
    }

    private function loadImage($imagePath, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($imagePath . $imageName);

        return $url;
    }

    public function deleteLicense($req, $userId)
    {
        if (Licenses::where('id', $req['license_id'])->delete()) {
            $deleteLibraries = Libraries::where('license_id', $req['license_id'])->delete();
            if ($deleteLibraries) {
                return true;
            }
        }

        return false;
    }

    public function editLicense($req, $userId)
    {
        $response = [
            'alert-type' => 'success',
            'message'    => 'Edit license Successfully',
        ];

        $licenseInfo = Licenses::find($req['id']);
        $oldListNameFile = $licenseInfo['file'];

        $licenseInfo['type'] = $req['type'];
        $licenseInfo['country'] = $req['country'];
        $licenseInfo['state'] = $req['state'];
        $licenseInfo['number'] = $req['number'];
        if (isset($req['expiration'])) {
            $expiration = Carbon::createFromFormat('d/m/Y', $req['expiration'])->format('Y-m-d');
            $licenseInfo['expiration'] = $expiration;
        }

        if (!$licenseInfo->save()) {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Edit license error',
            ];
            return $response;
        }

        return $response;
    }
}