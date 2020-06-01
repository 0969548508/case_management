<?php

namespace App\Repositories\Clients;

use App\Models\Clients;
use App\Models\Locations;
use App\Models\PriceClients;
use App\Models\PriceLocations;
use App\Models\Contacts;
use App\Repositories\Repository;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Notifications\ClientNotification;
use Auth;
use DB;
use Log;

class ClientsRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Clients::class;
    }

    public function getListClients()
    {
        $listClients = Clients::where('user_id', auth()->user()->id)
                                ->orderBy('name')->get();
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";

        foreach ($listClients as &$client) {
            if (!empty($client->image)) {
                $path = $pathTenant . "clients/$client->id/";
                $client->image = $this->loadImage($path, $client->image);
            }
        }

        return $listClients;
    }

    public function getClientDetail($clientId)
    {
        $clientDetail = $this->findById($clientId);

        if ($clientDetail['user_id'] == auth()->user()->id) {
            if (!empty($clientDetail) && !empty($clientDetail['image'])) {
                $clientId = $clientDetail['id'];
                $tenantDetail = tenancy()->getTenant();
                $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";
                $path = $pathTenant . "clients/$clientId/";
                $clientDetail['image'] = $this->loadImage($path, $clientDetail['image']);
            }
        } else {
            $clientDetail = null;
        }

        return $clientDetail;
    }

    public function createClient($request, $listRates)
    {
        $response = [
            'message'       => 'Save client error!',
            'alert-type'    => 'error'
        ];

        $count = $this->validClientName($request->name);
        if ($count > 0) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Company name is duplicate',
            ];

            return $response;
        }

        isset($request->_token) ? $data = $request->except('_token') : $data = $request->all();

        $data['user_id'] = auth()->user()->id;
        $result = $this->store($data);
        $result->status = 1;

        if ($result) {
            $response = [
                'message'       => 'Save client Successfully!',
                'alert-type'    => 'success',
                'clientId'      => $result->id,
            ];

            // Clone price_list
            $this->clonePriceListForClient($result->id, $listRates);

            if (!empty($request->file('image'))) {
                $clientId = $result->id;
                $file = $request->file('image');
                $path = "clients/$clientId/";

                $uploadResponse = $this->uploadImage($file, $path);

                if($uploadResponse->status() == 200) {
                    $fileName = str_replace(" ", "_", $file->getClientOriginalName());
                    $result->image = $fileName;
                }
            }
        }

        $result->save();

        // notification for create client
        // $dataNotify = array(
        //     'slug'    => 'client-create',
        //     'by' => [
        //         'id'           => auth()->user()->id,
        //         'name'         => auth()->user()->name,
        //         'family_name'  => auth()->user()->family_name,
        //         'image'        => UserRepository::loadImageUserLogin()
        //     ],
        //     'info' => [
        //         'id'   => $result->id,
        //         'name' => $result->name
        //     ]
        // );
        // auth()->user()->notify(new ClientNotification($result, $dataNotify));

        return $response;
    }

    /**
     * To upload image
     */
    private function uploadImage(UploadedFile $file, $imagePath, $oldItem = null, $slug = null)
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

    /**
     * To load image
     */
    private function loadImage($imagePath, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $url = $disk->url($imagePath . $imageName);

        return $url;
    }

    public function updateContentClient($data, $clientId, $column = 'name')
    {
        $response = [
            'alert-type' => 'success',
            'message'    => 'Save Successfully',
        ];

        $client = Clients::find($clientId);

        switch ($column) {
            case 'abn':
                if (isset($data['abn']) && !empty($data['abn'])) {
                    $client->abn = $data['abn'];
                } else {
                    $response = [
                        'alert-type' => 'errors',
                        'message'    => 'Please input company ABN',
                    ];

                    return $response;
                }
                break;
            case 'image':
                if (isset($data['image']) && !empty($data['image'])) {
                    $file = $data['image'];
                    $path = "clients/$clientId/";

                    $oldImage = (!empty($client->image)) ? $client->image : null;
                    $uploadResponse = $this->uploadImage($file, $path, $oldImage);

                    if($uploadResponse->status() == 200) {
                        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
                        $client->image = $fileName;
                    }
                }
                break;
            default:
                if (isset($data['name']) && !empty($data['name'])) {
                    $count = $this->validClientName($data['name'], $clientId);
                    if ($count > 0) {
                        $response = [
                            'alert-type' => 'errors',
                            'message'    => 'Company name is duplicate',
                        ];

                        return $response;
                    }

                    $client->name = $data['name'];
                } else {
                    $response = [
                        'alert-type' => 'errors',
                        'message'    => 'Please input company name',
                    ];

                    return $response;
                }
                break;
        }

        $client->save();

        return $response;
    }

    public function validClientName($clientName, $clientId = null)
    {
        $count = 0;

        Clients::all()->filter(function($record) use ($clientName, $clientId, &$count) {
            try {
                if (strtolower($record->name) == strtolower($clientName) && $record->id != $clientId) {
                    $count++;
                }
            } catch (DecryptException $e) {
                //
            }
        });

        return $count;
    }

    public function clonePriceListForClient($clientId, $listRates)
    {
        if (!$listRates->isEmpty()) {
            foreach ($listRates as $key => $rate) {
                $priceClient = new PriceClients;
                $priceClient->client_id        = $clientId;
                $priceClient->name             = $rate->name;
                $priceClient->description      = $rate->description;
                $priceClient->default_price    = $rate->default_price;
                $priceClient->default_tax_rate = $rate->default_tax_rate;
                $priceClient->custom_price     = $rate->custom_price;
                $priceClient->custom_tax_rate  = $rate->custom_tax_rate;
                $priceClient->rate_id          = $rate->id;

                $priceClient->save();
            }
        }
    }

    public function getListContact($clientId)
    {
        $contactList = Contacts::where('client_id', $clientId)->get();

        return $contactList;
    }

    public function editCompanyInformation($req, $clientId)
    {
        $response = [
            'message'       => 'Edit company error!',
            'alert-type'    => 'error'
        ];

        $count = $this->validClientName($req['company-name'], $clientId);
        if ($count > 0) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Company name is duplicate',
            ];

            return $response;
        }

        $data = Clients::find($clientId);
        $data['name'] = $req['company-name'];
        $data['abn'] = $req['abn-company'];

        $result = $data->save();

        if ($result) {
            $response = [
                'message'       => 'Edit company successfully!',
                'alert-type'    => 'success',
            ];
        }

        return $response;
    }

    public function deleteClient($clientId)
    {
        $clientInfo = Clients::find($clientId);
        $clientInfo->in_trash = 1;
        $clientInfo->status = 0;
        $clientInfo->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($clientInfo->name) . ' has been moved to Deleted clients list',
        ];
    }

    public function restoreClient($clientId)
    {
        $clientInfo = Clients::find($clientId);
        $clientInfo->in_trash = 0;
        $clientInfo->status = 1;
        $clientInfo->save();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($clientInfo->name) . ' has been restored',
        ];
    }

    public static function deletePermanentlyClient($clientId)
    {
        //delete all contacts
        Contacts::where('client_id', $clientId)->delete();

        //delete price locations
        PriceLocations::join('locations', 'locations.id', '=', 'price_locations.location_id')
                        ->where('locations.client_id', $clientId)
                        ->delete();

        //delete price clients
        PriceClients::where('client_id', $clientId)->delete();

        //delete locations
        Locations::where('client_id', $clientId)->delete();

        $clientInfo = Clients::find($clientId);
        $clientName = $clientInfo->name;
        $clientInfo->delete();

        return $respone = [
            'alert-type' => 'success',
            'message'    => ucwords($clientName) . ' has been deleted permanently from the system',
        ];
    }

    public function changeStatusClient($clientData, $clientId)
    {
        $clientInfo = Clients::find($clientId);
        $clientInfo->status = !$clientData['status'];

        if ($clientInfo->save()) {
            $response = [
                'message'       => 'Status change Successfully!',
                'alert-type'    => 'success',
                'status'        => $clientData['status']
            ];
        } else {
            $response = [
                'message'       => 'Status change Fail!',
                'alert-type'    => 'errors'
            ];
        }

        return $response;
    }

    public function deleteContactFromClient($req, $clientId)
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

    public function editPriceListClient($req)
    {
        $response = [
            'message'       => 'Edit price error!',
            'alert-type'    => 'error'
        ];

        $data = $req['item'];
        $countItem = 0;
        $totalItem = count($data);
        foreach ($data as $itemInfo) {
            $data = PriceClients::find($itemInfo['id']);
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

    public static function loadImageForClient($clientId, $imageName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";

        $path = $pathTenant . "clients/$clientId/";
        $url = $disk->url($path . $imageName);

        return $url;
    }

    public function getListClientsForMatter()
    {
        $listClients = $this->getall()
                            ->where('user_id', auth()->user()->id)
                            ->where('in_trash', 0)
                            ->where('status', 1);

        return json_decode(json_encode($listClients));
    }
}