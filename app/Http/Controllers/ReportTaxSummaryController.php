<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\ReportHelpers;
use App\Models\BankAccount;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportTaxSummaryController extends Controller
{
    use LoadsErpData, ReportHelpers;

    public function index(Request $request): View
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();

        $taxSummary = $this->taxSummary($dateFrom, $dateTo);

        $projects = $this->applyCompanyContext($request, Project::query())->orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = $this->applyCompanyContext($request, BankAccount::query())->orderBy('name')->get();

        return view('erp.reports.tax', compact('taxSummary', 'projects', 'bankAccounts'));
    }
}
