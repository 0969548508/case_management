<?php

namespace App\Repositories\Notations;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Notations;
use App\User;
use DB;
use Log;

class NotationsRepository extends Repository {

    public function model()
    {
        return \App\Models\Notations::class;
    }

    public function listNotationsByMatter($matterId)
    {
        $listNotationsByMatter = Notations::where('in_trash', 0)
                                ->where('case_id', $matterId)
                                ->get()->toArray();

        return $listNotationsByMatter;
    }

    public static function getCategoryName($categoryId)
    {
        $categoryName = DB::table('notation_categories')->where('id', $categoryId)
                                                        ->pluck('name')
                                                        ->first();

        return $categoryName;
    }

    public function deleteNotation($notationId, $matterId)
    {
        $notationInfo = Notations::find($notationId);

        Notations::where(['case_id' => $matterId, 'id' => $notationId])
                        ->update(['in_trash' => 1]);

        return $response = [
            'alert-type' => 'success',
            'message'    => ucwords($notationInfo->file) . ' has been moved to Deleted notations list',
        ];
    }

    public function listTrashNotations($matterId)
    {
        $listTrashNotations = Notations::where('in_trash', 1)
                                ->where('case_id', $matterId)
                                ->get()->toArray();

        return $listTrashNotations;
    }

    public function restoreNotation($notationId, $matterId)
    {
        $notationInfo = Notations::find($notationId);
        $response = [
            'alert-type' => 'error',
            'message'    => 'Restore failed',
        ];

        if (Notations::where(['case_id' => $matterId, 'id' => $notationId])
                                ->update(['in_trash' => 0])) {
            return $response = [
                'alert-type' => 'success',
                'message'    => ucwords($notationInfo->file) . ' has been restored',
            ];
        }

        return $response;

    }

    public static function deletePermanentlyNotation($notationId, $matterId)
    {
        $notationInfo = Notations::find($notationId);

        $fileName = $notationInfo->file;
        $disk = Storage::disk(env('DISK_STORAGE'));
        $path = "matters/$matterId/notations/" . $fileName;

        $result = Notations::where(['case_id' => $matterId, 'id' => $notationId])
                        ->delete();

        $response = [
            'alert-type' => 'error',
            'message'    => 'File does not exist',
        ];

        if ($disk->exists($path)) {
            if ($disk->delete($path) && $result) {
                return $response = [
                    'alert-type' => 'success',
                    'message'    => ucwords($notationInfo->file) . ' has been deleted permanently',
                ];
            }
        }

        return $response;
    }

    public function storeNotation($matterId, $data)
    {
        $notationInfo = new Notations;
        $data = $data->all();

        $notationInfo['case_id'] = $matterId;
        $notationInfo['category_id'] = $data['category_id'];
        $notationInfo['note'] = $data['note'];

        $originPath = "matters/$matterId/";
        $fileName = "";
        $disk = Storage::disk(env('DISK_STORAGE'));
        if (!$disk->exists($originPath . "notations/")) {
            $disk->makeDirectory($originPath . 'notations/', 0775, true); //creates directory
        }

        if (!empty($data['file'])) {
            $fileName = $fileName . str_replace(" ", "_", $data['file']->getClientOriginalName());
        }

        $notationInfo['file'] = $fileName;

        if ($notationInfo->save()) {
            $path = "matters/$matterId/notations/";
            if (!empty($data['file'])) {
                $uploadResponse = $this->uploadNotation($data['file'], $path, null);
            }

            $response = [
                'message'       => 'Save notation successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Save notation error!',
                'alert-type'    => 'error'
            ];
        }

        return $response;
    }

    public function updateNotation($data)
    {
        $notationInfo = Notations::find($data->id);
        $data = $data->all();

        $notationInfo['category_id'] = $data['category_id'];
        $notationInfo['note'] = $data['note'];


        if($notationInfo->save()) {
            $response = [
                'message'       => 'Save notation successfully!',
                'alert-type'    => 'success'
            ];
        } else {
            $response = [
                'message'       => 'Save notation error!',
                'alert-type'    => 'error'
            ];
        }

        return $response;
    }

    /**
     * To upload image
     */
    private function uploadNotation(UploadedFile $file, $imagePath, $oldItem = null)
    {
        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
        $disk = Storage::disk(env('DISK_STORAGE'));

        if (!empty($oldItem)) {
            $disk->deleteDirectory($imagePath);
        }

        //create new image
        $disk->putFileAs($imagePath, $file, $fileName);
        return response()->json();
    }
}