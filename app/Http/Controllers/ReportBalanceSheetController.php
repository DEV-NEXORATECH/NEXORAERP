<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\ReportHelpers;
use App\Models\BankAccount;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportBalanceSheetController extends Controller
{
    use LoadsErpData, ReportHelpers;

    public function index(Request $request): View
    {
        $balanceSheet = $this->balanceSheet();
        $projects = $this->applyCompanyContext($request, Project::query())->orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = $this->applyCompanyContext($request, BankAccount::query())->orderBy('name')->get();

        return view('erp.reports.balance-sheet', compact('balanceSheet', 'projects', 'bankAccounts'));
    }
}
