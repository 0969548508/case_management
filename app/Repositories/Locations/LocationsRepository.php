<?php

namespace App\Repositories\Locations;

use App\Repositories\Repository;
use App\Repositories\Locations\LocationsRepositoryInterface;
use DB;
use Log;
use App\Models\Clients;
use App\Models\Contacts;
use App\Models\Locations;
use App\Models\PriceLocations;

class LocationsRepository extends Repository implements LocationsRepositoryInterface {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Locations::class;
    }

    public function createLocation($locationInfo, $listRates)
    {
    	$response = [
            'message'       => 'Save location error!',
            'alert-type'    => 'error'
        ];

        $isDuplicate = $this->validLocationName($locationInfo['name'], NULL, $locationInfo['client_id']);
        if ($isDuplicate) {
            $response = [
                'message'    => 'Location name is duplicate',
                'alert-type' => 'error'
            ];

            return $response;
        }

        if ($locationInfo['is_primary'] == 1) {
            Locations::where('client_id', $locationInfo['client_id'])
                        ->update(['is_primary' => 0]);
        }

    	$result = $this->store($locationInfo);
        $result->status = 1;

    	if ($result) {
            $response = [
                'message'       => 'Save location Successfully!',
                'alert-type'    => 'success',
                'locationId'      => $result->id,
            ];

            // Clone price_list
            $this->clonePriceListForLocation($result->id, $listRates);
        }
        $result->save();

        return $response;
    }

    public function getListLocationsByClient($clientId)
    {
        return $this->findByColumn('client_id', $clientId, 'is_primary', 'DESC');
    }

    public function createContactList($req)
    {
        $response = [
            'message'       => 'Save contact error!',
            'alert-type'    => 'error'
        ];

        $result = Contacts::create($req);

        if ($result) {
            $response = [
                'message'       => 'Save contact successfully!',
                'alert-type'    => 'success',
            ];
        }

        return $response;
    }

    public function getAllContactByLocationId($locationId)
    {
        return Contacts::where('location_id', $locationId)->get()->toArray();
    }

    public function editLocationInformation($req, $locationId)
    {
        $response = [
            'message'       => 'Edit location error!',
            'alert-type'    => 'error'
        ];

        $data = Locations::find($locationId);
        $data->primary_phone        = $req['primary_phone'];
        $data->secondary_phone      = $req['secondary_phone'];
        $data->fax                  = $req['fax'];
        $data->description          = $req['description'];

        if ($data->save()) {
            $response = [
                'message'       => 'Edit location successfully!',
                'alert-type'    => 'success',
            ];
        }

        return $response;
    }

    public function clonePriceListForLocation($locationId, $listRates)
    {
        if (!$listRates->isEmpty()) {
            foreach ($listRates as $key => $rate) {
                $priceLocation = new PriceLocations;
                $priceLocation->location_id      = $locationId;
                $priceLocation->name             = $rate->name;
                $priceLocation->description      = $rate->description;
                $priceLocation->default_price    = $rate->default_price;
                $priceLocation->default_tax_rate = $rate->default_tax_rate;
                $priceLocation->custom_price     = $rate->custom_price;
                $priceLocation->custom_tax_rate  = $rate->custom_tax_rate;
                $priceLocation->rate_id          = $rate->rate_id;
                $priceLocation->is_updated       = $rate->is_updated;

                $priceLocation->save();
            }
        }
    }

    public function updateTitleLocation($data, $clientId, $locationId, $column = 'name')
    {
        $response = [
            'alert-type' => 'success',
            'message'    => 'Save Successfully',
        ];

        $location = $this->model()::find($locationId);

        switch ($column) {
            case 'abn':
                if (isset($data['abn']) && !empty($data['abn'])) {
                    $location->abn = $data['abn'];
                } else {
                    $response = [
                        'alert-type' => 'errors',
                        'message'    => 'Please input location ABN',
                    ];

                    return $response;
                }
                break;

            case 'status':
                if (isset($data['status'])) {
                    $location->status = !$data['status'];
                } else {
                    $response = [
                        'alert-type' => 'errors',
                        'message'    => 'Change status location error',
                    ];

                    return $response;
                }
                break;

            default:
                if (isset($data['name']) && !empty($data['name'])) {
                    $isDuplicate = $this->validLocationName($data['name'], $locationId, $clientId);
                    if ($isDuplicate) {
                        $response = [
                            'alert-type' => 'errors',
                            'message'    => 'Location name is duplicate',
                        ];

                        return $response;
                    }

                    $location->name = $data['name'];
                } else {
                    $response = [
                        'alert-type' => 'errors',
                        'message'    => 'Please input location name',
                    ];

                    return $response;
                }
                break;
        }

        $location->save();

        return $response;
    }

    public function validLocationName($locationName, $locationId = null, $clientId)
    {
        $isExist = Locations::all()->filter(function($record) use ($locationName, $locationId, $clientId) {
            try {
                if ($record->client_id == $clientId && $record->id != $locationId && strtolower($record->name) == strtolower($locationName)) {
                    return $record;
                }
            } catch (DecryptException $e) {
                //
            }
        });

        if (count($isExist) > 0) {
            return true;
        }

        return false;
    }

    public function deleteContact($req, $locationId)
    {
        $listParamContactToDelete = $req['selectedContact'];
        if (empty($listParamContactToDelete)) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Please choase at least one contact to delete!',
            ];

            return $response;
        }

        $countListParam = count($listParamContactToDelete);
        $count = 0;
        foreach ($listParamContactToDelete as $contact) {
            if (Contacts::where('id', $contact)->delete()) {
                $count ++;
            } else {
                $response = [
                    'alert-type' => 'errors',
                    'message'    => 'Delete contact error!',
                ];

                return $response;
            }
        }

        if ($countListParam == $count) {
            $response = [
                'alert-type' => 'success',
                'message'    => 'Delete contact successfully!',
            ];
        }

        return $response;
    }

    public function moveLocationToTrash($locationId)
    {
        $location = $this->model()::find($locationId);
        $location->trash = 1;
        $location->status = 0;
        $location->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($location->name) . ' has been moved to Deleted users list',
        ];
    }

    public function restoreLocation($locationId)
    {
        $location = $this->model()::find($locationId);
        $location->trash = 0;
        $location->status = 1;
        $location->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($location->name) . ' has been restored',
        ];
    }

    public function deletePermanentlyLocation($locationId)
    {
        $respone = [
            'alert-type' => 'error',
            'message'    => 'Delete location faild!',
        ];

        //delete all contacts
        Contacts::where('location_id', $locationId)->delete();

        //delete price locations
        PriceLocations::where('location_id', $locationId)->delete();

        //delete locations
        if (Locations::where('id', $locationId)->delete()) {
            $respone = [
                'alert-type' => 'success',
                'message'    => 'Has been deleted permanently from the system',
            ];
        }

        return $respone;
    }

    public function editContact($req, $contactId)
    {
        $response = [
            'message'       => 'Edit contact error!',
            'alert-type'    => 'error'
        ];

        $data = Contacts::find($contactId);

        $data->name        = $req['edit-name'];
        $data->job_title   = $req['edit_job_title'];
        $data->email       = $req['edit-email'];
        $data->phone       = $req['edit-phone'];
        $data->mobile      = $req['edit-mobile'];
        $data->fax         = $req['edit-fax'];
        $data->note        = $req['edit-note'];

        if ($data->save()) {
            $response = [
                'message'       => 'Edit contact successfully!',
                'alert-type'    => 'success',
            ];
        }

        return $response;
    }

    public function editPriceListLocation($req)
    {
        $response = [
            'message'       => 'Edit price error!',
            'alert-type'    => 'error'
        ];

        $data = $req['item'];
        $countItem = 0;
        $totalItem = count($data);
        foreach ($data as $itemInfo) {
            $data = PriceLocations::find($itemInfo['id']);
            if ($itemInfo['custom_price'] != $data->custom_price)
                $data->is_updated = 1;
            $data->custom_price = $itemInfo['custom_price'];
            if ($data->save()) {
                $countItem ++;
            }
        }

        if ($countItem == $totalItem) {
            $response = [
                'message'       => 'Edit price successfully!',
                'alert-type'    => 'success',
            ];
        }

        return $response;
    }

    public function getListLocationsByClientForMatter($clientId)
    {
        $listLocations = Locations::where('client_id', $clientId)
                        ->where('trash', 0)
                        ->where('status', 1)
                        ->orderBy('is_primary', 'DESC')
                        ->get();

        return $listLocations;
    }
}