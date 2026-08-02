<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Attendance;
use App\Models\BlogPost;
use App\Models\Cashflow;
use App\Models\CmsSection;
use App\Models\Employee;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\SalesInquiry;
use App\Models\SalesLead;
use App\Models\SalesOrder;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\VendorBill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $vendorBills    = $this->applyCompanyContext($request, VendorBill::query())->get();
        $tasks          = $this->applyCompanyContext($request, Task::with('assignee:id,name'))->get();
        $inquiries      = $this->applyCompanyContext($request, SalesInquiry::query())->get();
        $leads          = $this->applyCompanyContext($request, SalesLead::query())->get();
        $salesOrders    = $this->applyCompanyContext($request, SalesOrder::query())->get();
        $requisitions   = $this->applyCompanyContext($request, PurchaseRequisition::query())->get();
        $purchaseOrders = $this->applyCompanyContext($request, PurchaseOrder::query())->get();
        $goodsReceipts  = $this->applyCompanyContext($request, GoodsReceipt::query())->get();
        $attendances    = $this->applyCompanyContext($request, Attendance::query())->whereDate('work_date', today())->get();
        $timesheets     = $this->applyCompanyContext($request, Timesheet::query())->whereDate('work_date', today())->get();
        $cmsSections    = CmsSection::query()->get();
        $blogPosts      = BlogPost::query()->get();

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
            $income = $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $month)->sum('amount');
            $expense = $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $month)->sum('amount');
            return [
                'label'   => now()->subMonths($i)->format('M'),
                'income'  => $income,
                'expense' => $expense,
                'net'     => $income - $expense,
            ];
        });
        $monthlyMax = max($monthlyChart->pluck('income')->max(), $monthlyChart->pluck('expense')->max(), $monthlyChart->pluck('net')->map(fn ($value) => abs($value))->max(), 1);

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

        $invoiceAging = $this->agingBuckets($invoices, 'amount', 'paid_amount');
        $vendorBillAging = $this->agingBuckets($vendorBills, 'amount', 'paid_amount');
        $agingMax = max(
            collect($invoiceAging)->sum('amount'),
            collect($vendorBillAging)->sum('amount'),
            1
        );

        $projectStatus = $this->statusCounts($projects, [
            'planning' => 'Planning',
            'active' => 'In Progress',
            'in_progress' => 'In Progress',
            'on_hold' => 'On Hold',
            'hold' => 'On Hold',
            'completed' => 'Completed',
            'done' => 'Completed',
        ], ['Planning', 'In Progress', 'On Hold', 'Completed']);

        $projectProfitability = $projectReports
            ->sortByDesc('profit_margin')
            ->take(5)
            ->values();
        $profitabilityMax = max($projectProfitability->max(fn ($r) => abs($r['profit_margin'])), 1);

        $salesFunnel = collect([
            ['label' => 'Inquiry', 'count' => $inquiries->count()],
            ['label' => 'Lead', 'count' => $leads->count()],
            ['label' => 'Proposal Sent', 'count' => $proposals->where('status', 'sent')->count()],
            ['label' => 'Proposal Approved', 'count' => $proposals->where('status', 'approved')->count()],
            ['label' => 'Sales Order', 'count' => $salesOrders->count()],
        ]);
        $salesFunnelMax = max($salesFunnel->max('count'), 1);

        $procurementPipeline = collect([
            ['label' => 'Requisition', 'count' => $requisitions->whereNotIn('status', ['cancelled', 'rejected'])->count()],
            ['label' => 'Purchase Order', 'count' => $purchaseOrders->whereNotIn('status', ['cancelled', 'closed'])->count()],
            ['label' => 'Goods Receipt', 'count' => $goodsReceipts->whereNotIn('status', ['cancelled'])->count()],
            ['label' => 'Vendor Bill', 'count' => $vendorBills->whereNotIn('status', ['paid', 'void'])->count()],
        ]);
        $procurementMax = max($procurementPipeline->max('count'), 1);

        $attendanceToday = $this->statusCounts($attendances, [
            'present' => 'Present',
            'late' => 'Late',
            'leave' => 'Leave',
            'absent' => 'Absent',
        ], ['Present', 'Late', 'Leave', 'Absent']);
        $attendanceTotal = max(collect($attendanceToday)->sum('count'), 1);

        $pendingByCategory = collect([
            ['label' => 'Finance Approval', 'count' => $reimbursements->where('status', 'pending')->count() + $vendorBills->whereIn('status', ['draft', 'unpaid'])->count()],
            ['label' => 'Procurement Approval', 'count' => $requisitions->whereIn('status', ['draft', 'pending'])->count()],
            ['label' => 'Sales Proposal', 'count' => $proposals->where('status', 'sent')->count()],
            ['label' => 'Project Task', 'count' => $tasks->whereNotIn('status', ['done', 'completed'])->count()],
            ['label' => 'Timesheet', 'count' => max($employees->count() - $timesheets->where('status', 'submitted')->unique('employee_id')->count(), 0)],
        ])->sortByDesc('count')->values();
        $pendingMax = max($pendingByCategory->max('count'), 1);

        $taskStatus = $this->statusCounts($tasks, [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
            'completed' => 'Done',
        ], ['To Do', 'In Progress', 'Review', 'Done']);
        $taskOverdue = $tasks
            ->whereNotIn('status', ['done', 'completed'])
            ->filter(fn ($task) => $task->due_date && $task->due_date < today()->toDateString())
            ->count();

        $actionItems = collect()
            ->merge($vendorBills->whereNotIn('status', ['paid', 'void'])->map(fn ($bill) => [
                'priority' => $bill->due_date && $bill->due_date < today()->toDateString() ? 'Critical' : 'High',
                'item' => 'Bayar vendor bill ' . ($bill->bill_number ?: $bill->vendor_name),
                'module' => 'Finance',
                'pic' => 'Finance',
                'due' => $bill->due_date ?: '-',
                'status' => $bill->status,
            ]))
            ->merge($tasks->whereNotIn('status', ['done', 'completed'])->map(fn ($task) => [
                'priority' => $task->due_date && $task->due_date < today()->toDateString() ? 'Critical' : ucfirst($task->priority ?: 'Normal'),
                'item' => $task->title,
                'module' => 'Task',
                'pic' => $task->assignee?->name ?: '-',
                'due' => $task->due_date ?: '-',
                'status' => $task->status,
            ]))
            ->sortByDesc(fn ($item) => ['Critical' => 3, 'High' => 2, 'Medium' => 1, 'Normal' => 0][$item['priority']] ?? 0)
            ->take(6)
            ->values();

        $cmsStatus = collect([
            ['label' => 'Draft', 'count' => $blogPosts->where('status', 'draft')->count()],
            ['label' => 'Published', 'count' => $blogPosts->where('status', 'published')->count()],
            ['label' => 'Active Sections', 'count' => $cmsSections->where('is_visible', true)->count()],
            ['label' => 'Hidden Sections', 'count' => $cmsSections->where('is_visible', false)->count()],
        ]);
        $cmsMax = max($cmsStatus->max('count'), 1);

        $publicationTrend = collect(range(5, 0))->map(function ($i) use ($blogPosts) {
            $month = now()->subMonths($i)->format('Y-m');
            return [
                'label' => now()->subMonths($i)->format('M'),
                'count' => $blogPosts
                    ->where('status', 'published')
                    ->filter(fn ($post) => $post->published_at && $post->published_at->format('Y-m') === $month)
                    ->count(),
            ];
        });
        $publicationMax = max($publicationTrend->max('count'), 1);

        $cmsFreshness = collect([
            ['label' => '0-7 hari', 'count' => $cmsSections->filter(fn ($s) => $s->updated_at && $s->updated_at->gte(now()->subDays(7)))->count()],
            ['label' => '8-30 hari', 'count' => $cmsSections->filter(fn ($s) => $s->updated_at && $s->updated_at->lt(now()->subDays(7)) && $s->updated_at->gte(now()->subDays(30)))->count()],
            ['label' => '31-90 hari', 'count' => $cmsSections->filter(fn ($s) => $s->updated_at && $s->updated_at->lt(now()->subDays(30)) && $s->updated_at->gte(now()->subDays(90)))->count()],
            ['label' => '>90 hari', 'count' => $cmsSections->filter(fn ($s) => !$s->updated_at || $s->updated_at->lt(now()->subDays(90)))->count()],
        ]);
        $lastContentUpdate = collect([$cmsSections->max('updated_at'), $blogPosts->max('updated_at')])->filter()->max();

        $executiveSummary = [
            'income' => $summary['income'],
            'expense' => $summary['expense'],
            'net_cashflow' => $summary['balance'],
            'active_projects' => $projects->whereIn('status', ['active', 'in_progress'])->count(),
            'pending_approvals' => $pendingByCategory->sum('count'),
        ];

        $notifications = collect([
            ['type' => 'Reimbursement pending',  'count' => $reimbursements->where('status', 'pending')->count(), 'message' => 'Menunggu approval.', 'danger' => false],
            ['type' => 'Invoice jatuh tempo',    'count' => $invoices->whereNotIn('status', ['paid', 'void'])->filter(fn ($i) => $i->due_date && $i->due_date <= now()->addDays(7)->toDateString())->count(), 'message' => 'Jatuh tempo 7 hari.', 'danger' => true],
            ['type' => 'Salary belum dibayar',   'count' => $salaries->where('status', 'approved')->count(), 'message' => 'Menunggu pembayaran.', 'danger' => false],
            ['type' => 'Proposal menunggu',      'count' => $proposals->where('status', 'sent')->count(), 'message' => 'Menunggu approval client.', 'danger' => false],
        ])->filter(fn ($item) => $item['count'] > 0);

        if ($this->isApi()) {
            return $this->respond(compact(
                'summary', 'filterCounts', 'dashboard',
                'monthlyChart', 'expenseBreakdown', 'projectChart', 'notifications',
                'executiveSummary', 'invoiceAging', 'vendorBillAging', 'projectStatus',
                'projectProfitability', 'salesFunnel', 'procurementPipeline', 'attendanceToday',
                'pendingByCategory', 'taskStatus', 'taskOverdue', 'actionItems',
                'cmsStatus', 'publicationTrend', 'cmsFreshness', 'lastContentUpdate'
            ));
        }

        return view('erp.dashboard.index', compact(
            'summary', 'filterCounts', 'dashboard',
            'monthlyChart', 'monthlyMax', 'expenseBreakdown',
            'projectChart', 'projectMax', 'notifications',
            'executiveSummary', 'invoiceAging', 'vendorBillAging', 'agingMax',
            'projectStatus', 'projectProfitability', 'profitabilityMax',
            'salesFunnel', 'salesFunnelMax', 'procurementPipeline', 'procurementMax',
            'attendanceToday', 'attendanceTotal', 'pendingByCategory', 'pendingMax',
            'taskStatus', 'taskOverdue', 'actionItems', 'cmsStatus', 'cmsMax',
            'publicationTrend', 'publicationMax', 'cmsFreshness', 'lastContentUpdate'
        ));
    }

    private function agingBuckets(Collection $items, string $amountField, string $paidField): array
    {
        $buckets = [
            'current' => ['label' => 'Belum jatuh tempo', 'amount' => 0],
            '1_30' => ['label' => '1-30 hari', 'amount' => 0],
            '31_60' => ['label' => '31-60 hari', 'amount' => 0],
            '61_90' => ['label' => '61-90 hari', 'amount' => 0],
            '90_plus' => ['label' => '>90 hari', 'amount' => 0],
        ];

        foreach ($items as $item) {
            if (in_array($item->status, ['paid', 'void'], true)) {
                continue;
            }

            $outstanding = max((float) $item->{$amountField} - (float) $item->{$paidField}, 0);
            if ($outstanding <= 0) {
                continue;
            }

            if (!$item->due_date || $item->due_date >= today()->toDateString()) {
                $buckets['current']['amount'] += $outstanding;
                continue;
            }

            $days = today()->diffInDays($item->due_date);
            $key = match (true) {
                $days <= 30 => '1_30',
                $days <= 60 => '31_60',
                $days <= 90 => '61_90',
                default => '90_plus',
            };
            $buckets[$key]['amount'] += $outstanding;
        }

        return array_values($buckets);
    }

    private function statusCounts(Collection $items, array $map, array $labels): array
    {
        $counts = collect($labels)->mapWithKeys(fn ($label) => [$label => 0])->all();

        foreach ($items as $item) {
            $label = $map[$item->status] ?? ucfirst(str_replace('_', ' ', (string) $item->status));
            if (!array_key_exists($label, $counts)) {
                $counts[$label] = 0;
            }
            $counts[$label]++;
        }

        return collect($counts)
            ->map(fn ($count, $label) => ['label' => $label, 'count' => $count])
            ->values()
            ->all();
    }
}
