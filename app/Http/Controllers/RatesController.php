<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Rates\RatesRepository as RatesRepository;
use App\Models\Rates;
use Log;

class RatesController extends Controller
{
    /**
     * @var rates
    */
    protected $rates;

	public function __construct(RatesRepository $rates)
	{
        $this->rates = $rates;
    }

    public function showListRate()
    {
    	$listRates = $this->rates->showListRate();

    	return view('rates.browse', compact('listRates'));
    }

    public function viewcreateRate()
    {
        return view('rates.edit-add')->with([
            'slug'    => 'rate-create',
        ]);
    }

    public function createRate(Request $request)
    {
        $response = $this->rates->createRate($request->all());
        if ($response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        return redirect()->route('showListRate')->with($response);
    }

    public function showDetailRate($id)
    {
        $rateDetail = $this->rates->showDetailRate($id);
        if (empty($rateDetail)) {
            $response = [
                'alert-type' => 'error',
                'message'    => 'Rate not found.',
            ];

            return redirect()->route('showListRate')->with($response);
        }

        return view('rates.edit-add', [
            'slug'          => 'rate-detail',
            'rateId'        => $id,
            'rateDetail'    => $rateDetail,
        ]);
    }

    public function updateRate(Request $request, $id)
    {
        $response = $this->rates->updateRate($request, $id);

        if ($response['alert-type'] == 'error') {
            return redirect()->back()->withInput()->with($response);
        }

        return redirect()->route('showListRate')->with($response);
    }

    public function deleteRate(Request $request, $id)
    {
        $result = $this->rates->deleteRate($id);
        if (isset($response['alert-type']) && $response['alert-type'] == 'error') {
            return redirect()->back()->with($response);
        }

        return redirect()->route('showListRate')->with($result);
    }
}
