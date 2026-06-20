<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Http\Traits\AppliesListFilters;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use AppliesListFilters;

    public function index(Request $request): View
    {
        $auditLogs = $this->applyListFilters(AuditLog::with('user:id,name')->latest(), $request, ['action', 'description'])->paginate(50)->withQueryString();
        return view('erp.audit-logs.index', compact('auditLogs'));
    }
}
