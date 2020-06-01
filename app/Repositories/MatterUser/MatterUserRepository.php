<?php

namespace App\Repositories\MatterUser;

use App\Repositories\Repository;
use Carbon\Carbon;
use Auth;
use DB;
use Log;
use App\Models\MattersUsers;

class MatterUserRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\MattersUsers::class;
    }

    public function getListMatterUser($matterId)
    {
        $listMatterUser = MattersUsers::where('case_id', $matterId)->with(['userofmatter' ,'matter'])->get();

        return $listMatterUser;
    }

    private function loadImage($imagePath, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($imagePath . $imageName);

        return $url;
    }
}