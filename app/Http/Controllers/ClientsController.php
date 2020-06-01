<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Clients\ClientsRepository as ClientsRepository;
use App\Repositories\Locations\LocationsRepository as LocationsRepository;
use App\Repositories\Rates\RatesRepository as RatesRepository;
use App\Repositories\PriceClients\PriceClientsRepository as PriceClientsRepository;
use App\Models\Clients;
use Auth;
use Log;

class ClientsController extends Controller
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
     * @var rates
    */
    protected $rates;

    /**
     * @var priceClients
    */
    protected $priceClients;

	public function __construct(ClientsRepository $clients, LocationsRepository $locations, RatesRepository $rates, PriceClientsRepository $priceClients)
	{
        $this->clients = $clients;
        $this->locations = $locations;
        $this->rates = $rates;
        $this->priceClients = $priceClients;
    }

    public function showListClient()
    {
		$listClients = $this->clients->getListClients();
        $columns = array('Company Name', 'Locations', 'Contacts', 'Active Cases', 'Status');

    	return view('clients.browse', [
			'columns'     => $columns,
			'listClients' => $listClients,
    	]);
    }

    public function showCreateClient()
    {
    	return view('clients.edit-add', [
			'slug'    => 'client-create',
    	]);
    }

    public function showDetailClient($id)
    {
		$clientDetail = $this->clients->getClientDetail($id);
        if (empty($clientDetail)) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Client not found.',
            ];

            return redirect()->route('showListClient')->with($response);
        }
        $listLocations = $this->locations->getListLocationsByClient($id);

		return view('clients.read', [
            'slug'          => 'client-detail',
			'clientDetail'  => $clientDetail,
            'listLocations' => $listLocations,
		]);
    }

    public function createClient(Request $request)
    {
        return $this->saveClientAndLocation($request);
    }

    public function createAndAddMoreInfo(Request $request)
    {
        return $this->saveClientAndLocation($request, true);
    }

    protected function saveClientAndLocation($request, $addMoreInfo = false)
    {
        $listRates = $this->rates->showListRate();

		$responseClient = $this->clients->createClient($request, $listRates);

		if (isset($responseClient['alert-type']) && $responseClient['alert-type'] == 'error') {
			return redirect()->back()->withInput()->with($responseClient);
		}

		// Location info
		$locationInfo['client_id'] = $responseClient['clientId'];
		$locationInfo['name'] = $request->location_name;
		$locationInfo['abn'] = $request->location_abn;
		$locationInfo['is_primary'] = (isset($request->is_primary) && $request->is_primary == 'on') ? 1 : 0;

        $listRates = $this->priceClients->findByColumn('client_id', $responseClient['clientId']);
		$response = $this->locations->createLocation($locationInfo, $listRates);

		if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
			return redirect()->back()->withInput()->with($response);
		}

		if ($addMoreInfo) {
			// redirect to location detail
			return redirect()->route('showStatiticLocation', $response['locationId'])->with($responseClient);
		} else {
			return redirect()->route('showListClient')->with($responseClient);
		}
    }

    public function updateContentClient(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->clients->updateContentClient($data, $id, $data['column']);
        $response[$result['alert-type']] = $result['message'];

        return response()->json($response);
    }

    public function updateImageClient(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->clients->updateContentClient($data, $id, $data['column']);
        $response[$result['alert-type']] = $result['message'];

        return response()->json($response);
    }

    public function showContactListClient($clientId)
    {
        return $this->showTab('contact-list', $clientId);
    }

    public function showPriceListClient($clientId)
    {
        return $this->showTab('price-list', $clientId);
    }

    public function showEditPriceListClient($clientId)
    {
        return $this->showTab('edit-price-list', $clientId);
    }

    protected function showTab($tab, $clientId)
    {
        $clientDetail = $this->clients->getClientDetail($clientId);
        if (empty($clientDetail)) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Client not found.',
            ];

            return redirect()->route('showListClient')->with($response);
        }

        $activeTab = true;
        $slug      = 'client-detail';
        switch ($tab) {
            case 'contact-list':
                $contactList = $this->clients->getListContact($clientId);

                if(auth()->user()->hasAnyPermission('view client contacts')) {
                    return view('clients.contact-list', compact([
                        'activeTab',
                        'slug',
                        'clientDetail',
                        'contactList'
                    ]));
                }
                break;

            case 'price-list':
                $listRates = $this->priceClients->findByColumn('client_id', $clientId, 'name');

                if(auth()->user()->hasAnyPermission('view client price list')) {
                    return view('clients.price-list', compact([
                        'activeTab',
                        'slug',
                        'clientDetail',
                        'listRates'
                    ]));
                }
                break;

            case 'edit-price-list':
                $listRates = $this->priceClients->findByColumn('client_id', $clientId, 'name');

                if(auth()->user()->hasAnyPermission('edit client price list')) {
                    return view('clients.edit-price-list', compact([
                        'activeTab',
                        'slug',
                        'clientDetail',
                        'listRates'
                    ]));
                }
                break;
            
            default:
                # code...
                break;
        }

        return redirect()->route('showDetailClient', $clientId);
    }

    public function showListTrashClient()
    {
        $listClients = $this->clients->getListClients();
        $columns = array('Company Name', 'Location', 'Contact', 'Active Case', 'Action');

        return view('clients.trash', [
            'slug'        => 'client-trash',
            'columns'     => $columns,
            'listClients' => $listClients,
        ]);
    }

    public function deleteClient(Request $request, $id)
    {
        $result = $this->clients->deleteClient($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListClient')->with($result);
    }

    public function restoreClient(Request $request, $id)
    {
        $result = $this->clients->restoreClient($id);

        return redirect()->route('showListTrashClient')->with($result);
    }

    public function deletePermanentlyClient(Request $request, $id)
    {
        $result = $this->clients->deletePermanentlyClient($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListTrashClient')->with($result);
    }

    public function changeStatusClient(Request $request, $id)
    {
        $response = $this->clients->changeStatusClient($request->all(), $id);

        return response()->json($response);
    }

    public function deleteContactFromClient(Request $req, $clientId)
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

        $result = $this->clients->deleteContactFromClient($data, $clientId);

        $response[$result['alert-type']] = $result['message'];

        return response()->json($response);
    }

    public function editContactFromClient(Request $req, $clientId, $contactId)
    {
        $response = $this->locations->editContact($req->except(['_token']), $contactId);
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showContactListClient', $clientId)->with($response);
    }

    public function editPriceListClient(Request $req, $clientId)
    {
        $response = $this->clients->editPriceListClient($req->except(['_token']));
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }
        return redirect()->route('showPriceListClient', $clientId)->with($response);
    }
}
