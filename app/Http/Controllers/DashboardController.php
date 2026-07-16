<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Reimbursement;
use App\Models\Salary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View|JsonResponse
    {
        $cashflows      = $this->applyCompanyContext($request, Cashflow::with(['project:id,code']))->get();
        $projects       = $this->applyCompanyContext($request, Project::with(['cashflows', 'salaries', 'reimbursements', 'invoices']))->latest()->get();
        $invoices       = $this->applyCompanyContext($request, Invoice::query())->get();
        $salaries       = $this->applyCompanyContext($request, Salary::query())->get();
        $reimbursements = $this->applyCompanyContext($request, Reimbursement::query())->get();
        $proposals      = $this->applyCompanyContext($request, Proposal::query())->get();
        $employees      = $this->applyCompanyContext($request, Employee::query())->get();

        $summary = $this->cashflowSummary($cashflows);

        $filterCounts = [
            'projects_total'         => $projects->count(),
            'projects_active'        => $projects->where('status', 'active')->count(),
            'proposals_pending'      => $proposals->where('status', 'sent')->count(),
            'invoices_outstanding'   => $invoices->whereNotIn('status', ['paid', 'void'])->count(),
            'employees_total'        => $employees->count(),
            'reimbursements_pending' => $reimbursements->where('status', 'pending')->count(),
        ];

        $startOfMonth = now()->startOfMonth()->toDateString();
        $dashboard = [
            'month_income'          => $cashflows->where('type', 'income')->filter(fn ($c) => $c->transaction_date >= $startOfMonth)->sum('amount'),
            'month_expense'         => $cashflows->where('type', 'expense')->filter(fn ($c) => $c->transaction_date >= $startOfMonth)->sum('amount'),
            'outstanding_invoice'   => $invoices->whereNotIn('status', ['paid', 'void'])->sum(fn ($i) => $i->amount - $i->paid_amount),
            'month_payroll'         => $salaries->where('period', now()->format('Y-m'))->sum('net_salary'),
            'pending_reimbursement' => $reimbursements->where('status', 'pending')->sum('amount'),
        ];

        $monthlyChart = collect(range(5, 0))->map(function ($i) use ($cashflows) {
            $month = now()->subMonths($i)->format('Y-m');
            return [
                'label'   => now()->subMonths($i)->format('M'),
                'income'  => $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $month)->sum('amount'),
                'expense' => $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $month)->sum('amount'),
            ];
        });
        $monthlyMax = max($monthlyChart->pluck('income')->max(), $monthlyChart->pluck('expense')->max(), 1);

        $totalExpense = $cashflows->where('type', 'expense')->sum('amount');
        $expenseBreakdown = $cashflows->where('type', 'expense')
            ->groupBy('cost_type')
            ->map(fn ($items) => [
                'type'    => $items->first()->cost_type,
                'amount'  => $items->sum('amount'),
                'percent' => $totalExpense > 0 ? round(($items->sum('amount') / $totalExpense) * 100, 1) : 0,
            ])
            ->sortByDesc('amount')->take(6)->values();

        $projectReports = $projects->map(fn ($p) => [
            'project'             => $p,
            'summary'             => $this->cashflowSummary($p->cashflows),
            'profit_margin'       => $p->contract_value > 0 ? ($this->cashflowSummary($p->cashflows)['balance'] / $p->contract_value * 100) : 0,
            'salary_total'        => $p->salaries->where('status', 'paid')->sum('net_salary'),
            'reimbursement_total' => $p->reimbursements->where('status', 'paid')->sum('amount'),
            'invoice_total'       => $p->invoices->sum('amount'),
        ]);
        $projectChart = $projectReports->sortByDesc(fn ($r) => $r['summary']['income'])->take(5);
        $projectMax   = max($projectChart->max(fn ($r) => $r['summary']['income']), $projectChart->max(fn ($r) => $r['summary']['expense']), 1);

        $notifications = collect([
            ['type' => 'Reimbursement pending',  'count' => $reimbursements->where('status', 'pending')->count(), 'message' => 'Menunggu approval.', 'danger' => false],
            ['type' => 'Invoice jatuh tempo',    'count' => $invoices->whereNotIn('status', ['paid', 'void'])->filter(fn ($i) => $i->due_date && $i->due_date <= now()->addDays(7)->toDateString())->count(), 'message' => 'Jatuh tempo 7 hari.', 'danger' => true],
            ['type' => 'Salary belum dibayar',   'count' => $salaries->where('status', 'approved')->count(), 'message' => 'Menunggu pembayaran.', 'danger' => false],
            ['type' => 'Proposal menunggu',      'count' => $proposals->where('status', 'sent')->count(), 'message' => 'Menunggu approval client.', 'danger' => false],
        ])->filter(fn ($item) => $item['count'] > 0);

        if ($this->isApi()) {
            return $this->respond(compact(
                'summary', 'filterCounts', 'dashboard',
                'monthlyChart', 'expenseBreakdown', 'projectChart', 'notifications'
            ));
        }

        return view('erp.dashboard.index', compact(
            'summary', 'filterCounts', 'dashboard',
            'monthlyChart', 'monthlyMax', 'expenseBreakdown',
            'projectChart', 'projectMax', 'notifications'
        ));
    }
}
