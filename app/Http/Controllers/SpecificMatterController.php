<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SpecificMatters\SpecificMattersRepository as SpecificMattersRepository;
use App\Models\SpecificMatters;
use Log;

class SpecificMatterController extends Controller
{
    /**
     * @var specificMatter
    */
    protected $specificMatter;

	public function __construct(SpecificMattersRepository $specificMatter)
	{
        $this->specificMatter = $specificMatter;
    }

    public function showListType()
    {
    	$listTypes = $this->specificMatter->getListTypes();

    	return view('type-subtype.browse', compact('listTypes'));
    }

    public function ajaxAddType(Request $request)
    {
    	$response = $this->specificMatter->addType($request);

    	$result = [
            'message'    => $response['message'],
            'alert-type' => $response['alert-type']
        ];

    	if ($response['alert-type'] == 'success') {
    		if (isset($request->parent_id)) {
	    		$view = view('type-subtype.subtype-item', [
					'typeDetail'     => $response['typeDetail'],
					'checkTransform' => $request->check_transform
				])->render();
			} else {
				$view = view('type-subtype.type-item', [
					'typeDetail'     => $response['typeDetail']
				])->render();
			}

			$result['html'] = $view;
    	}

    	return $result;
    }

    public function ajaxEditType(Request $request, $typeId)
    {
    	$result = $this->specificMatter->editType($typeId, $request);

    	return $result;
    }

    public function deleteType($typeId)
    {
		$response = $this->specificMatter->deleteType($typeId);

		return redirect()->route('showListType')->with($response);
    }
}
