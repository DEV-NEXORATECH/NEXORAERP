<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\ReportHelpers;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\VendorBill;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportAgingController extends Controller
{
    use LoadsErpData, ReportHelpers;

    public function index(Request $request, string $type = 'ar'): View
    {
        $aging = match ($type) {
            'ap' => $this->aging(VendorBill::whereIn('status', ['unpaid', 'partial'])->get(), 'due_date'),
            default => $this->aging(Invoice::whereNotIn('status', ['paid', 'void'])->get(), 'due_date'),
        };

        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = BankAccount::orderBy('name')->get();

        return view('erp.reports.aging', compact('aging', 'type', 'projects', 'bankAccounts'));
    }
}
