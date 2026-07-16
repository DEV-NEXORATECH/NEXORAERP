<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Http\Traits\AppliesListFilters;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    use AppliesListFilters;

    public function index(Request $request): View|JsonResponse
    {
        $query = $this->applyListFilters(AuditLog::with('user:id,name')->latest(), $request, ['action', 'description'])
            ->when($request->filled('user_id'), fn ($auditQuery) => $auditQuery->where('user_id', $request->integer('user_id')));

        $auditLogs = $query->paginate(50)->withQueryString();
        if ($this->isApi()) {
            return $this->respond($auditLogs);
        }
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('erp.audit-logs.index', compact('auditLogs', 'users'));
    }
}
