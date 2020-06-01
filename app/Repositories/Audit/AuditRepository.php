<?php

namespace App\Repositories\Audit;

use App\Repositories\Repository;
use OwenIt\Auditing\Models\Audit;
use DB;
use Log;

class AuditRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \OwenIt\Auditing\Models\Audit::class;
    }

    public function showListAuditLog()
    {
        $listAuditLogs = Audit::orderBy('created_at', 'DESC')->get();

        return $listAuditLogs;
    }

    public static function getInfoByModel($model, $modelId, $columns)
    {
        $info = $model::select($columns)->find($modelId);

        return $info;
    }
}