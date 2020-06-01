<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FullTextSearch\FullTextSearchRepository as FullTextSearchRepository;
use Log;

class FullTextSearchController extends Controller
{
    /**
     * @var search
    */
    protected $search;

    public function __construct(FullTextSearchRepository $search)
	{
        $this->search = $search;
    }

    public function fullTextSearch(Request $request)
    {
		if (!empty($request->search)) {
			$result = $this->search->fullTextSearch($request);
	        $view = view('layouts.suggestion', [
	            'items'       => $result,
	            'valueSearch' => $request->search
	        ])->render();
	    } else {
	        $view = view('layouts.suggestion', [
	            'items'       => '',
	            'valueSearch' => $request->search
	        ])->render();
	    }

        return response()->json(['html' => $view]);
    }
}
