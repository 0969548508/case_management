<?php

namespace App\Repositories\Libraries;

use App\Repositories\Repository;
use App\Repositories\Libraries\LibrariesRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Libraries;
use DB;
use Log;

class LibrariesRepository extends Repository implements LibrariesRepositoryInterface {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Libraries::class;
    }

    public function createLibraries($req, $userId, $licenseId)
    {
        $fileArr = $req;
        $countFile = count($fileArr);
        $successTime = 0;
        $path = "users/$userId/licenses/".$licenseId;

        foreach ($fileArr as $file) {
            $uploadResponse = $this->uploadLicenseLibrary($file, $path, null);
            if($uploadResponse->status() == 200) {
                $fileName = str_replace(" ", "_", $file->getClientOriginalName());
                $library['license_id'] = $licenseId;
                $library['image'] = $fileName;
                if ($this->store($library)) {
                    $successTime ++;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($countFile == $successTime) {
            return true;
        }

        return false;
    }

    private function uploadLicenseLibrary(UploadedFile $file, $imagePath, $oldItem = null) {
        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
        $disk = Storage::disk(env('DISK_STORAGE'));

        if (!empty($oldItem)) {
            $disk->deleteDirectory($imagePath);
        }

        //create new image
        $disk->putFileAs($imagePath, $file, $fileName);
        return response()->json();
    }

    public function editLicenseLibrary ($userId, $licenseId, $fileData) {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $path = "users/$userId/licenses/".$licenseId;
        foreach ($fileData as $file) {
            $newFileName = str_replace(" ", "_", $file->getClientOriginalName());
            $checkPath = "users/$userId/licenses/".$licenseId.'/'.$newFileName;
            if (!$disk->exists($checkPath)) {
                $uploadResponse = $this->uploadLicenseLibrary($file, $path, null);
                if ($uploadResponse->status() == 200) {
                    $library['license_id'] = $licenseId;
                    $library['image'] = $newFileName;
                    if (!$this->store($library)) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public function deleteLicenseLibrary ($userId, $licenseId)
    {
        $path = "users/$userId/licenses/".$licenseId;
        $disk = Storage::disk(env('DISK_STORAGE'));

        if ($disk->deleteDirectory($path)) {
            $response = [
                'message'       => 'Delete Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Delete accreditation error!',
                'alert-type'    => 'errors'
            ];
        }

        return $response;
    }

    public function deleteImageLicense($data, $userId)
    {
        $oldItem = $data['imgLicenseName'];
        $explodePath = explode("users", $data['imgLicenseName']);
        $path = "users".$explodePath[1];

        $disk = Storage::disk(env('DISK_STORAGE'));

        $flieName = '';
        if (!empty($oldItem)) {
            $result = $disk->delete($path);

            if ($result) {
                if (Libraries::where('id', $data['imgId'])->delete()) {
                    $response = [
                        'message'       => 'Delete Successfully!',
                        'alert-type'    => 'success'
                    ];

                    return $response;
                } else {
                    $response = [
                        'message'       => 'Delete license error!',
                        'alert-type'    => 'errors'
                    ];

                    return $response;
                }
            }
        }
    }
}