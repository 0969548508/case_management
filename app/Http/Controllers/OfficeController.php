<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Offices\OfficeRepository as OfficeRepository;
use DB;
use Log;

class OfficeController extends Controller
{
    /**
     * @var office
    */
    protected $office;

     public function __construct(OfficeRepository $office)
    {
        $this->office = $office;
    }

    public function getListOffice()
    {
    	$listCountries = DB::table('countries')->get()->toArray();
    	$listOffice = $this->office->getListOffices();

    	return view('office.browse', compact(
    		'listCountries',
    		'listOffice'
    	));
    }

    public function getListStates(Request $request, $countryId)
    {
        $listStates = DB::table('states')->where('country_id', $countryId)
                        ->get()->toArray();
        if (isset($request->action) && $request->action == 'edit') {
        	$view = view('office.edit-state-city-item', [
	        	'type'       => 'state',
				'listStates' => $listStates
			])->render();
        } else {
	        $view = view('office.state-city-item', [
	        	'type'       => 'state',
				'listStates' => $listStates
			])->render();
	    }

        return response()->json(['html' => $view]);
    }

    public function getListCities($stateId)
    {
        $listCities = DB::table('cities')->where('state_id', $stateId)
                        ->get()->toArray();
        if (isset($request->action) && $request->action == 'edit') {
        	$view = view('office.edit-state-city-item', [
	        	'type'       => 'city',
				'listStates' => $listCities
			])->render();
        } else {
	        $view = view('office.state-city-item', [
	        	'type'       => 'city',
				'listCities' => $listCities
			])->render();
	    }

        return response()->json(['html' => $view]);
    }

    public function createOffice(Request $request)
    {
    	$response = $this->office->storeOffice($request->all());

		if ($response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        return redirect()->route('getListOffice')->with($response);
    }

    public function showEditForm($officeId)
    {
    	$officeDetail = $this->office->getOfficeDetail($officeId);
    	$listCountries = DB::table('countries')->get()->toArray();
    	$listStates = array();
    	$listCities = array();

    	if (!empty($officeDetail->country))
    		$listStates = DB::table('states')->where('country_id', $officeDetail->country)->get()->toArray();

        if (!empty($officeDetail->state))
        	$listCities = DB::table('cities')->where('state_id', $officeDetail->state)->get()->toArray();

    	$view = view('office.edit-form', compact(
    		'officeDetail',
    		'listCountries',
    		'listStates',
    		'listCities'
    	))->render();

        return response()->json(['html' => $view]);
    }

    public function updateOffice(Request $request, $officeId)
    {
    	$response = $this->office->updateOffice($request, $officeId);

		if ($response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        return redirect()->route('getListOffice')->with($response);
    }

    public function showDeleteForm($officeId)
    {
    	$response = $this->office->deleteOffice($officeId);

    	return redirect()->route('getListOffice')->with($response);
    }
}
