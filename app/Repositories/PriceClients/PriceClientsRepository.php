<?php

namespace App\Repositories\PriceClients;

use App\Repositories\Repository;
use DB;
use Log;

class PriceClientsRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\PriceClients::class;
    }
}