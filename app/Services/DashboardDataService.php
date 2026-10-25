<?php

namespace App\Services;

use App\Http\Traits\LoadsErpData;
use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\BankReconciliationItem;
use App\Models\BlogPost;
use App\Models\Budget;
use App\Models\Cashflow;
use App\Models\Client;
use App\Models\ClientContract;
use App\Models\CmsSection;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\JobPosition;
use App\Models\JournalEntry;
use App\Models\LeaveRequest;
use App\Models\PerformanceReview;
use App\Models\ProcurementContract;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\PurchaseMatch;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\RecurringBilling;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\SalesCommission;
use App\Models\SalesInquiry;
use App\Models\SalesLead;
use App\Models\SalesOrder;
use App\Models\SalesTarget;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardDataService
{
    use LoadsErpData;

    /**
     * Resolve date range filter and previous comparison period.
     */
    public function resolveDateRange(Request $request): array
    {
        $period = $request->input('period', 'this_month');
        $now = now();

        switch ($period) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end   = $now->copy()->endOfDay();
                $prevStart = $start->copy()->subDay();
                $prevEnd   = $end->copy()->subDay();
                $label = 'Hari Ini (' . $start->isoFormat('D MMM Y') . ')';
                break;

            case '7_days':
                $start = $now->copy()->subDays(6)->startOfDay();
                $end   = $now->copy()->endOfDay();
                $prevStart = $start->copy()->subDays(7);
                $prevEnd   = $start->copy()->subSecond();
                $label = '7 Hari Terakhir';
                break;

            case '30_days':
                $start = $now->copy()->subDays(29)->startOfDay();
                $end   = $now->copy()->endOfDay();
                $prevStart = $start->copy()->subDays(30);
                $prevEnd   = $start->copy()->subSecond();
                $label = '30 Hari Terakhir';
                break;

            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end   = $now->copy()->subMonth()->endOfMonth();
                $prevStart = $start->copy()->subMonth()->startOfMonth();
                $prevEnd   = $start->copy()->subMonth()->endOfMonth();
                $label = 'Bulan Lalu (' . $start->isoFormat('MMMM Y') . ')';
                break;

            case 'this_quarter':
                $start = $now->copy()->startOfQuarter();
                $end   = $now->copy()->endOfQuarter();
                $prevStart = $start->copy()->subQuarter()->startOfQuarter();
                $prevEnd   = $start->copy()->subQuarter()->endOfQuarter();
                $label = 'Kuartal Ini (Q' . $start->quarter . ' ' . $start->year . ')';
                break;

            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                $prevStart = $start->copy()->subYear()->startOfYear();
                $prevEnd   = $start->copy()->subYear()->endOfYear();
                $label = 'Tahun Ini (' . $start->year . ')';
                break;

            case 'custom':
                $s = $request->input('start_date');
                $e = $request->input('end_date');
                $start = $s ? Carbon::parse($s)->startOfDay() : $now->copy()->startOfMonth();
                $end   = $e ? Carbon::parse($e)->endOfDay() : $now->copy()->endOfDay();
                $diffDays = max(1, $start->diffInDays($end));
                $prevStart = $start->copy()->subDays($diffDays);
                $prevEnd   = $start->copy()->subSecond();
                $label = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
                break;

            case 'this_month':
            default:
                $period = 'this_month';
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                $prevStart = $start->copy()->subMonth()->startOfMonth();
                $prevEnd   = $start->copy()->subMonth()->endOfMonth();
                $label = 'Bulan Ini (' . $start->isoFormat('MMMM Y') . ')';
                break;
        }

        return [
            'period'    => $period,
            'start'     => $start,
            'end'       => $end,
            'prevStart' => $prevStart,
            'prevEnd'   => $prevEnd,
            'label'     => $label,
            'startStr'  => $start->toDateString(),
            'endStr'    => $end->toDateString(),
        ];
    }

    /**
     * Compute trend percentage and direction
     */
    public function calcTrend(float $current, float $previous, bool $higherIsBetter = true): array
    {
        if ($previous == 0) {
            $percent = $current > 0 ? 100 : 0;
        } else {
            $percent = round((($current - $previous) / abs($previous)) * 100, 1);
        }

        $direction = $percent > 0 ? 'up' : ($percent < 0 ? 'down' : 'neutral');
        $isPositive = $higherIsBetter ? ($percent >= 0) : ($percent <= 0);

        return [
            'value'      => abs($percent),
            'raw'        => $percent,
            'direction'  => $direction,
            'is_positive'=> $isPositive,
            'label'      => ($percent > 0 ? '+' : '') . $percent . '% vs lalu',
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. DASHBOARD UTAMA (COMMAND CENTER)
    // ─────────────────────────────────────────────────────────────────────────
    public function getMainDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $role = $user?->role ?? 'admin';
        $canFinance = $role === 'admin' || in_array($role, ['finance', 'management'], true);
        $canHr = $role === 'admin' || in_array($role, ['hr', 'management'], true);

        // Core Models
        $cashflows = $this->applyCompanyContext($request, Cashflow::with('project:id,code'))->get();
        $projects  = $this->applyCompanyContext($request, Project::with(['cashflows', 'invoices']))->latest()->get();
        $invoices  = $this->applyCompanyContext($request, Invoice::query())->get();
        $bills     = $this->applyCompanyContext($request, VendorBill::query())->get();
        $tasks     = $this->applyCompanyContext($request, Task::with('assignee:id,name'))->get();
        $reimburse = $this->applyCompanyContext($request, Reimbursement::query())->get();
        $salaries  = $canHr ? $this->applyCompanyContext($request, Salary::query())->get() : collect();
        $employees = $this->applyCompanyContext($request, Employee::query())->get();
        $inquiries = $this->applyCompanyContext($request, SalesInquiry::query())->get();
        $leads     = $this->applyCompanyContext($request, SalesLead::query())->get();
        $proposals = $this->applyCompanyContext($request, Proposal::query())->get();
        $orders    = $this->applyCompanyContext($request, SalesOrder::query())->get();
        $requisitions = $this->applyCompanyContext($request, PurchaseRequisition::query())->get();
        $pos       = $this->applyCompanyContext($request, PurchaseOrder::query())->get();
        $receipts  = $this->applyCompanyContext($request, GoodsReceipt::query())->get();
        $attendances = $this->applyCompanyContext($request, Attendance::query())->whereDate('work_date', today())->get();
        $cmsSections = CmsSection::query()->get();
        $blogPosts = BlogPost::query()->get();

        // Financial metrics
        $curIncome = (float) $cashflows->where('type', 'income')
            ->filter(fn ($c) => $c->transaction_date >= $range['startStr'] && $c->transaction_date <= $range['endStr'])
            ->sum('amount');
        $prevIncome = (float) $cashflows->where('type', 'income')
            ->filter(fn ($c) => $c->transaction_date >= $range['prevStart']->toDateString() && $c->transaction_date <= $range['prevEnd']->toDateString())
            ->sum('amount');

        $curExpense = (float) $cashflows->where('type', 'expense')
            ->filter(fn ($c) => $c->transaction_date >= $range['startStr'] && $c->transaction_date <= $range['endStr'])
            ->sum('amount');
        $prevExpense = (float) $cashflows->where('type', 'expense')
            ->filter(fn ($c) => $c->transaction_date >= $range['prevStart']->toDateString() && $c->transaction_date <= $range['prevEnd']->toDateString())
            ->sum('amount');

        $netCashflow = $curIncome - $curExpense;
        $prevNet = $prevIncome - $prevExpense;

        $unpaidInvoices = $invoices->whereNotIn('status', ['paid', 'void']);
        $outstandingInv = (float) $unpaidInvoices->sum(fn ($i) => max(0, (float) $i->amount - (float) $i->paid_amount));

        $activeProjects = $projects->whereIn('status', ['active', 'in_progress'])->count();
        $pendingApprovals = $reimburse->where('status', 'pending')->count()
            + $requisitions->whereIn('status', ['draft', 'pending'])->count()
            + $proposals->where('status', 'sent')->count();

        $monthPayroll = $canHr ? (float) $salaries->where('period', now()->format('Y-m'))->sum('net_salary') : 0;
        $pendingReimbursements = (float) $reimburse->where('status', 'pending')->sum('amount');

        // Sparkline 6 points for cards
        $monthlyPoints = collect(range(5, 0))->map(function ($i) use ($cashflows) {
            $m = now()->subMonths($i)->format('Y-m');
            $inc = (float) $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            $exp = (float) $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            return [
                'month'   => now()->subMonths($i)->format('M'),
                'income'  => $inc,
                'expense' => $exp,
                'net'     => $inc - $exp,
            ];
        });

        // 8 KPI Cards
        $kpis = [
            'income' => [
                'label'       => 'Total Income',
                'value'       => $curIncome,
                'trend'       => $this->calcTrend($curIncome, $prevIncome, true),
                'sparkline'   => $monthlyPoints->pluck('income')->all(),
                'color'       => '#16A34A',
                'route'       => route('cashflows.index-page'),
            ],
            'expense' => [
                'label'       => 'Total Expense',
                'value'       => $curExpense,
                'trend'       => $this->calcTrend($curExpense, $prevExpense, false),
                'sparkline'   => $monthlyPoints->pluck('expense')->all(),
                'color'       => '#DC2626',
                'route'       => route('cashflows.index-page'),
            ],
            'net_cashflow' => [
                'label'       => 'Net Cashflow',
                'value'       => $netCashflow,
                'trend'       => $this->calcTrend($netCashflow, $prevNet, true),
                'sparkline'   => $monthlyPoints->pluck('net')->all(),
                'color'       => '#2563EB',
                'route'       => route('reports.cash-flow'),
            ],
            'outstanding_invoices' => [
                'label'       => 'Outstanding Invoices',
                'value'       => $outstandingInv,
                'count'       => $unpaidInvoices->count(),
                'trend'       => ['label' => $unpaidInvoices->count() . ' invoice aktif', 'direction' => 'neutral', 'is_positive' => true],
                'color'       => '#EA580C',
                'route'       => route('finance.index'),
            ],
            'active_projects' => [
                'label'       => 'Active Projects',
                'value'       => $activeProjects,
                'count'       => $projects->count(),
                'trend'       => ['label' => $projects->count() . ' total project', 'direction' => 'up', 'is_positive' => true],
                'color'       => '#7C3AED',
                'route'       => route('projects.index'),
            ],
            'pending_approvals' => [
                'label'       => 'Pending Approvals',
                'value'       => $pendingApprovals,
                'trend'       => ['label' => 'Reimburse, PR, proposal', 'direction' => 'neutral', 'is_positive' => false],
                'color'       => '#D97706',
                'route'       => route('approvals.index'),
            ],
            'payroll_month' => [
                'label'       => 'Payroll Bulan Ini',
                'value'       => $monthPayroll,
                'trend'       => ['label' => $employees->count() . ' karyawan aktif', 'direction' => 'neutral', 'is_positive' => true],
                'color'       => '#0891B2',
                'route'       => route('salaries.index-page'),
                'is_hidden'   => !$canHr,
            ],
            'pending_reimbursements' => [
                'label'       => 'Pending Reimbursements',
                'value'       => $pendingReimbursements,
                'count'       => $reimburse->where('status', 'pending')->count(),
                'trend'       => ['label' => $reimburse->where('status', 'pending')->count() . ' klaim diajukan', 'direction' => 'neutral', 'is_positive' => false],
                'color'       => '#4F46E5',
                'route'       => route('reimbursements.index-page'),
            ],
        ];

        // Section 2: Combo Chart (Income vs Expense vs Net Cashflow)
        $comboMonths = collect(range(5, 0))->map(function ($i) use ($cashflows) {
            $m = now()->subMonths($i)->format('Y-m');
            $inc = (float) $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            $exp = (float) $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            return [
                'label'   => now()->subMonths($i)->format('M Y'),
                'income'  => $inc,
                'expense' => $exp,
                'net'     => $inc - $exp,
            ];
        });

        // Section 2: Expense Breakdown Donut
        $totalExp = $cashflows->where('type', 'expense')->sum('amount');
        $expenseCategories = $cashflows->where('type', 'expense')
            ->groupBy(fn ($c) => $c->cost_type ?: 'Operasional')
            ->map(fn ($group, $name) => [
                'name'    => ucfirst($name),
                'amount'  => (float) $group->sum('amount'),
                'percent' => $totalExp > 0 ? round(($group->sum('amount') / $totalExp) * 100, 1) : 0,
            ])
            ->sortByDesc('amount')
            ->values();

        // If categories > 5, lump into Lainnya
        if ($expenseCategories->count() > 5) {
            $top = $expenseCategories->take(4);
            $others = $expenseCategories->slice(4);
            $expenseCategories = $top->push([
                'name'    => 'Lainnya',
                'amount'  => (float) $others->sum('amount'),
                'percent' => round($others->sum('percent'), 1),
            ]);
        }

        // Section 3: Business Operations
        // Project Status Donut
        $projectStatusCounts = [
            'Planning'    => $projects->whereIn('status', ['planning', 'draft'])->count(),
            'In Progress' => $projects->whereIn('status', ['active', 'in_progress'])->count(),
            'On Hold'     => $projects->whereIn('status', ['on_hold', 'hold'])->count(),
            'Completed'   => $projects->whereIn('status', ['completed', 'done'])->count(),
        ];

        // Sales Funnel Horizontal Bar
        $salesFunnel = [
            ['stage' => 'Inquiry',           'count' => $inquiries->count()],
            ['stage' => 'Lead',              'count' => $leads->count()],
            ['stage' => 'Proposal Sent',     'count' => $proposals->where('status', 'sent')->count()],
            ['stage' => 'Proposal Approved', 'count' => $proposals->where('status', 'approved')->count()],
            ['stage' => 'Sales Order',       'count' => $orders->count()],
        ];

        // Procurement Pipeline
        $procurementPipeline = [
            ['stage' => 'Requisition',   'count' => $requisitions->whereNotIn('status', ['cancelled', 'rejected'])->count()],
            ['stage' => 'Purchase Order','count' => $pos->whereNotIn('status', ['cancelled', 'closed'])->count()],
            ['stage' => 'Goods Receipt', 'count' => $receipts->whereNotIn('status', ['cancelled'])->count()],
            ['stage' => 'Vendor Bill',   'count' => $bills->whereNotIn('status', ['paid', 'void'])->count()],
        ];

        // Section 4: Operational Monitoring
        // Attendance Today Donut
        $attendanceToday = [
            'Present' => $attendances->where('status', 'present')->count(),
            'Late'    => $attendances->where('status', 'late')->count(),
            'Leave'   => $attendances->where('status', 'leave')->count(),
            'Absent'  => $attendances->where('status', 'absent')->count(),
        ];

        // Task Overview Stacked Bar
        $taskCounts = [
            'To Do'       => $tasks->where('status', 'todo')->count(),
            'In Progress' => $tasks->where('status', 'in_progress')->count(),
            'Review'      => $tasks->where('status', 'review')->count(),
            'Done'        => $tasks->whereIn('status', ['done', 'completed'])->count(),
        ];
        $overdueTasksCount = $tasks->whereNotIn('status', ['done', 'completed'])
            ->filter(fn ($t) => $t->due_date && $t->due_date < today()->toDateString())
            ->count();

        // Section 5: Action Required Table
        $actionItems = collect();

        // Overdue Invoices
        foreach ($unpaidInvoices->filter(fn ($i) => $i->due_date && $i->due_date < today()->toDateString())->take(3) as $inv) {
            $actionItems->push([
                'type'        => 'Invoice Customer',
                'reference'   => $inv->number,
                'description' => 'Tagihan belum dibayar: ' . ($inv->client?->name ?: 'Client'),
                'priority'    => 'Critical',
                'due_date'    => $inv->due_date,
                'status'      => 'Overdue',
                'action_url'  => route('finance.index'),
                'action_text' => 'Lihat Tagihan',
            ]);
        }

        // Unpaid Vendor Bills
        foreach ($bills->whereNotIn('status', ['paid', 'void'])->take(3) as $bill) {
            $isOverdue = $bill->due_date && $bill->due_date < today()->toDateString();
            $actionItems->push([
                'type'        => 'Vendor Bill',
                'reference'   => $bill->bill_number ?: ('BILL-' . $bill->id),
                'description' => 'Tagihan vendor: ' . $bill->vendor_name,
                'priority'    => $isOverdue ? 'Critical' : 'High',
                'due_date'    => $bill->due_date ?: '-',
                'status'      => $bill->status,
                'action_url'  => route('vendor-bills.index'),
                'action_text' => 'Bayar Bill',
            ]);
        }

        // Pending Reimbursements
        foreach ($reimburse->where('status', 'pending')->take(3) as $r) {
            $actionItems->push([
                'type'        => 'Reimbursement',
                'reference'   => 'REIM-' . $r->id,
                'description' => 'Klaim diajukan: ' . $r->description,
                'priority'    => 'Medium',
                'due_date'    => $r->expense_date ?: '-',
                'status'      => 'Pending',
                'action_url'  => route('reimbursements.index-page'),
                'action_text' => 'Review',
            ]);
        }

        // Pending Purchase Requisitions
        foreach ($requisitions->whereIn('status', ['draft', 'pending'])->take(2) as $pr) {
            $actionItems->push([
                'type'        => 'Purchase Req',
                'reference'   => $pr->number,
                'description' => 'Pengadaan diajukan: ' . $pr->purpose,
                'priority'    => 'Medium',
                'due_date'    => $pr->required_date ?: '-',
                'status'      => $pr->status,
                'action_url'  => route('purchase-requisitions.index'),
                'action_text' => 'Approve',
            ]);
        }

        // Proposals Pending
        foreach ($proposals->where('status', 'sent')->take(2) as $prop) {
            $actionItems->push([
                'type'        => 'Sales Proposal',
                'reference'   => $prop->number,
                'description' => 'Menunggu respon client: ' . ($prop->project?->name ?: $prop->title),
                'priority'    => 'High',
                'due_date'    => $prop->valid_until ?: '-',
                'status'      => 'Sent',
                'action_url'  => route('sales.index'),
                'action_text' => 'Follow up',
            ]);
        }

        // Overdue Tasks
        foreach ($tasks->whereNotIn('status', ['done', 'completed'])->filter(fn ($t) => $t->due_date && $t->due_date < today()->toDateString())->take(2) as $task) {
            $actionItems->push([
                'type'        => 'Task Overdue',
                'reference'   => 'TASK-' . $task->id,
                'description' => $task->title . ' (PIC: ' . ($task->assignee?->name ?: '-') . ')',
                'priority'    => 'Critical',
                'due_date'    => $task->due_date,
                'status'      => 'Overdue',
                'action_url'  => route('approvals.index'),
                'action_text' => 'Cek Task',
            ]);
        }

        // Section 6: CMS Summary & Activity Timeline
        $cmsSummary = [
            'published_posts' => $blogPosts->where('status', 'published')->count(),
            'draft_posts'     => $blogPosts->where('status', 'draft')->count(),
            'active_sections' => $cmsSections->where('is_visible', true)->count(),
            'hidden_sections' => $cmsSections->where('is_visible', false)->count(),
            'trend'           => collect(range(5, 0))->map(fn ($i) => [
                'month' => now()->subMonths($i)->format('M'),
                'count' => $blogPosts->where('status', 'published')->filter(fn ($p) => $p->published_at && $p->published_at->format('Y-m') === now()->subMonths($i)->format('Y-m'))->count(),
            ])->pluck('count')->all(),
        ];

        // Recent Activity Timeline
        $recentActivities = collect();
        foreach ($invoices->sortByDesc('created_at')->take(3) as $inv) {
            $recentActivities->push([
                'module'    => 'Finance',
                'badge'     => 'Invoice',
                'color'     => '#2563EB',
                'title'     => 'Invoice diterbitkan ' . $inv->number,
                'desc'      => 'Nominal Rp ' . number_format((float) $inv->amount, 0, ',', '.') . ' untuk ' . ($inv->client?->name ?: 'Client'),
                'time'      => $inv->created_at ? $inv->created_at->diffForHumans() : '-',
            ]);
        }
        foreach ($proposals->sortByDesc('created_at')->take(2) as $prop) {
            $recentActivities->push([
                'module'    => 'Sales',
                'badge'     => 'Proposal',
                'color'     => '#4F46E5',
                'title'     => 'Proposal dibuat ' . $prop->number,
                'desc'      => 'Estimasi nilai Rp ' . number_format((float) $prop->amount, 0, ',', '.'),
                'time'      => $prop->created_at ? $prop->created_at->diffForHumans() : '-',
            ]);
        }
        foreach ($projects->sortByDesc('created_at')->take(2) as $proj) {
            $recentActivities->push([
                'module'    => 'Project',
                'badge'     => 'Project',
                'color'     => '#7C3AED',
                'title'     => 'Project baru didaftarkan: ' . $proj->name,
                'desc'      => 'Status: ' . ucfirst($proj->status),
                'time'      => $proj->created_at ? $proj->created_at->diffForHumans() : '-',
            ]);
        }
        $recentActivities = $recentActivities->sortByDesc('time')->values()->take(6);

        return [
            'range'                => $range,
            'kpis'                 => $kpis,
            'comboMonths'          => $comboMonths,
            'expenseCategories'    => $expenseCategories,
            'projectStatusCounts'  => $projectStatusCounts,
            'salesFunnel'          => $salesFunnel,
            'procurementPipeline'  => $procurementPipeline,
            'attendanceToday'      => $attendanceToday,
            'taskCounts'           => $taskCounts,
            'overdueTasksCount'    => $overdueTasksCount,
            'actionItems'          => $actionItems->values(),
            'cmsSummary'           => $cmsSummary,
            'recentActivities'     => $recentActivities,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. EXECUTIVE OVERVIEW DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getExecutiveDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $cashflows = $this->applyCompanyContext($request, Cashflow::query())->get();
        $projects  = $this->applyCompanyContext($request, Project::with(['cashflows', 'invoices', 'salaries', 'reimbursements']))->get();
        $invoices  = $this->applyCompanyContext($request, Invoice::with('project'))->get();
        $bills     = $this->applyCompanyContext($request, VendorBill::query())->get();
        $budgets   = $this->applyCompanyContext($request, Budget::query())->get();
        $contracts = $this->applyCompanyContext($request, ClientContract::with('client'))->get();
        $bankAccs  = $this->applyCompanyContext($request, BankAccount::query())->get();
        $clients   = $this->applyCompanyContext($request, Client::query())->get();

        $totalRevenue = (float) $cashflows->where('type', 'income')->sum('amount');
        $totalExpense = (float) $cashflows->where('type', 'expense')->sum('amount');
        $grossProfit  = $totalRevenue - $totalExpense;
        $profitMargin = $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0;

        $curRev = (float) $cashflows->where('type', 'income')->filter(fn ($c) => $c->transaction_date >= $range['startStr'] && $c->transaction_date <= $range['endStr'])->sum('amount');
        $prevRev = (float) $cashflows->where('type', 'income')->filter(fn ($c) => $c->transaction_date >= $range['prevStart']->toDateString() && $c->transaction_date <= $range['prevEnd']->toDateString())->sum('amount');
        $revGrowth = $this->calcTrend($curRev, $prevRev, true);

        $cashPosition = (float) $bankAccs->sum('opening_balance') + $grossProfit;
        $ar = (float) $invoices->whereNotIn('status', ['paid', 'void'])->sum(fn ($i) => max(0, (float) $i->amount - (float) $i->paid_amount));
        $ap = (float) $bills->whereNotIn('status', ['paid', 'void'])->sum(fn ($b) => max(0, (float) $b->amount - (float) $b->paid_amount));

        $totalBudget = (float) $budgets->sum('budget_amount');
        $budgetUtil = $totalBudget > 0 ? round(($totalExpense / $totalBudget) * 100, 1) : 0;
        $activeContracts = $contracts->where('status', 'active')->count();

        // Project Profitability average
        $projectMargins = $projects->map(function ($p) {
            $inc = (float) $p->cashflows->where('type', 'income')->sum('amount');
            $exp = (float) $p->cashflows->where('type', 'expense')->sum('amount');
            $contract = (float) $p->contract_value;
            $margin = $contract > 0 ? round((($inc - $exp) / $contract) * 100, 1) : ($inc > 0 ? round((($inc - $exp) / $inc) * 100, 1) : 0);
            return [
                'project' => $p,
                'income'  => $inc,
                'expense' => $exp,
                'profit'  => $inc - $exp,
                'margin'  => $margin,
            ];
        });
        $avgProjectMargin = $projectMargins->count() > 0 ? round($projectMargins->avg('margin'), 1) : 0;

        // KPI cards for Executive
        $kpis = [
            'revenue'         => ['label' => 'Total Revenue', 'value' => $totalRevenue, 'trend' => $revGrowth, 'color' => '#16A34A'],
            'expenses'        => ['label' => 'Total Expenses', 'value' => $totalExpense, 'trend' => ['label' => 'Akumulasi operasional', 'direction' => 'neutral', 'is_positive' => true], 'color' => '#DC2626'],
            'gross_profit'    => ['label' => 'Gross Profit', 'value' => $grossProfit, 'trend' => ['label' => ($grossProfit >= 0 ? 'Surplus' : 'Defisit'), 'direction' => ($grossProfit >= 0 ? 'up' : 'down'), 'is_positive' => ($grossProfit >= 0)], 'color' => '#2563EB'],
            'profit_margin'   => ['label' => 'Profit Margin', 'value' => $profitMargin . '%', 'trend' => ['label' => 'Target: 25.0%', 'direction' => $profitMargin >= 25 ? 'up' : 'down', 'is_positive' => $profitMargin >= 25], 'color' => '#7C3AED'],
            'cash_position'   => ['label' => 'Cash Position', 'value' => $cashPosition, 'trend' => ['label' => 'Likuiditas kas & bank', 'direction' => 'neutral', 'is_positive' => true], 'color' => '#0891B2'],
            'receivables'     => ['label' => 'Outstanding AR', 'value' => $ar, 'trend' => ['label' => $invoices->whereNotIn('status', ['paid', 'void'])->count() . ' invoice belum lunas', 'direction' => 'neutral', 'is_positive' => false], 'color' => '#EA580C'],
            'payables'        => ['label' => 'Outstanding AP', 'value' => $ap, 'trend' => ['label' => $bills->whereNotIn('status', ['paid', 'void'])->count() . ' tagihan vendor', 'direction' => 'neutral', 'is_positive' => false], 'color' => '#D97706'],
            'budget_util'     => ['label' => 'Budget Utilization', 'value' => $budgetUtil . '%', 'trend' => ['label' => 'Alokasi anggaran', 'direction' => 'neutral', 'is_positive' => $budgetUtil <= 100], 'color' => '#4F46E5'],
            'contracts'       => ['label' => 'Active Contracts', 'value' => $activeContracts, 'trend' => ['label' => $contracts->count() . ' total kontrak klien', 'direction' => 'up', 'is_positive' => true], 'color' => '#0F766E'],
            'project_margin'  => ['label' => 'Avg Project Margin', 'value' => $avgProjectMargin . '%', 'trend' => ['label' => 'Rata-rata profitabilitas', 'direction' => 'neutral', 'is_positive' => true], 'color' => '#7C3AED'],
            'revenue_growth'  => ['label' => 'Revenue Growth', 'value' => $revGrowth['raw'] . '%', 'trend' => $revGrowth, 'color' => '#16A34A'],
            'net_profit'      => ['label' => 'Net Profit', 'value' => $grossProfit, 'trend' => ['label' => 'Setelah beban', 'direction' => 'neutral', 'is_positive' => true], 'color' => '#16A34A'],
        ];

        // 1. Revenue & Profit Trend (Combo Chart)
        $comboTrend = collect(range(5, 0))->map(function ($i) use ($cashflows) {
            $m = now()->subMonths($i)->format('Y-m');
            $rev = (float) $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            $exp = (float) $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            return [
                'month'   => now()->subMonths($i)->format('M Y'),
                'revenue' => $rev,
                'expense' => $exp,
                'profit'  => $rev - $exp,
            ];
        });

        // 2. Profit Margin Trend (Line Chart)
        $marginTrend = $comboTrend->map(function ($item) {
            $margin = $item['revenue'] > 0 ? round(($item['profit'] / $item['revenue']) * 100, 1) : 0;
            return [
                'month'  => $item['month'],
                'margin' => $margin,
                'target' => 25, // target benchmark
            ];
        });

        // 3. Revenue Composition by Project / Client Donut
        $topProjectsRevenue = $projects->map(fn ($p) => [
            'name'    => $p->name,
            'amount'  => (float) $p->cashflows->where('type', 'income')->sum('amount'),
        ])->filter(fn ($p) => $p['amount'] > 0)->sortByDesc('amount')->values();

        $revComposition = $topProjectsRevenue->take(5);
        if ($topProjectsRevenue->count() > 5) {
            $revComposition->push([
                'name'   => 'Lainnya',
                'amount' => (float) $topProjectsRevenue->slice(5)->sum('amount'),
            ]);
        }

        // 4. Budget vs Actual Grouped Bar
        $budgetComparison = $budgets->take(6)->map(function ($b) use ($cashflows) {
            $actual = (float) $cashflows->where('type', 'expense')->where('project_id', $b->project_id)->sum('amount');
            return [
                'category' => $b->project?->name ?: ('Budget #' . $b->id),
                'budget'   => (float) $b->budget_amount,
                'actual'   => $actual,
                'variance' => (float) $b->budget_amount - $actual,
            ];
        });

        // 5. Project Profitability Ranking (Horizontal Bar Chart)
        $profitRanking = $projectMargins->sortByDesc('profit')->take(6)->values()->map(fn ($item) => [
            'name'    => $item['project']->name,
            'profit'  => $item['profit'],
            'margin'  => $item['margin'],
        ]);

        // 6. Receivables Aging Stacked Bar
        $arAging = $this->agingBuckets($invoices, 'amount', 'paid_amount');

        // Management Alerts
        $alerts = collect();
        if ($ar > 0) {
            $alerts->push(['type' => 'warning', 'title' => 'Piutang Berjalan', 'desc' => 'Terdapat outstanding piutang sebesar Rp ' . number_format($ar, 0, ',', '.') . ' yang perlu ditagih.']);
        }
        if ($ap > 0) {
            $alerts->push(['type' => 'critical', 'title' => 'Kewajiban Pembayaran', 'desc' => 'Total hutang vendor bill sebesar Rp ' . number_format($ap, 0, ',', '.') . '.']);
        }
        $lossProjects = $projectMargins->filter(fn ($p) => $p['profit'] < 0);
        if ($lossProjects->isNotEmpty()) {
            $alerts->push(['type' => 'danger', 'title' => 'Project Margin Negatif', 'desc' => $lossProjects->count() . ' project saat ini mengalami margin defisit.']);
        }
        $expiringContracts = $contracts->filter(fn ($c) => $c->end_date && Carbon::parse($c->end_date)->lte(now()->addDays(30)) && Carbon::parse($c->end_date)->gte(now()));
        if ($expiringContracts->isNotEmpty()) {
            $alerts->push(['type' => 'info', 'title' => 'Kontrak Klien Segera Berakhir', 'desc' => $expiringContracts->count() . ' kontrak klien mendekati tanggal berakhir (< 30 hari).']);
        }

        // Tables
        // Top Clients by Revenue
        $topClients = $invoices->groupBy('client_id')->map(function ($group, $clientId) use ($clients) {
            $client = $clients->firstWhere('id', $clientId);
            $total = (float) $group->sum('amount');
            $paid  = (float) $group->sum('paid_amount');
            return [
                'client_name' => $client?->name ?: 'Client #' . $clientId,
                'invoices_count' => $group->count(),
                'total_revenue' => $total,
                'paid_revenue' => $paid,
                'outstanding'  => max(0, $total - $paid),
            ];
        })->sortByDesc('total_revenue')->take(10)->values();

        // Top Projects by Profit
        $topProjectsTable = $projectMargins->sortByDesc('profit')->take(10)->values();

        return [
            'range'             => $range,
            'kpis'              => $kpis,
            'comboTrend'        => $comboTrend,
            'marginTrend'       => $marginTrend,
            'revComposition'    => $revComposition,
            'budgetComparison'  => $budgetComparison,
            'profitRanking'     => $profitRanking,
            'arAging'           => $arAging,
            'alerts'            => $alerts,
            'topClients'        => $topClients,
            'topProjects'       => $topProjectsTable,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. SALES & CRM DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getSalesDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $inquiries  = $this->applyCompanyContext($request, SalesInquiry::with('owner'))->latest()->get();
        $leads      = $this->applyCompanyContext($request, SalesLead::with(['client', 'owner']))->latest()->get();
        $proposals  = $this->applyCompanyContext($request, Proposal::with('project'))->latest()->get();
        $orders     = $this->applyCompanyContext($request, SalesOrder::with('client'))->latest()->get();
        $targets    = $this->applyCompanyContext($request, SalesTarget::query())->get();
        $commissions= $this->applyCompanyContext($request, SalesCommission::query())->get();
        $contracts  = $this->applyCompanyContext($request, ClientContract::with('client'))->get();

        $totalInquiries = $inquiries->count();
        $totalLeads = $leads->count();
        $qualifiedLeads = $leads->whereIn('stage', ['qualified', 'proposal', 'negotiation', 'won'])->count();
        $proposalsSent = $proposals->where('status', 'sent')->count();
        $proposalsApproved = $proposals->where('status', 'approved')->count();
        $totalOrders = $orders->count();
        $pipelineValue = (float) $leads->sum('value') + (float) $proposals->where('status', 'sent')->sum('amount');

        $targetTotal = (float) $targets->sum('target_amount');
        $achievedTotal = (float) $targets->sum('achieved_amount');
        $targetAchievement = $targetTotal > 0 ? round(($achievedTotal / $targetTotal) * 100, 1) : 0;

        $conversionRate = $totalInquiries > 0 ? round(($totalOrders / $totalInquiries) * 100, 1) : ($totalLeads > 0 ? round(($totalOrders / $totalLeads) * 100, 1) : 0);
        $totalCommission = (float) $commissions->sum('amount');
        $activeContracts = $contracts->where('status', 'active')->count();

        $kpis = [
            'inquiries'     => ['label' => 'Total Inquiries', 'value' => $totalInquiries, 'color' => '#0B2345', 'trend' => ['label' => 'Permintaan masuk', 'direction' => 'up', 'is_positive' => true]],
            'leads'         => ['label' => 'Total Leads', 'value' => $totalLeads, 'color' => '#4F46E5', 'trend' => ['label' => 'Prospek terdaftar', 'direction' => 'neutral', 'is_positive' => true]],
            'qualified'     => ['label' => 'Qualified Leads', 'value' => $qualifiedLeads, 'color' => '#2563EB', 'trend' => ['label' => 'Memenuhi kriteria', 'direction' => 'up', 'is_positive' => true]],
            'prop_sent'     => ['label' => 'Proposals Sent', 'value' => $proposalsSent, 'color' => '#EA580C', 'trend' => ['label' => 'Menunggu respon', 'direction' => 'neutral', 'is_positive' => true]],
            'prop_approved' => ['label' => 'Approved Proposals', 'value' => $proposalsApproved, 'color' => '#16A34A', 'trend' => ['label' => 'Disetujui klien', 'direction' => 'up', 'is_positive' => true]],
            'orders'        => ['label' => 'Sales Orders', 'value' => $totalOrders, 'color' => '#16A34A', 'trend' => ['label' => 'Order terkonfirmasi', 'direction' => 'up', 'is_positive' => true]],
            'pipeline_val'  => ['label' => 'Pipeline Value', 'value' => $pipelineValue, 'color' => '#7C3AED', 'trend' => ['label' => 'Estimasi potensi', 'direction' => 'neutral', 'is_positive' => true]],
            'achievement'   => ['label' => 'Target Achievement', 'value' => $targetAchievement . '%', 'color' => '#16A34A', 'trend' => ['label' => 'Dari target kuartal', 'direction' => $targetAchievement >= 80 ? 'up' : 'down', 'is_positive' => $targetAchievement >= 80]],
            'conversion'    => ['label' => 'Conversion Rate', 'value' => $conversionRate . '%', 'color' => '#2563EB', 'trend' => ['label' => 'Lead ke Order', 'direction' => 'neutral', 'is_positive' => true]],
            'commission'    => ['label' => 'Sales Commission', 'value' => $totalCommission, 'color' => '#D97706', 'trend' => ['label' => 'Komisi penjualan', 'direction' => 'neutral', 'is_positive' => true]],
            'contracts'     => ['label' => 'Active Contracts', 'value' => $activeContracts, 'color' => '#0F766E', 'trend' => ['label' => 'Kontrak berjalan', 'direction' => 'neutral', 'is_positive' => true]],
        ];

        // 1. Sales Funnel
        $salesFunnel = [
            ['stage' => 'Inquiry',           'count' => $totalInquiries],
            ['stage' => 'Lead',              'count' => $totalLeads],
            ['stage' => 'Proposal Sent',     'count' => $proposalsSent],
            ['stage' => 'Proposal Approved', 'count' => $proposalsApproved],
            ['stage' => 'Sales Order',       'count' => $totalOrders],
        ];

        // 2. Monthly Sales Performance (Line Chart: Order Count & Total Amount)
        $monthlyPerformance = collect(range(5, 0))->map(function ($i) use ($orders) {
            $m = now()->subMonths($i)->format('Y-m');
            $periodOrders = $orders->filter(fn ($o) => $o->order_date && $o->order_date->format('Y-m') === $m);
            return [
                'month'  => now()->subMonths($i)->format('M Y'),
                'count'  => $periodOrders->count(),
                'amount' => (float) $periodOrders->sum('amount'),
            ];
        });

        // 3. Target vs Actual Grouped Bar
        $targetVsActual = $targets->take(5)->map(fn ($t) => [
            'period'   => $t->period ?: 'Q' . now()->quarter,
            'target'   => (float) $t->target_amount,
            'achieved' => (float) $t->achieved_amount,
        ]);

        // 4. Lead Status Donut
        $leadStatusDistribution = $leads->groupBy(fn ($l) => ucfirst(str_replace('_', ' ', $l->stage ?: 'New')))
            ->map(fn ($group, $stage) => ['label' => $stage, 'count' => $group->count()])
            ->values();

        // 5. Sales by Person Horizontal Bar
        $salesByPerson = $orders->groupBy(fn ($o) => $o->proposal?->creator?->name ?: ($o->client?->name ?: 'Sales Team'))
            ->map(fn ($group, $name) => [
                'name'   => $name,
                'amount' => (float) $group->sum('amount'),
                'count'  => $group->count(),
            ])
            ->sortByDesc('amount')->take(6)->values();

        // 6. Proposal Status Donut
        $proposalStatusDistribution = [
            'Draft'    => $proposals->where('status', 'draft')->count(),
            'Sent'     => $proposals->where('status', 'sent')->count(),
            'Approved' => $proposals->where('status', 'approved')->count(),
            'Rejected' => $proposals->where('status', 'rejected')->count(),
        ];

        // 7. Client Contribution Bar
        $clientContribution = $orders->groupBy('client_id')->map(function ($group) {
            $client = $group->first()->client;
            return [
                'client' => $client?->name ?: 'Client',
                'amount' => (float) $group->sum('amount'),
            ];
        })->sortByDesc('amount')->take(6)->values();

        // Tables
        $recentInquiries = $inquiries->take(5);
        $activeLeads = $leads->take(5);
        $pendingProposals = $proposals->where('status', 'sent')->take(5);
        $recentOrders = $orders->take(5);
        $expiringContracts = $contracts->filter(fn ($c) => $c->end_date && Carbon::parse($c->end_date)->lte(now()->addDays(60)))->take(5);

        return [
            'range'                      => $range,
            'kpis'                       => $kpis,
            'salesFunnel'                => $salesFunnel,
            'monthlyPerformance'         => $monthlyPerformance,
            'targetVsActual'             => $targetVsActual,
            'leadStatusDistribution'     => $leadStatusDistribution,
            'salesByPerson'              => $salesByPerson,
            'proposalStatusDistribution' => $proposalStatusDistribution,
            'clientContribution'         => $clientContribution,
            'recentInquiries'            => $recentInquiries,
            'activeLeads'                => $activeLeads,
            'pendingProposals'           => $pendingProposals,
            'recentOrders'               => $recentOrders,
            'expiringContracts'          => $expiringContracts,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 4. PROJECT MANAGEMENT DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getProjectsDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $projects   = $this->applyCompanyContext($request, Project::with(['cashflows', 'invoices', 'salaries', 'reimbursements']))->latest()->get();
        $tasks      = $this->applyCompanyContext($request, Task::with('assignee'))->get();
        $timesheets = $this->applyCompanyContext($request, Timesheet::with(['project', 'employee']))->get();

        $totalProjects = $projects->count();
        $activeProjects = $projects->whereIn('status', ['active', 'in_progress'])->count();
        $completedProjects = $projects->whereIn('status', ['completed', 'done'])->count();
        $onHoldProjects = $projects->whereIn('status', ['on_hold', 'hold'])->count();
        $overdueProjects = $projects->filter(fn ($p) => $p->end_date && $p->end_date < today()->toDateString() && !in_array($p->status, ['completed', 'done']))->count();

        $projectRevenue = (float) $projects->sum(fn ($p) => $p->cashflows->where('type', 'income')->sum('amount'));
        $projectExpenses = (float) $projects->sum(fn ($p) => $p->cashflows->where('type', 'expense')->sum('amount'));
        $projectProfit = $projectRevenue - $projectExpenses;

        $projectMargins = $projects->map(function ($p) {
            $inc = (float) $p->cashflows->where('type', 'income')->sum('amount');
            $exp = (float) $p->cashflows->where('type', 'expense')->sum('amount');
            $c = (float) $p->contract_value;
            $margin = $c > 0 ? round((($inc - $exp) / $c) * 100, 1) : ($inc > 0 ? round((($inc - $exp) / $inc) * 100, 1) : 0);
            return [
                'project' => $p,
                'revenue' => $inc,
                'expense' => $exp,
                'profit'  => $inc - $exp,
                'margin'  => $margin,
                'budget'  => (float) $p->budget,
            ];
        });
        $avgMargin = $projectMargins->count() > 0 ? round($projectMargins->avg('margin'), 1) : 0;

        $openTasks = $tasks->whereNotIn('status', ['done', 'completed'])->count();
        $overdueTasks = $tasks->whereNotIn('status', ['done', 'completed'])->filter(fn ($t) => $t->due_date && $t->due_date < today()->toDateString())->count();
        $loggedHours = (float) $timesheets->sum('hours');

        $kpis = [
            'total_projects'  => ['label' => 'Total Projects', 'value' => $totalProjects, 'color' => '#0B2345'],
            'active_projects' => ['label' => 'Active Projects', 'value' => $activeProjects, 'color' => '#7C3AED'],
            'completed'       => ['label' => 'Completed Projects', 'value' => $completedProjects, 'color' => '#16A34A'],
            'on_hold'         => ['label' => 'On Hold Projects', 'value' => $onHoldProjects, 'color' => '#EA580C'],
            'overdue'         => ['label' => 'Overdue Projects', 'value' => $overdueProjects, 'color' => '#DC2626'],
            'revenue'         => ['label' => 'Project Revenue', 'value' => $projectRevenue, 'color' => '#16A34A'],
            'expenses'        => ['label' => 'Project Expenses', 'value' => $projectExpenses, 'color' => '#DC2626'],
            'profit'          => ['label' => 'Project Profit', 'value' => $projectProfit, 'color' => '#2563EB'],
            'avg_margin'      => ['label' => 'Avg Project Margin', 'value' => $avgMargin . '%', 'color' => '#7C3AED'],
            'open_tasks'      => ['label' => 'Open Tasks', 'value' => $openTasks, 'color' => '#2563EB'],
            'overdue_tasks'   => ['label' => 'Overdue Tasks', 'value' => $overdueTasks, 'color' => '#DC2626'],
            'timesheet_hours' => ['label' => 'Timesheet Hours', 'value' => $loggedHours . ' hrs', 'color' => '#0891B2'],
        ];

        // 1. Project Status Donut
        $projectStatus = [
            'Planning'    => $projects->whereIn('status', ['planning', 'draft'])->count(),
            'In Progress' => $activeProjects,
            'On Hold'     => $onHoldProjects,
            'Completed'   => $completedProjects,
        ];

        // 2. Project Profitability Horizontal Bar
        $profitabilityBars = $projectMargins->sortByDesc('profit')->take(6)->values()->map(fn ($m) => [
            'name'    => $m['project']->name,
            'revenue' => $m['revenue'],
            'expense' => $m['expense'],
            'profit'  => $m['profit'],
        ]);

        // 3. Project Budget vs Actual Grouped Bar
        $budgetVsActual = $projectMargins->where('budget', '>', 0)->take(6)->values()->map(fn ($m) => [
            'name'    => $m['project']->name,
            'budget'  => $m['budget'],
            'actual'  => $m['expense'],
        ]);

        // 4. Monthly Project Revenue Area Chart
        $monthlyRevenueArea = collect(range(5, 0))->map(function ($i) use ($projects) {
            $m = now()->subMonths($i)->format('Y-m');
            $val = (float) $projects->sum(fn ($p) => $p->cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount'));
            return [
                'month'   => now()->subMonths($i)->format('M Y'),
                'revenue' => $val,
            ];
        });

        // 5. Task Distribution Donut
        $taskDistribution = [
            'To Do'       => $tasks->where('status', 'todo')->count(),
            'In Progress' => $tasks->where('status', 'in_progress')->count(),
            'Review'      => $tasks->where('status', 'review')->count(),
            'Done'        => $tasks->whereIn('status', ['done', 'completed'])->count(),
        ];

        // 6. Project Workload Stacked Bar
        $workloadByProject = $timesheets->groupBy('project_id')->map(function ($items, $projId) use ($projects) {
            $p = $projects->firstWhere('id', $projId);
            return [
                'project' => $p?->name ?: ('Project #' . $projId),
                'hours'   => (float) $items->sum('hours'),
            ];
        })->sortByDesc('hours')->take(6)->values();

        // 7. Project Performance Progress
        $projectProgressList = $projects->take(6)->map(function ($p) {
            $totalContract = (float) $p->contract_value;
            $invoiced = (float) $p->invoices->sum('amount');
            $percent = $totalContract > 0 ? min(100, round(($invoiced / $totalContract) * 100)) : 50;
            return [
                'name'     => $p->name,
                'status'   => $p->status,
                'progress' => $percent,
            ];
        });

        // Tables
        $activeProjectsTable = $projects->whereIn('status', ['active', 'in_progress'])->take(6);
        $profitabilityTable  = $projectMargins->sortByDesc('profit')->take(6)->values();
        $upcomingDeadlines   = $projects->filter(fn ($p) => $p->end_date && $p->end_date >= today()->toDateString())->sortBy('end_date')->take(5);
        $overdueTasksTable   = $tasks->whereNotIn('status', ['done', 'completed'])->filter(fn ($t) => $t->due_date && $t->due_date < today()->toDateString())->take(5);
        $timesheetSummary    = $timesheets->take(5);

        return [
            'range'               => $range,
            'kpis'                => $kpis,
            'projectStatus'       => $projectStatus,
            'profitabilityBars'   => $profitabilityBars,
            'budgetVsActual'      => $budgetVsActual,
            'monthlyRevenueArea'  => $monthlyRevenueArea,
            'taskDistribution'    => $taskDistribution,
            'workloadByProject'   => $workloadByProject,
            'projectProgressList' => $projectProgressList,
            'activeProjects'      => $activeProjectsTable,
            'profitabilityTable'  => $profitabilityTable,
            'upcomingDeadlines'   => $upcomingDeadlines,
            'overdueTasks'        => $overdueTasksTable,
            'timesheetSummary'    => $timesheetSummary,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5. HUMAN RESOURCES DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getHrDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $role = $user?->role ?? 'admin';
        $canSalary = $role === 'admin' || in_array($role, ['hr', 'finance', 'management'], true);

        $employees    = $this->applyCompanyContext($request, Employee::with(['departmentRecord', 'jobPosition']))->get();
        $attendances  = $this->applyCompanyContext($request, Attendance::query())->get();
        $todayAtt     = $attendances->where('work_date', today()->toDateString());
        $leaveReqs    = $this->applyCompanyContext($request, LeaveRequest::with('employee'))->latest()->get();
        $salaries     = $canSalary ? $this->applyCompanyContext($request, Salary::with('employee'))->latest()->get() : collect();
        $timesheets   = $this->applyCompanyContext($request, Timesheet::with('employee'))->latest()->get();
        $reviews      = $this->applyCompanyContext($request, PerformanceReview::with('employee'))->latest()->get();
        $skills       = $this->applyCompanyContext($request, EmployeeSkill::with('employee'))->get();
        $departments  = $this->applyCompanyContext($request, Department::query())->get();

        $totalEmp = $employees->count();
        $activeEmp = $employees->whereNull('deleted_at')->count();
        $presentToday = $todayAtt->where('status', 'present')->count();
        $lateToday = $todayAtt->where('status', 'late')->count();
        $absentToday = $todayAtt->where('status', 'absent')->count();
        $leaveToday = $todayAtt->where('status', 'leave')->count();

        $pendingLeave = $leaveReqs->where('status', 'pending')->count();
        $payrollMonth = $canSalary ? (float) $salaries->where('period', now()->format('Y-m'))->sum('net_salary') : 0;
        $unpaidSalaries = $canSalary ? (float) $salaries->whereIn('status', ['draft', 'approved'])->sum('net_salary') : 0;
        $pendingTimesheets = $timesheets->where('status', 'pending')->count();
        $dueReviews = $reviews->where('status', 'pending')->count();
        $skillCoverage = $skills->unique('employee_id')->count();

        $kpis = [
            'total_emp'      => ['label' => 'Total Employees', 'value' => $totalEmp, 'color' => '#0B2345'],
            'active_emp'     => ['label' => 'Active Employees', 'value' => $activeEmp, 'color' => '#16A34A'],
            'present_today'  => ['label' => 'Present Today', 'value' => $presentToday, 'color' => '#16A34A'],
            'late_today'     => ['label' => 'Late Today', 'value' => $lateToday, 'color' => '#EA580C'],
            'absent_today'   => ['label' => 'Absent Today', 'value' => $absentToday, 'color' => '#DC2626'],
            'leave_today'    => ['label' => 'On Leave Today', 'value' => $leaveToday, 'color' => '#2563EB'],
            'pending_leave'  => ['label' => 'Pending Leave Requests', 'value' => $pendingLeave, 'color' => '#D97706'],
            'payroll_month'  => ['label' => 'Payroll Bulan Ini', 'value' => $payrollMonth, 'color' => '#0891B2', 'is_hidden' => !$canSalary],
            'unpaid_salary'  => ['label' => 'Unpaid Salaries', 'value' => $unpaidSalaries, 'color' => '#DC2626', 'is_hidden' => !$canSalary],
            'pending_timesheet'=> ['label' => 'Pending Timesheets', 'value' => $pendingTimesheets, 'color' => '#4F46E5'],
            'reviews_due'    => ['label' => 'Performance Reviews Due', 'value' => $dueReviews, 'color' => '#7C3AED'],
            'skills_coverage'=> ['label' => 'Employee Skills Coverage', 'value' => $skillCoverage . ' org', 'color' => '#0F766E'],
        ];

        // 1. Attendance Distribution Donut
        $attDistribution = [
            'Present' => $presentToday,
            'Late'    => $lateToday,
            'Leave'   => $leaveToday,
            'Absent'  => $absentToday,
        ];

        // 2. Attendance Trend 30 Days (Line Chart)
        $attTrend = collect(range(29, 0))->map(function ($i) use ($attendances) {
            $d = now()->subDays($i)->toDateString();
            $dayAtt = $attendances->where('work_date', $d);
            return [
                'date'    => now()->subDays($i)->format('d M'),
                'present' => $dayAtt->where('status', 'present')->count(),
                'late'    => $dayAtt->where('status', 'late')->count(),
            ];
        });

        // 3. Employee by Department Horizontal Bar
        $empByDept = $departments->map(fn ($dept) => [
            'name'  => $dept->name,
            'count' => $employees->where('department_id', $dept->id)->count(),
        ])->sortByDesc('count')->values();

        // 4. Payroll Cost Trend Area Chart (if permitted)
        $payrollTrend = collect(range(5, 0))->map(function ($i) use ($salaries, $canSalary) {
            $m = now()->subMonths($i)->format('Y-m');
            $cost = $canSalary ? (float) $salaries->where('period', $m)->sum('net_salary') : 0;
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'cost'  => $cost,
            ];
        });

        // 5. Leave Distribution Donut
        $leaveDistribution = [
            'Pending'  => $leaveReqs->where('status', 'pending')->count(),
            'Approved' => $leaveReqs->where('status', 'approved')->count(),
            'Rejected' => $leaveReqs->where('status', 'rejected')->count(),
        ];

        // 6. Employee Skills Horizontal Bar
        $skillsCount = $skills->groupBy('skill_name')->map(fn ($group, $skill) => [
            'skill' => $skill,
            'count' => $group->count(),
        ])->sortByDesc('count')->take(6)->values();

        // 7. Timesheet Hours Stacked Bar
        $timesheetHours = $timesheets->groupBy('work_date')->take(7)->map(fn ($group, $d) => [
            'date'  => Carbon::parse($d)->format('d M'),
            'hours' => (float) $group->sum('hours'),
        ])->values();

        // Tables
        $todayAttendanceTable = $todayAtt->take(6);
        $pendingLeaveTable    = $leaveReqs->where('status', 'pending')->take(5);
        $payrollStatusTable   = $canSalary ? $salaries->take(5) : collect();
        $pendingTimesheetsTable = $timesheets->where('status', 'pending')->take(5);
        $performanceReviewsTable = $reviews->take(5);

        return [
            'range'                   => $range,
            'kpis'                    => $kpis,
            'attDistribution'         => $attDistribution,
            'attTrend'                => $attTrend,
            'empByDept'               => $empByDept,
            'payrollTrend'            => $payrollTrend,
            'leaveDistribution'       => $leaveDistribution,
            'skillsCount'             => $skillsCount,
            'timesheetHours'          => $timesheetHours,
            'todayAttendanceTable'    => $todayAttendanceTable,
            'pendingLeaveTable'       => $pendingLeaveTable,
            'payrollStatusTable'      => $payrollStatusTable,
            'pendingTimesheetsTable'  => $pendingTimesheetsTable,
            'performanceReviewsTable' => $performanceReviewsTable,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 6. FINANCE & ACCOUNTING DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getFinanceDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $cashflows = $this->applyCompanyContext($request, Cashflow::with('project'))->get();
        $invoices  = $this->applyCompanyContext($request, Invoice::with('project'))->get();
        $bills     = $this->applyCompanyContext($request, VendorBill::query())->get();
        $reimburse = $this->applyCompanyContext($request, Reimbursement::query())->get();
        $budgets   = $this->applyCompanyContext($request, Budget::query())->get();
        $journals  = $this->applyCompanyContext($request, JournalEntry::query())->latest()->get();
        $reconItems= $this->applyCompanyContext($request, BankReconciliationItem::query())->get();
        $recurrings= $this->applyCompanyContext($request, RecurringBilling::query())->get();
        $projects  = $this->applyCompanyContext($request, Project::query())->get();

        $totalIncome = (float) $cashflows->where('type', 'income')->sum('amount');
        $totalExpense = (float) $cashflows->where('type', 'expense')->sum('amount');
        $netCashflow = $totalIncome - $totalExpense;

        $ar = (float) $invoices->whereNotIn('status', ['paid', 'void'])->sum(fn ($i) => max(0, (float) $i->amount - (float) $i->paid_amount));
        $ap = (float) $bills->whereNotIn('status', ['paid', 'void'])->sum(fn ($b) => max(0, (float) $b->amount - (float) $b->paid_amount));
        $pendingReimbursements = (float) $reimburse->where('status', 'pending')->sum('amount');

        $totalBudget = (float) $budgets->sum('budget_amount');
        $budgetUtil = $totalBudget > 0 ? round(($totalExpense / $totalBudget) * 100, 1) : 0;

        $unreconciled = $reconItems->where('reconciled', false)->count();
        $activeRecurring = $recurrings->where('status', 'active')->count();

        $kpis = [
            'total_income'     => ['label' => 'Total Income', 'value' => $totalIncome, 'color' => '#16A34A'],
            'total_expense'    => ['label' => 'Total Expense', 'value' => $totalExpense, 'color' => '#DC2626'],
            'net_cashflow'     => ['label' => 'Net Cashflow', 'value' => $netCashflow, 'color' => '#2563EB'],
            'receivables'      => ['label' => 'Total Receivables', 'value' => $ar, 'color' => '#EA580C'],
            'payables'         => ['label' => 'Total Payables', 'value' => $ap, 'color' => '#D97706'],
            'out_invoices'     => ['label' => 'Outstanding Invoices', 'value' => $invoices->whereNotIn('status', ['paid', 'void'])->count() . ' inv', 'color' => '#EA580C'],
            'unpaid_bills'     => ['label' => 'Unpaid Vendor Bills', 'value' => $bills->whereNotIn('status', ['paid', 'void'])->count() . ' bills', 'color' => '#DC2626'],
            'pending_reimburse'=> ['label' => 'Pending Reimbursements', 'value' => $pendingReimbursements, 'color' => '#4F46E5'],
            'budget_util'      => ['label' => 'Budget Utilization', 'value' => $budgetUtil . '%', 'color' => '#7C3AED'],
            'tax_payables'     => ['label' => 'Tax Payables (PPN/PPH)', 'value' => (float) $invoices->sum('amount') * 0.11, 'color' => '#0F766E'],
            'unreconciled'     => ['label' => 'Unreconciled Trans.', 'value' => $unreconciled . ' trans', 'color' => '#DC2626'],
            'recurring'        => ['label' => 'Active Recurring Bills', 'value' => $activeRecurring . ' aktif', 'color' => '#0891B2'],
        ];

        // 1. Cashflow Trend (Area Chart: Cash In, Cash Out, Net)
        $cashflowTrend = collect(range(5, 0))->map(function ($i) use ($cashflows) {
            $m = now()->subMonths($i)->format('Y-m');
            $in = (float) $cashflows->where('type', 'income')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            $out = (float) $cashflows->where('type', 'expense')->filter(fn ($c) => substr((string) $c->transaction_date, 0, 7) === $m)->sum('amount');
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'in'    => $in,
                'out'   => $out,
                'net'   => $in - $out,
            ];
        });

        // 2. Income vs Expense Grouped Bar Chart
        $incVsExp = $cashflowTrend;

        // 3. Expense Distribution Donut Chart
        $expDistribution = $cashflows->where('type', 'expense')
            ->groupBy(fn ($c) => $c->cost_type ?: 'Operasional')
            ->map(fn ($group, $name) => ['label' => ucfirst($name), 'amount' => (float) $group->sum('amount')])
            ->sortByDesc('amount')->take(6)->values();

        // 4. Receivable Aging Stacked Bar
        $arAging = $this->agingBuckets($invoices, 'amount', 'paid_amount');

        // 5. Payable Aging Horizontal Stacked Bar
        $apAging = $this->agingBuckets($bills, 'amount', 'paid_amount');

        // 6. Budget vs Actual Grouped Bar
        $budgetComparison = $budgets->take(6)->map(function ($b) use ($cashflows) {
            $act = (float) $cashflows->where('type', 'expense')->where('project_id', $b->project_id)->sum('amount');
            return [
                'name'   => $b->project?->name ?: ('Budget #' . $b->id),
                'budget' => (float) $b->budget_amount,
                'actual' => $act,
            ];
        });

        // 7. Revenue by Project Horizontal Bar
        $revByProject = $projects->map(fn ($p) => [
            'name'   => $p->name,
            'amount' => (float) $cashflows->where('type', 'income')->where('project_id', $p->id)->sum('amount'),
        ])->filter(fn ($p) => $p['amount'] > 0)->sortByDesc('amount')->take(6)->values();

        // 8. Invoice Payment Status Donut
        $invStatusDonut = [
            'Paid'    => $invoices->where('status', 'paid')->count(),
            'Partial' => $invoices->where('status', 'partial')->count(),
            'Sent'    => $invoices->where('status', 'sent')->count(),
            'Draft'   => $invoices->where('status', 'draft')->count(),
            'Overdue' => $invoices->whereNotIn('status', ['paid', 'void'])->filter(fn ($i) => $i->due_date && $i->due_date < today()->toDateString())->count(),
        ];

        // 9. Reconciliation Status Donut
        $reconDonut = [
            'Matched'   => $reconItems->where('reconciled', true)->count(),
            'Pending'   => $reconItems->where('reconciled', false)->count(),
        ];

        // Tables
        $outInvoicesTable = $invoices->whereNotIn('status', ['paid', 'void'])->take(5);
        $unpaidBillsTable = $bills->whereNotIn('status', ['paid', 'void'])->take(5);
        $pendingReimburseTable = $reimburse->where('status', 'pending')->take(5);
        $recentJournalsTable = $journals->take(5);
        $unreconciledTable = $reconItems->where('reconciled', false)->take(5);
        $budgetVarianceTable = $budgets->take(5);

        return [
            'range'                  => $range,
            'kpis'                   => $kpis,
            'cashflowTrend'          => $cashflowTrend,
            'incVsExp'               => $incVsExp,
            'expDistribution'        => $expDistribution,
            'arAging'                => $arAging,
            'apAging'                => $apAging,
            'budgetComparison'       => $budgetComparison,
            'revByProject'           => $revByProject,
            'invStatusDonut'         => $invStatusDonut,
            'reconDonut'             => $reconDonut,
            'outInvoicesTable'       => $outInvoicesTable,
            'unpaidBillsTable'       => $unpaidBillsTable,
            'pendingReimburseTable'  => $pendingReimburseTable,
            'recentJournalsTable'    => $recentJournalsTable,
            'unreconciledTable'      => $unreconciledTable,
            'budgetVarianceTable'    => $budgetVarianceTable,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 7. PROCUREMENT DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getProcurementDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $requisitions = $this->applyCompanyContext($request, PurchaseRequisition::query())->latest()->get();
        $pos          = $this->applyCompanyContext($request, PurchaseOrder::with('vendor'))->latest()->get();
        $receipts     = $this->applyCompanyContext($request, GoodsReceipt::query())->latest()->get();
        $matches      = $this->applyCompanyContext($request, PurchaseMatch::query())->get();
        $contracts    = $this->applyCompanyContext($request, ProcurementContract::with('vendor'))->get();
        $bills        = $this->applyCompanyContext($request, VendorBill::query())->get();
        $vendors      = $this->applyCompanyContext($request, Vendor::query())->get();

        $totalReq = $requisitions->count();
        $pendingReq = $requisitions->whereIn('status', ['draft', 'pending', 'submitted'])->count();
        $approvedReq = $requisitions->where('status', 'approved')->count();

        $openPo = $pos->whereNotIn('status', ['closed', 'cancelled'])->count();
        $completedPo = $pos->where('status', 'closed')->count();
        $pendingReceipts = $receipts->where('status', 'draft')->count();

        $procurementSpending = (float) $pos->whereNotIn('status', ['cancelled'])->sum('amount');
        $activeVendors = $vendors->count();
        $pendingMatches = $matches->where('match_status', 'unmatched')->count();
        $activeContracts = $contracts->where('status', 'active')->count();
        $contractsExpiring = $contracts->filter(fn ($c) => $c->end_date && Carbon::parse($c->end_date)->lte(now()->addDays(60)))->count();
        $outstandingBills = (float) $bills->whereNotIn('status', ['paid', 'void'])->sum('amount');

        $kpis = [
            'total_req'       => ['label' => 'Total Requisitions', 'value' => $totalReq, 'color' => '#0B2345'],
            'pending_req'     => ['label' => 'Pending Requisitions', 'value' => $pendingReq, 'color' => '#EA580C'],
            'approved_req'    => ['label' => 'Approved Requisitions', 'value' => $approvedReq, 'color' => '#16A34A'],
            'open_po'         => ['label' => 'Open Purchase Orders', 'value' => $openPo, 'color' => '#2563EB'],
            'completed_po'    => ['label' => 'Completed Orders', 'value' => $completedPo, 'color' => '#16A34A'],
            'pending_receipts'=> ['label' => 'Pending Goods Receipts', 'value' => $pendingReceipts, 'color' => '#D97706'],
            'spending'        => ['label' => 'Procurement Spending', 'value' => $procurementSpending, 'color' => '#DC2626'],
            'active_vendors'  => ['label' => 'Active Vendors', 'value' => $activeVendors, 'color' => '#0F766E'],
            'pending_matches' => ['label' => 'Pending Matches', 'value' => $pendingMatches, 'color' => '#EA580C'],
            'contracts'       => ['label' => 'Active Contracts', 'value' => $activeContracts, 'color' => '#7C3AED'],
            'contracts_exp'   => ['label' => 'Contracts Near Expiry', 'value' => $contractsExpiring, 'color' => '#DC2626'],
            'out_bills'       => ['label' => 'Outstanding Bills', 'value' => $outstandingBills, 'color' => '#EA580C'],
        ];

        // 1. Procurement Pipeline (Horizontal Funnel)
        $procurementPipeline = [
            ['stage' => 'Purchase Requisition', 'count' => $totalReq],
            ['stage' => 'Purchase Order',       'count' => $pos->count()],
            ['stage' => 'Goods Receipt',        'count' => $receipts->count()],
            ['stage' => 'Vendor Bill',          'count' => $bills->count()],
        ];

        // 2. Purchase Order Status Donut
        $poStatusDonut = [
            'Draft'     => $pos->where('status', 'draft')->count(),
            'Approved'  => $pos->where('status', 'approved')->count(),
            'Received'  => $pos->where('status', 'received')->count(),
            'Closed'    => $pos->where('status', 'closed')->count(),
            'Cancelled' => $pos->where('status', 'cancelled')->count(),
        ];

        // 3. Monthly Procurement Spending Bar Chart
        $monthlySpending = collect(range(5, 0))->map(function ($i) use ($pos) {
            $m = now()->subMonths($i)->format('Y-m');
            $val = (float) $pos->filter(fn ($p) => $p->order_date && $p->order_date->format('Y-m') === $m)->sum('amount');
            return [
                'month'  => now()->subMonths($i)->format('M Y'),
                'amount' => $val,
            ];
        });

        // 4. Vendor Spending Horizontal Bar
        $vendorSpending = $pos->groupBy('vendor_id')->map(function ($group) {
            $v = $group->first()->vendor;
            return [
                'name'   => $v?->name ?: 'Vendor',
                'amount' => (float) $group->sum('amount'),
            ];
        })->sortByDesc('amount')->take(6)->values();

        // 5. Requisition Status Donut
        $reqStatusDonut = [
            'Draft'     => $requisitions->where('status', 'draft')->count(),
            'Pending'   => $requisitions->whereIn('status', ['pending', 'submitted'])->count(),
            'Approved'  => $requisitions->where('status', 'approved')->count(),
            'Rejected'  => $requisitions->where('status', 'rejected')->count(),
        ];

        // 6. Goods Receipt Trend Line Chart
        $receiptTrend = collect(range(5, 0))->map(function ($i) use ($receipts) {
            $m = now()->subMonths($i)->format('Y-m');
            $count = $receipts->filter(fn ($r) => $r->receipt_date && substr((string) $r->receipt_date, 0, 7) === $m)->count();
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'count' => $count,
            ];
        });

        // 7. Purchase Matching Status Stacked Bar
        $matchingStatus = [
            'Matched'           => $matches->where('match_status', 'matched')->count(),
            'Partially Matched' => $matches->where('match_status', 'partial')->count(),
            'Unmatched'         => $matches->where('match_status', 'unmatched')->count(),
        ];

        // Tables
        $pendingReqTable = $requisitions->whereIn('status', ['draft', 'pending', 'submitted'])->take(5);
        $openPoTable = $pos->whereNotIn('status', ['closed', 'cancelled'])->take(5);
        $pendingReceiptsTable = $receipts->where('status', 'draft')->take(5);
        $matchesTable = $matches->take(5);
        $billsTable = $bills->take(5);
        $contractsTable = $contracts->take(5);

        return [
            'range'                 => $range,
            'kpis'                  => $kpis,
            'procurementPipeline'   => $procurementPipeline,
            'poStatusDonut'         => $poStatusDonut,
            'monthlySpending'       => $monthlySpending,
            'vendorSpending'        => $vendorSpending,
            'reqStatusDonut'        => $reqStatusDonut,
            'receiptTrend'          => $receiptTrend,
            'matchingStatus'        => $matchingStatus,
            'pendingReqTable'       => $pendingReqTable,
            'openPoTable'           => $openPoTable,
            'pendingReceiptsTable'  => $pendingReceiptsTable,
            'matchesTable'          => $matchesTable,
            'billsTable'            => $billsTable,
            'contractsTable'        => $contractsTable,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 8. INVENTORY & WAREHOUSE DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getInventoryDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $products    = $this->applyCompanyContext($request, Product::with(['category', 'unit', 'movements']))->get();
        $movements   = $this->applyCompanyContext($request, StockMovement::with(['product', 'warehouse']))->latest()->get();
        $adjustments = $this->applyCompanyContext($request, StockAdjustment::with('product'))->latest()->get();
        $warehouses  = $this->applyCompanyContext($request, Warehouse::query())->get();
        $categories  = $this->applyCompanyContext($request, ProductCategory::query())->get();

        $totalProducts = $products->count();
        $activeProducts = $products->where('is_active', true)->count();

        // Calculate total stock on hand per product
        $productStockData = $products->map(function ($p) {
            $stock = $p->stockOnHand();
            $val = $stock * (float) $p->cost;
            $isLow = $p->isLowStock();
            $isOut = $stock <= 0;
            return [
                'product'    => $p,
                'stock'      => $stock,
                'valuation'  => $val,
                'is_low'     => $isLow,
                'is_out'     => $isOut,
            ];
        });

        $totalStockQty = (float) $productStockData->sum('stock');
        $totalValuation = (float) $productStockData->sum('valuation');
        $totalWarehouses = $warehouses->count();
        $lowStockCount = $productStockData->where('is_low', true)->count();
        $outOfStockCount = $productStockData->where('is_out', true)->count();

        $stockInQty = (float) $movements->where('type', 'in')->sum('quantity');
        $stockOutQty = (float) $movements->where('type', 'out')->sum('quantity');
        $adjustmentsCount = $adjustments->count();
        $movementsCount = $movements->count();

        $kpis = [
            'total_products'  => ['label' => 'Total Products', 'value' => $totalProducts, 'color' => '#0B2345'],
            'active_products' => ['label' => 'Active Products', 'value' => $activeProducts, 'color' => '#16A34A'],
            'total_stock'     => ['label' => 'Total Stock Quantity', 'value' => number_format($totalStockQty, 0, ',', '.'), 'color' => '#2563EB'],
            'total_warehouses'=> ['label' => 'Total Warehouses', 'value' => $totalWarehouses, 'color' => '#0F766E'],
            'stock_in'        => ['label' => 'Stock In (Total)', 'value' => number_format($stockInQty, 0, ',', '.'), 'color' => '#16A34A'],
            'stock_out'       => ['label' => 'Stock Out (Total)', 'value' => number_format($stockOutQty, 0, ',', '.'), 'color' => '#DC2626'],
            'adjustments'     => ['label' => 'Stock Adjustments', 'value' => $adjustmentsCount, 'color' => '#EA580C'],
            'low_stock'       => ['label' => 'Low Stock Products', 'value' => $lowStockCount, 'color' => '#D97706'],
            'out_of_stock'    => ['label' => 'Out of Stock Products', 'value' => $outOfStockCount, 'color' => '#DC2626'],
            'movement_count'  => ['label' => 'Stock Movements', 'value' => $movementsCount, 'color' => '#7C3AED'],
            'valuation'       => ['label' => 'Inventory Valuation', 'value' => $totalValuation, 'color' => '#16A34A'],
            'categories'      => ['label' => 'Product Categories', 'value' => $categories->count(), 'color' => '#0891B2'],
        ];

        // 1. Stock by Category Donut
        $stockByCategory = $products->groupBy('category_id')->map(function ($group, $catId) use ($categories) {
            $cat = $categories->firstWhere('id', $catId);
            return [
                'name'  => $cat?->name ?: 'Uncategorized',
                'count' => $group->count(),
            ];
        })->sortByDesc('count')->values();

        // 2. Stock In vs Stock Out Grouped Bar
        $stockInVsOut = collect(range(5, 0))->map(function ($i) use ($movements) {
            $m = now()->subMonths($i)->format('Y-m');
            $in = (float) $movements->where('type', 'in')->filter(fn ($mov) => $mov->created_at && $mov->created_at->format('Y-m') === $m)->sum('quantity');
            $out = (float) $movements->where('type', 'out')->filter(fn ($mov) => $mov->created_at && $mov->created_at->format('Y-m') === $m)->sum('quantity');
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'in'    => $in,
                'out'   => $out,
            ];
        });

        // 3. Stock by Warehouse Horizontal Bar
        $stockByWarehouse = $warehouses->map(function ($w) use ($movements) {
            $qty = (float) $movements->where('warehouse_id', $w->id)->sum(fn ($m) => $m->type === 'out' ? -$m->quantity : $m->quantity);
            return [
                'name' => $w->name,
                'qty'  => max(0, $qty),
            ];
        })->sortByDesc('qty')->values();

        // 4. Monthly Stock Movements Line Chart
        $monthlyMovements = collect(range(5, 0))->map(function ($i) use ($movements) {
            $m = now()->subMonths($i)->format('Y-m');
            $count = $movements->filter(fn ($mov) => $mov->created_at && $mov->created_at->format('Y-m') === $m)->count();
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'count' => $count,
            ];
        });

        // 5. Product Movement Distribution Donut
        $movementDistribution = [
            'Inbound (In)'     => $movements->where('type', 'in')->count(),
            'Outbound (Out)'   => $movements->where('type', 'out')->count(),
            'Adjustment'       => $adjustmentsCount,
        ];

        // 6. Top Moving Products Horizontal Bar
        $topMovingProducts = $movements->groupBy('product_id')->map(function ($group) use ($products) {
            $p = $products->firstWhere('id', $group->first()->product_id);
            return [
                'name'  => $p?->name ?: 'Product',
                'count' => $group->count(),
            ];
        })->sortByDesc('count')->take(6)->values();

        // 7. Stock Adjustment Trend Bar Chart
        $adjustmentTrend = collect(range(5, 0))->map(function ($i) use ($adjustments) {
            $m = now()->subMonths($i)->format('Y-m');
            $count = $adjustments->filter(fn ($a) => $a->created_at && $a->created_at->format('Y-m') === $m)->count();
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'count' => $count,
            ];
        });

        // Tables
        $stockSummaryTable = $productStockData->take(6);
        $outOfStockTable = $productStockData->where('is_out', true)->take(5);
        $lowStockTable = $productStockData->where('is_low', true)->take(5);
        $recentMovementsTable = $movements->take(6);
        $recentAdjustmentsTable = $adjustments->take(5);

        return [
            'range'                 => $range,
            'kpis'                  => $kpis,
            'stockByCategory'       => $stockByCategory,
            'stockInVsOut'          => $stockInVsOut,
            'stockByWarehouse'      => $stockByWarehouse,
            'monthlyMovements'      => $monthlyMovements,
            'movementDistribution'  => $movementDistribution,
            'topMovingProducts'     => $topMovingProducts,
            'adjustmentTrend'       => $adjustmentTrend,
            'stockSummaryTable'     => $stockSummaryTable,
            'outOfStockTable'       => $outOfStockTable,
            'lowStockTable'         => $lowStockTable,
            'recentMovementsTable'  => $recentMovementsTable,
            'recentAdjustmentsTable'=> $recentAdjustmentsTable,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 9. CMS & WEBSITE DASHBOARD
    // ─────────────────────────────────────────────────────────────────────────
    public function getCmsDashboardData(Request $request, ?User $user = null): array
    {
        $range = $this->resolveDateRange($request);
        $posts    = BlogPost::query()->latest()->get();
        $sections = CmsSection::query()->get();

        $totalPosts = $posts->count();
        $publishedPosts = $posts->where('status', 'published')->count();
        $draftPosts = $posts->where('status', 'draft')->count();

        $activeSections = $sections->where('is_visible', true)->count();
        $hiddenSections = $sections->where('is_visible', false)->count();

        $recentUpdated = $sections->filter(fn ($s) => $s->updated_at && $s->updated_at->gte(now()->subDays(7)))->count();
        $staleContent = $sections->filter(fn ($s) => !$s->updated_at || $s->updated_at->lt(now()->subDays(90)))->count();

        $kpis = [
            'total_posts'      => ['label' => 'Total Blog Posts', 'value' => $totalPosts, 'color' => '#0B2345'],
            'published_posts'  => ['label' => 'Published Posts', 'value' => $publishedPosts, 'color' => '#16A34A'],
            'draft_posts'      => ['label' => 'Draft Posts', 'value' => $draftPosts, 'color' => '#EA580C'],
            'active_sections'  => ['label' => 'Active Website Sections', 'value' => $activeSections, 'color' => '#2563EB'],
            'hidden_sections'  => ['label' => 'Hidden Sections', 'value' => $hiddenSections, 'color' => '#DC2626'],
            'recent_updates'   => ['label' => 'Updated (7 Hari)', 'value' => $recentUpdated . ' bagian', 'color' => '#0891B2'],
            'stale_content'    => ['label' => 'Stale Content (>90d)', 'value' => $staleContent . ' konten', 'color' => '#D97706'],
            'total_sections'   => ['label' => 'Total Sections', 'value' => $sections->count(), 'color' => '#7C3AED'],
        ];

        // 1. Blog Publication Trend Line Chart
        $publicationTrend = collect(range(5, 0))->map(function ($i) use ($posts) {
            $m = now()->subMonths($i)->format('Y-m');
            $cnt = $posts->where('status', 'published')->filter(fn ($p) => $p->published_at && $p->published_at->format('Y-m') === $m)->count();
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'count' => $cnt,
            ];
        });

        // 2. Blog Status Distribution Donut
        $blogStatusDonut = [
            'Published' => $publishedPosts,
            'Draft'     => $draftPosts,
        ];

        // 3. Website Section Status Donut
        $sectionStatusDonut = [
            'Active' => $activeSections,
            'Hidden' => $hiddenSections,
        ];

        // 4. Content Freshness Horizontal Bar
        $contentFreshness = [
            ['label' => '0-7 hari',   'count' => $recentUpdated],
            ['label' => '8-30 hari',  'count' => $sections->filter(fn ($s) => $s->updated_at && $s->updated_at->lt(now()->subDays(7)) && $s->updated_at->gte(now()->subDays(30)))->count()],
            ['label' => '31-90 hari', 'count' => $sections->filter(fn ($s) => $s->updated_at && $s->updated_at->lt(now()->subDays(30)) && $s->updated_at->gte(now()->subDays(90)))->count()],
            ['label' => '>90 hari',   'count' => $staleContent],
        ];

        // 5. Content Activity Bar Chart
        $contentActivity = collect(range(5, 0))->map(function ($i) use ($posts, $sections) {
            $m = now()->subMonths($i)->format('Y-m');
            $pCount = $posts->filter(fn ($p) => $p->created_at && $p->created_at->format('Y-m') === $m)->count();
            $sCount = $sections->filter(fn ($s) => $s->updated_at && $s->updated_at->format('Y-m') === $m)->count();
            return [
                'month'   => now()->subMonths($i)->format('M Y'),
                'posts'   => $pCount,
                'sections'=> $sCount,
            ];
        });

        // Tables
        $recentBlogPosts = $posts->take(5);
        $draftArticles = $posts->where('status', 'draft')->take(5);
        $recentlyUpdatedSections = $sections->sortByDesc('updated_at')->take(5);
        $staleContentSections = $sections->filter(fn ($s) => !$s->updated_at || $s->updated_at->lt(now()->subDays(90)))->take(5);

        return [
            'range'                   => $range,
            'kpis'                    => $kpis,
            'publicationTrend'        => $publicationTrend,
            'blogStatusDonut'         => $blogStatusDonut,
            'sectionStatusDonut'      => $sectionStatusDonut,
            'contentFreshness'        => $contentFreshness,
            'contentActivity'         => $contentActivity,
            'recentBlogPosts'         => $recentBlogPosts,
            'draftArticles'           => $draftArticles,
            'recentlyUpdatedSections' => $recentlyUpdatedSections,
            'staleContentSections'    => $staleContentSections,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 10. MY WORKSPACE DASHBOARD (PERSONAL USER CONTEXT)
    // ─────────────────────────────────────────────────────────────────────────
    public function getWorkspaceDashboardData(Request $request, User $user): array
    {
        $range = $this->resolveDateRange($request);
        $userId = $user->id;
        // ERP requests are linked to employees, while authentication is linked to users.
        // Do not query employee-owned tables with the non-existent user_id column.
        $employeeId = Employee::query()
            ->where('name', $user->name)
            ->value('id');

        // My tasks
        $myTasks = Task::where('assigned_to', $userId)->get();
        $myOpenTasks = $myTasks->whereNotIn('status', ['done', 'completed'])->count();
        $myCompletedTasks = $myTasks->whereIn('status', ['done', 'completed'])->count();
        $myOverdueTasks = $myTasks->whereNotIn('status', ['done', 'completed'])
            ->filter(fn ($t) => $t->due_date && $t->due_date < today()->toDateString())
            ->count();

        // My pending requests
        $myReimbursements = $employeeId ? Reimbursement::where('employee_id', $employeeId)->get() : collect();
        $myLeaveRequests  = $employeeId ? LeaveRequest::where('employee_id', $employeeId)->get() : collect();
        $pendingRequests  = $myReimbursements->where('status', 'pending')->count() + $myLeaveRequests->where('status', 'pending')->count();

        // My Approvals (reimbursements or tasks assigned to approve)
        $approvalsAssigned = Reimbursement::where('status', 'pending')->count(); // For admin/approver

        // My Timesheets
        $myTimesheets = $employeeId ? Timesheet::where('employee_id', $employeeId)->get() : collect();
        $myTimesheetHours = (float) $myTimesheets->sum('hours');

        // My Attendance this month
        $myAttendances = $employeeId ? Attendance::where('employee_id', $employeeId)
            ->whereMonth('work_date', now()->month)
            ->get() : collect();
        $myPresentDays = $myAttendances->where('status', 'present')->count();

        // My Assigned Projects
        $myProjects = Project::where('status', 'active')->take(5)->get();

        $kpis = [
            'open_tasks'      => ['label' => 'My Open Tasks', 'value' => $myOpenTasks, 'color' => '#2563EB'],
            'completed_tasks' => ['label' => 'My Completed Tasks', 'value' => $myCompletedTasks, 'color' => '#16A34A'],
            'overdue_tasks'   => ['label' => 'My Overdue Tasks', 'value' => $myOverdueTasks, 'color' => '#DC2626'],
            'approvals'       => ['label' => 'My Pending Approvals', 'value' => $approvalsAssigned, 'color' => '#EA580C'],
            'requests'        => ['label' => 'My Pending Requests', 'value' => $pendingRequests, 'color' => '#D97706'],
            'timesheet_hours' => ['label' => 'My Timesheet Hours', 'value' => $myTimesheetHours . ' hrs', 'color' => '#0891B2'],
            'attendance_days' => ['label' => 'My Attendance (Bulan Ini)', 'value' => $myPresentDays . ' hari', 'color' => '#16A34A'],
            'assigned_proj'   => ['label' => 'My Assigned Projects', 'value' => $myProjects->count(), 'color' => '#7C3AED'],
        ];

        // 1. My Task Status Donut
        $myTaskStatusDonut = [
            'To Do'       => $myTasks->where('status', 'todo')->count(),
            'In Progress' => $myTasks->where('status', 'in_progress')->count(),
            'Review'      => $myTasks->where('status', 'review')->count(),
            'Done'        => $myCompletedTasks,
        ];

        // 2. My Productivity Bar Chart (completed per month)
        $myProductivity = collect(range(5, 0))->map(function ($i) use ($myTasks) {
            $m = now()->subMonths($i)->format('Y-m');
            $cnt = $myTasks->whereIn('status', ['done', 'completed'])
                ->filter(fn ($t) => $t->updated_at && $t->updated_at->format('Y-m') === $m)->count();
            return [
                'month'     => now()->subMonths($i)->format('M Y'),
                'completed' => $cnt,
            ];
        });

        // 3. My Timesheet Area Chart
        $myTimesheetArea = collect(range(5, 0))->map(function ($i) use ($myTimesheets) {
            $m = now()->subMonths($i)->format('Y-m');
            $val = (float) $myTimesheets->filter(fn ($t) => $t->work_date && substr((string) $t->work_date, 0, 7) === $m)->sum('hours');
            return [
                'month' => now()->subMonths($i)->format('M Y'),
                'hours' => $val,
            ];
        });

        // 4. My Workload Horizontal Bar (tasks per project)
        $myWorkload = $myTasks->groupBy('project_id')->map(function ($group, $projId) use ($myProjects) {
            $p = $myProjects->firstWhere('id', $projId);
            return [
                'name'  => $p?->name ?: ('Project #' . $projId),
                'tasks' => $group->count(),
            ];
        })->values();

        // 5. My Requests Donut
        $allMyReqs = $myReimbursements->concat($myLeaveRequests);
        $myRequestsDonut = [
            'Pending'  => $allMyReqs->where('status', 'pending')->count(),
            'Approved' => $allMyReqs->where('status', 'approved')->count(),
            'Rejected' => $allMyReqs->where('status', 'rejected')->count(),
        ];

        // Tables
        $myTasksTable = $myTasks->take(6);
        $myUpcomingDeadlines = $myTasks->whereNotIn('status', ['done', 'completed'])->filter(fn ($t) => $t->due_date && $t->due_date >= today()->toDateString())->sortBy('due_date')->take(5);

        return [
            'range'               => $range,
            'kpis'                => $kpis,
            'myTaskStatusDonut'   => $myTaskStatusDonut,
            'myProductivity'      => $myProductivity,
            'myTimesheetArea'     => $myTimesheetArea,
            'myWorkload'          => $myWorkload,
            'myRequestsDonut'     => $myRequestsDonut,
            'myTasksTable'        => $myTasksTable,
            'myUpcomingDeadlines' => $myUpcomingDeadlines,
            'myProjects'          => $myProjects,
        ];
    }

    /**
     * Calculate aging buckets for invoices / vendor bills.
     */
    protected function agingBuckets($items, string $totalKey = 'amount', string $paidKey = 'paid_amount'): array
    {
        $raw = [
            'not_due' => 0.0,
            '1_30'    => 0.0,
            '31_60'   => 0.0,
            '61_90'   => 0.0,
            '90_plus' => 0.0,
        ];

        $today = Carbon::today();

        foreach ($items as $item) {
            if (in_array($item->status ?? '', ['paid', 'void', 'cancelled'])) {
                continue;
            }
            $due = $item->due_date ? Carbon::parse($item->due_date) : null;
            $outstanding = max(0, (float) ($item->$totalKey ?? 0) - (float) ($item->$paidKey ?? 0));

            if ($outstanding <= 0) {
                continue;
            }

            if (!$due || $due->isFuture()) {
                $raw['not_due'] += $outstanding;
            } else {
                $days = $due->diffInDays($today);
                if ($days <= 30) {
                    $raw['1_30'] += $outstanding;
                } elseif ($days <= 60) {
                    $raw['31_60'] += $outstanding;
                } elseif ($days <= 90) {
                    $raw['61_90'] += $outstanding;
                } else {
                    $raw['90_plus'] += $outstanding;
                }
            }
        }

        return [
            ['label' => 'Not Due',    'amount' => round($raw['not_due'], 2)],
            ['label' => '1-30 Hari',  'amount' => round($raw['1_30'], 2)],
            ['label' => '31-60 Hari', 'amount' => round($raw['31_60'], 2)],
            ['label' => '61-90 Hari', 'amount' => round($raw['61_90'], 2)],
            ['label' => '90+ Hari',   'amount' => round($raw['90_plus'], 2)],
        ];
    }
}
