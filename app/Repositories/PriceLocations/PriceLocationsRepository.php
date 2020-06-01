<?php

namespace App\Repositories\PriceLocations;

use App\Repositories\Repository;
use DB;
use Log;

class PriceLocationsRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\PriceLocations::class;
    }
}