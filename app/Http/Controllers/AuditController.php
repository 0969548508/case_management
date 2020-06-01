<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Audit\AuditRepository as AuditRepository;
use Auth;
use Log;

class AuditController extends Controller
{
    protected $audit;

    public function __construct(AuditRepository $audit)
    {
        $this->audit = $audit;
    }

    public function index()
    {
		$columns = array('User Name', 'Role', 'Date & Time', 'Action');
		$listAuditLogs = $this->audit->showListAuditLog();

		return view('audit.browse', [
			'columns'       => $columns,
			'listAuditLogs' => $listAuditLogs,
		]);
    }
}
