<?php

namespace App\Repositories\FullTextSearch;

use App\Repositories\Repository;
use App\User;
use App\Models\Clients;
use App\Models\Matter;
use Log;

class FullTextSearchRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\FullTextSearch::class;
    }

    public function fullTextSearch($request)
    {
        $result = array();
        $valueSearch = $request->search;

        $listUsers = User::search('user', $valueSearch);
        if (!$listUsers->isEmpty())
            $result['user'] = $listUsers;

        $listCases = Matter::search('matter', $valueSearch);
        if (!$listCases->isEmpty())
            $result['matter'] = $listCases;

        $listClients = Clients::search('client', $valueSearch);
        if (!$listClients->isEmpty())
            $result['client'] = $listClients;

        return $result;
    }

}