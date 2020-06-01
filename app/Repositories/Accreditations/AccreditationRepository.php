<?php

namespace App\Repositories\Accreditations;

use App\Repositories\Repository;
use App\Repositories\Accreditations\AccreditationRepositoryInterface;
use Log;
use App\Models\Accreditations;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AccreditationRepository extends Repository implements AccreditationRepositoryInterface {

    public function model()
    {
        return \App\Models\Accreditations::class;
    }

    public function storeAccreditation($userId, $data)
    {
        $accreditationInfo['qualification'] = $data['qualification'];

        $dateAcquired = Carbon::createFromFormat('d/m/Y', $data['date-acquired'])->format('Y-m-d');
        $accreditationInfo['date_acquired'] = $dateAcquired;

        $accreditationInfo['user_id'] = $userId;

        $flieName = "";
        if (!empty($data['file'])) {
            foreach ($data['file'] as $file) {
                $flieName = $flieName . str_replace(" ", "_", $file->getClientOriginalName()) .",";
            }
        }

        $accreditationInfo['file'] = $flieName;
        $result = $this->store($accreditationInfo);

        $count = 0;
        if ($result) {
            $path = "users/$userId/accreditations/".$result->id;
            if (!empty($data['file'])) {
                foreach ($data['file'] as $file) {
                    $uploadResponse = $this->uploadAccreditation($file, $path, null);
                    if ($uploadResponse->status() == 200) {
                        $count ++;
                    }
                }
            }

            if ($count == count($data['file'])) {
                $response = [
                    'message'       => 'Add Successfully!',
                    'alert-type'    => 'success'
                ];
            } else {
                $response = [
                    'message'       => 'Add accreditation error!',
                    'alert-type'    => 'errors'
                ];
            }
        } else {
            $response = [
                'message'       => 'Add accreditation error!',
                'alert-type'    => 'errors'
            ];
        }

        return $response;
    }

    public function updateAccreditation($id, $data)
    {
        $accreditationInfo = Accreditations::find($data['accreditationsId']);
        $toCheckData = $accreditationInfo;

        $accreditationInfo['qualification'] = $data['qualification'];
        $dateAcquired = Carbon::createFromFormat('d/m/Y', $data['date-acquired'])->format('Y-m-d');
        $accreditationInfo['date_acquired'] = $dateAcquired;

        $fileData = $data['file'];
        $flieName = $toCheckData['file'];
        $listNewFileName = '';
        $disk = Storage::disk(env('DISK_STORAGE'));
        $path = "users/$id/accreditations/".$data['accreditationsId'];
        if (!empty($fileData)) {
            foreach ($fileData as $file) {
                $newFileName = str_replace(" ", "_", $file->getClientOriginalName());
                $listNewFileName = $listNewFileName . str_replace(" ", "_", $newFileName) .",";
                $checkPath = "users/$id/accreditations/".$data['accreditationsId'].'/'.$newFileName;
                if (!$disk->exists($checkPath)) {
                    $uploadResponse = $this->uploadAccreditation($file, $path, null);
                    if ($uploadResponse->status() != 200) {
                        $response = [
                            'message'       => 'Update accreditation error!',
                            'alert-type'    => 'errors'
                        ];
                        return $response;
                    }
                }
            }
        }

        $flieName = $flieName.$listNewFileName.",";
        $explodeFile = explode(",", $flieName);
        $explodeFile = array_unique($explodeFile);
        $listFileToUpdate = '';
        foreach ($explodeFile as $file) {
            if ($file != "") {
                $listFileToUpdate = $listFileToUpdate . str_replace(" ", "_", $file) .",";
            }
        }

        $accreditationInfo['file'] = $listFileToUpdate;
        if ($accreditationInfo->save()) {
            $response = [
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Update accreditation error!',
                'alert-type'    => 'errors'
            ];
        }

        return $response;
    }

    public function getListAccreditationByUser($userId)
    {
        $getListAccreditations = Accreditations::where('user_id', $userId)
                        ->get()->toArray();
        return $getListAccreditations;
    }

    public function deleteAccreditation($userId, $id)
    {
        $path = "users/$userId/accreditations/".$id;
        $disk = Storage::disk(env('DISK_STORAGE'));
        $result = $this->delete($id);
        if ($result) {
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
        } else {
            $response = [
                'message'       => 'Delete accreditation error!',
                'alert-type'    => 'errors'
            ];
        }
        return $response;
    }

    private function uploadAccreditation(UploadedFile $file, $imagePath, $oldItem = null) {
        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
        $disk = Storage::disk(env('DISK_STORAGE'));

        if (!empty($oldItem)) {
            $disk->deleteDirectory($imagePath);
        }

        //create new image
        $disk->putFileAs($imagePath, $file, $fileName);
        return response()->json();
    }

    public function deleteImageAccreditation($data, $userId)
    {
        $oldItem = $data['imgAccreditationsName'];
        $path = "users/$userId/accreditations/".$data['accreditationsId']."/".$oldItem;
        $disk = Storage::disk(env('DISK_STORAGE'));

        $flieName = '';
        if (!empty($oldItem)) {
            $result = $disk->delete($path);

            if ($result) {
                $accreditationDetail = Accreditations::find($data['accreditationsId']);
                $fileInfo = $accreditationDetail->file;
                $explodeFile = explode(",", $fileInfo);
                foreach ($explodeFile as $key => $file) {
                    if ($file == '' || $file == $oldItem) {
                        continue;
                    }
                    $flieName = $flieName . str_replace(" ", "_", $file) .",";
                }

                // update file for Accreditations
                $accreditationDetail['file'] = $flieName;
                if ($accreditationDetail->save()) {
                    $response = [
                        'message'       => 'Delete Successfully!',
                        'alert-type'    => 'success'
                    ];

                    return $response;
                } else {
                    $response = [
                        'message'       => 'Delete accreditation error!',
                        'alert-type'    => 'errors'
                    ];

                    return $response;
                }
            }
        }
    }

    public static function getLinkFile($fileName, $userId, $accreditationsId)
    {
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
        $path = $pathTenant . "users/$userId/accreditations/$accreditationsId/";

        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($path . $fileName);

        return $url;
    }
}