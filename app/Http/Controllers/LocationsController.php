<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Clients\ClientsRepository as ClientsRepository;
use App\Repositories\Locations\LocationsRepository as LocationsRepository;
use App\Repositories\PriceClients\PriceClientsRepository as PriceClientsRepository;
use App\Repositories\PriceLocations\PriceLocationsRepository as PriceLocationsRepository;
use Auth;
use Log;

class LocationsController extends Controller
{
    /**
     * @var clients
    */
    protected $clients;

    /**
     * @var locations
    */
    protected $locations;

    /**
     * @var priceClients
    */
    protected $priceClients;

    /**
     * @var priceLocations
    */
    protected $priceLocations;

    public function __construct(ClientsRepository $clients, LocationsRepository $locations, PriceClientsRepository $priceClients, PriceLocationsRepository $priceLocations)
    {
        $this->clients = $clients;
        $this->locations = $locations;
        $this->priceClients = $priceClients;
        $this->priceLocations = $priceLocations;
    }

    public function showStatiticLocation($id)
    {
        return $this->showTab('statitic', $id);
    }

    public function showContactListLocation($id)
    {
        return $this->showTab('contact-list', $id);
    }

    public function showAgreementLocation($id)
    {
        return $this->showTab('agreements', $id);
    }

    public function showPriceListLocation($id)
    {
        return $this->showTab('price-list', $id);
    }

    public function showEditPriceListLocation($id)
    {
        return $this->showTab('edit-price-list', $id);
    }

    public function showCreateLocation($clientId)
    {
        $clientDetail = $this->clients->getClientDetail($clientId);

        return view('locationManagement.create-location', [
            'slug'         => 'location-create',
            'clientDetail' => $clientDetail,
        ]);
    }


    public function createNewLocation(Request $req, $clientId)
    {
        $req['client_id'] = $clientId;
        return $this->saveLocation($req);
    }

    public function createAndAddMoreInfoLocation(Request $req, $clientId)
    {
        $req['client_id'] = $clientId;
        return $this->saveLocation($req, true);
    }

    protected function saveLocation($req, $addMoreInfo = false)
    {
        $req['is_primary'] = (isset($req->is_primary) && $req->is_primary == 'on') ? 1 : 0;

        $listRates = $this->priceClients->findByColumn('client_id', $req->all()['client_id']);
        $response = $this->locations->createLocation($req->all(), $listRates);

        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        if ($addMoreInfo) {
            // redirect to location detail
            return redirect()->route('showStatiticLocation', $response['locationId'])->with($response);
        } else {
            return redirect()->route('showDetailClient', $req->client_id)->with($response);
        }
    }

    protected function showTab($tab, $locationId)
    {
        $locationDetail = $this->locations->findById($locationId);
        $clientDetail = $this->clients->getClientDetail($locationDetail->client_id);
        $activeTab = true;
        $slug = 'location-detail';
        $contactList = $this->locations->getAllContactByLocationId($locationId);

        switch ($tab) {
            case 'statitic':
                return view('locationManagement.statitic', compact([
                    'slug',
                    'activeTab',
                    'locationId',
                    'locationDetail',
                    'clientDetail'
                ]));
                break;

            case 'contact-list':
                if(auth()->user()->hasAnyPermission('view client location contacts')) {
                    return view('locationManagement.contact-list', compact([
                        'slug',
                        'activeTab',
                        'locationId',
                        'locationDetail',
                        'clientDetail',
                        'contactList'
                    ]));
                }
                break;

            case 'agreements':
                if(auth()->user()->hasAnyPermission('view agreements')) {
                    return view('locationManagement.agreements', compact([
                        'slug',
                        'activeTab',
                        'locationId',
                        'locationDetail',
                        'clientDetail'
                    ]));
                }
                break;

            case 'price-list':
                if(auth()->user()->hasAnyPermission('view client location price list')) {
                    $listRates = $this->priceLocations->findByColumn('location_id', $locationId, 'name');

                    return view('locationManagement.price-list', compact([
                        'slug',
                        'activeTab',
                        'locationId',
                        'locationDetail',
                        'clientDetail',
                        'listRates'
                    ]));
                }
                break;

            case 'edit-price-list':
                if(auth()->user()->hasAnyPermission('edit client location price list')) {
                    $listRates = $this->priceLocations->findByColumn('location_id', $locationId, 'name');

                    return view('locationManagement.edit-price-list', compact([
                        'slug',
                        'activeTab',
                        'locationId',
                        'locationDetail',
                        'clientDetail',
                        'listRates'
                    ]));
                }
                break;
            
            default:
                # code...
                break;
        }

        return redirect()->route('showStatiticLocation', $locationId);
    }

    public function createContactListLocation(Request $req, $locationId)
    {
        $req['location_id'] = $locationId;
        $response = $this->locations->createContactList($req->all());
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showContactListLocation', $locationId)->with($response);
    }

    public function editLocationInfo(Request $req, $routeName, $locationId)
    {
        $response = $this->locations->editLocationInformation($req->except(['_token']), $locationId);
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route($routeName, $locationId)->with($response);
    }

    public function updateTitleLocation(Request $req, $clientId, $locationId)
    {
        $data = $req->all();
        $result = $this->locations->updateTitleLocation($data, $clientId, $locationId, $data['column']);
        $response[$result['alert-type']] = $result['message'];

        return response()->json($response);
    }

    public function updateCompanyInfo(Request $req, $routeName, $clientId, $locationId)
    {
        $response = $this->clients->editCompanyInformation($req->except(['_token']), $clientId);
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route($routeName, $locationId)->with($response);
    }

    public function deleteContact(Request $req, $locationId)
    {
        $data = $req->all();
        if (empty($data)) {
            $response = [
                'alert-type' => 'errors',
                'message'    => 'Delete contact error!',
            ];

            $response['alert-type'] = $result['message'];
            return response()->json($response);
        }

        $result = $this->locations->deleteContact($data, $locationId);

        $response[$result['alert-type']] = $result['message'];
        return response()->json($response);
    }

    public function showListTrashLocation ($clientId)
    {
        $slug = 'location-trash';
        $clientDetail = $this->clients->getClientDetail($clientId);
        $listLocations = $this->locations->getListLocationsByClient($clientId);

        return view('locationManagement.location-trash', compact('slug', 'listLocations', 'clientDetail'));
    }

    public function moveLocationToTrash(Request $req, $clientId, $locationId)
    {
        $result = $this->locations->moveLocationToTrash($locationId);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showDetailClient', $clientId)->with($result);
    }

    public function restoreLocation(Request $req, $clientId, $locationId)
    {
        $result = $this->locations->restoreLocation($locationId);

        return redirect()->route('showListTrashLocation', $clientId)->with($result);
    }

    public function deletePermanentlyLocation(Request $req, $clientId, $locationId)
    {
        $result = $this->locations->deletePermanentlyLocation($locationId);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListTrashLocation', $clientId)->with($result);
    }

    public function editContact(Request $req, $locationId, $contactId)
    {
        $response = $this->locations->editContact($req->except(['_token']), $contactId);
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showContactListLocation', $locationId)->with($response);
    }

    public function editPriceListLocation(Request $req, $locationId)
    {
        $response = $this->locations->editPriceListLocation($req->except(['_token']));
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showPriceListLocation', $locationId)->with($response);
    }
}
