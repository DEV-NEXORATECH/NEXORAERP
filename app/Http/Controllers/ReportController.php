<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $projectReports = Project::with(['cashflows', 'salaries', 'reimbursements', 'invoices'])->get()->map(fn ($p) => [
            'project'             => $p,
            'summary'             => $this->cashflowSummary($p->cashflows),
            'profit_margin'       => $p->contract_value > 0 ? ($this->cashflowSummary($p->cashflows)['balance'] / $p->contract_value * 100) : 0,
            'salary_total'        => $p->salaries->where('status', 'paid')->sum('net_salary'),
            'reimbursement_total' => $p->reimbursements->where('status', 'paid')->sum('amount'),
            'invoice_total'       => $p->invoices->sum('amount'),
        ]);

        return view('erp.reports.index', compact('projectReports'));
    }

    public function exportCashflows(): StreamedResponse
    {
        $rows = Cashflow::with('project')->orderBy('transaction_date')->get()->map(fn (Cashflow $flow) => [
            $flow->transaction_date,
            $flow->project?->code ?? 'Company',
            $flow->type,
            $flow->category,
            $flow->vendor,
            $flow->amount,
            $flow->description,
        ]);

        return $this->csv('cashflows.csv', ['Tanggal', 'Project', 'Type', 'Category', 'Vendor', 'Amount', 'Description'], $rows);
    }

    public function exportProjectFinance(): StreamedResponse
    {
        $rows = Project::with('cashflows')->get()->map(function (Project $project) {
            $summary = $this->cashflowSummary($project->cashflows);
            $margin  = $summary['income'] > 0 ? round(($summary['balance'] / $summary['income']) * 100, 2) : 0;

            return [$project->code, $project->name, $project->client, $project->contract_value, $summary['income'], $summary['expense'], $summary['balance'], $margin . '%'];
        });

        return $this->csv('project-finance.csv', ['Code', 'Project', 'Client', 'Contract', 'Income', 'Expense', 'Profit/Loss', 'Margin'], $rows);
    }
}
