<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Http\Traits\AppliesListFilters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    use AppliesListFilters;

    public function index(Request $request): View|JsonResponse
    {
        $auditLogs = $this->applyListFilters(AuditLog::with('user:id,name')->latest(), $request, ['action', 'description'])->paginate(50)->withQueryString();
        if ($this->isApi()) {
            return $this->respond($auditLogs);
        }
        return view('erp.audit-logs.index', compact('auditLogs'));
    }
}
