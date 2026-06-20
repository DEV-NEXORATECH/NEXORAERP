<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportProjectProfitabilityController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $projectId = $request->integer('project_id') ?: null;

        $projectReports = Project::with(['cashflows', 'salaries', 'reimbursements', 'invoices'])
            ->when($projectId, fn ($q) => $q->whereKey($projectId))
            ->get()
            ->map(fn ($p) => [
                'project'             => $p,
                'summary'             => $this->cashflowSummary($p->cashflows),
                'profit_margin'       => $p->contract_value > 0 ? ($this->cashflowSummary($p->cashflows)['balance'] / $p->contract_value * 100) : 0,
                'salary_total'        => $p->salaries->where('status', 'paid')->sum('net_salary'),
                'reimbursement_total' => $p->reimbursements->where('status', 'paid')->sum('amount'),
                'invoice_total'       => $p->invoices->sum('amount'),
            ]);

        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = BankAccount::orderBy('name')->get();

        return view('erp.reports.project', compact('projectReports', 'projects', 'bankAccounts'));
    }
}
