<?php

namespace App\Repositories\Rates;

use App\Repositories\Repository;
use App\Models\Rates;
use App\Models\PriceClients;
use App\Models\PriceLocations;
use DB;
use Log;

class RatesRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Rates::class;
    }

    public function showListRate()
    {
        $listRates = $this->getall();

        return $listRates;
    }

    public function createRate($data)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Create rate error!',
        ];

        $count = $this->validRateName($data['name']);
        if ($count > 0) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Rate name is duplicate',
            ];

            return $response;
        }

        if (strpos($data['default_price'], '-') && $data['default_price'][0] != '-') {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'The default price is the wrong format',
            ];

            return $response;
        }

        $data['default_price'] = (preg_replace('/[^0-9]/', '', $data['default_price']) == '') ? 0 : $data['default_price'];

        $result = $this->store($data);
        if ($result) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Create rate successfully!',
            ];
        }

        return $response;
    }

    public function showDetailRate($rateId)
    {
        $rateDetail = $this->findById($rateId);

        return $rateDetail;
    }

    public function updateRate($request, $rateId)
    {
        isset($request->_token) ? $data = $request->except('_token') : $data = $request->all();
        $rate = Rates::find($rateId);

        $response = [
            'alert-type'    => 'error',
            'message'       => 'Update rate error!',
        ];

        $count = $this->validRateName($data['name'], $rateId);
        if ($count > 0) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Rate name is duplicate',
            ];

            return $response;
        }

        if (strpos($data['default_price'], '-') && $data['default_price'][0] != '-') {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'The default price is the wrong format',
            ];

            return $response;
        }

        $data['default_price'] = (preg_replace('/[^0-9]/', '', $data['default_price']) == '') ? 0 : $data['default_price'];

        if ($rate->update($data)) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Update rate successfully!',
            ];

            $priceClients = PriceClients::where(['rate_id' => $rateId, 'is_updated' => 0])->update($data);
            $priceLocations = PriceLocations::where(['rate_id' => $rateId, 'is_updated' => 0])->update($data);
        }

        return $response;
    }

    public function validRateName($rateName, $rateId = null)
    {
        $count = 0;

        Rates::all()->filter(function($record) use ($rateName, $rateId, &$count) {
            try {
                if (strtolower($record->name) == strtolower($rateName) && $record->id != $rateId) {
                    $count++;
                }
            } catch (DecryptException $e) {
                //
            }
        });

        return $count;
    }

    public function deleteRate($rateId)
    {
        $priceClients = PriceClients::where(['rate_id' => $rateId, 'is_updated' => 0])->delete();
        $priceLocations = PriceLocations::where(['rate_id' => $rateId, 'is_updated' => 0])->delete();

        $rate = Rates::find($rateId);
        $rate->delete($rateId);

        return $respone = [
            'alert-type' => 'success',
            'message'    => 'Delete Rate Successfully',
        ];
    }
}