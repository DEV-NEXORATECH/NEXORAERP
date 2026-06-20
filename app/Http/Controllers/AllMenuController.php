<?php

namespace App\Http\Controllers;

class AllMenuController extends Controller
{
    public function index()
    {
        $menus = $this->menus();

        return view('erp.all-menu.index', compact('menus'));
    }

    public function show(string $section)
    {
        $menus = $this->menus();
        $sectionMap = collect(array_keys($menus))->mapWithKeys(fn ($key) => [str($key)->slug()->toString() => $key]);
        abort_unless($sectionMap->has($section), 404);

        $sectionName = $sectionMap[$section];
        $items = $menus[$sectionName];

        return view('erp.all-menu.show', compact('section', 'sectionName', 'items'));
    }

    private function menus(): array
    {
        $role = auth()->user()->role;
        $can = fn (...$roles) => $role === 'admin' || in_array($role, $roles, true);
        $menus = [];

        $menus['Main'] = [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard', 'can' => true],
        ];

        if ($can('admin', 'sales')) {
            $menus['Sales'] = [
                ['label' => 'Projects',          'icon' => 'projects',      'route' => 'projects.index',             'can' => true],
                ['label' => 'Proposals',         'icon' => 'proposal',      'route' => 'sales.index',                'can' => true],
                ['label' => 'Sales Inquiries',   'icon' => 'cashflow',      'route' => 'sales-inquiries.index',       'can' => true],
                ['label' => 'Sales Leads',       'icon' => 'projects',      'route' => 'sales-leads.index',            'can' => true],
                ['label' => 'Sales Orders',      'icon' => 'invoice',       'route' => 'sales-orders.index',           'can' => true],
                ['label' => 'Sales Targets',     'icon' => 'reports',       'route' => 'sales-targets.index',          'can' => true],
                ['label' => 'Sales Commissions', 'icon' => 'salary',        'route' => 'sales-commissions.index',      'can' => true],
                ['label' => 'Client Contracts',  'icon' => 'master',        'route' => 'client-contracts.index',       'can' => true],
            ];
        }

        if ($can('admin', 'hr')) {
            $menus['HR'] = [
                ['label' => 'Employees',           'icon' => 'employees',     'route' => 'hr.index',                   'can' => true],
                ['label' => 'Salaries',            'icon' => 'salary',        'route' => 'salaries.index-page',        'can' => true],
                ['label' => 'Employee Skills',     'icon' => 'master',        'route' => 'employee-skills.index',       'can' => true],
                ['label' => 'Attendances',         'icon' => 'audit',         'route' => 'attendances.index',           'can' => true],
                ['label' => 'Timesheets',          'icon' => 'projects',      'route' => 'timesheets.index',            'can' => true],
                ['label' => 'Leave Requests',      'icon' => 'reimbursement', 'route' => 'leave-requests.index',        'can' => true],
                ['label' => 'Performance Reviews', 'icon' => 'reports',       'route' => 'performance-reviews.index',   'can' => true],
                ['label' => 'Payroll Benefits',    'icon' => 'salary',        'route' => 'payroll-benefits.index',      'can' => true],
            ];
        }

        if ($can('admin', 'finance')) {
            $menus['Finance'] = [
                ['label' => 'Reimbursements',        'icon' => 'reimbursement', 'route' => 'reimbursements.index-page',      'can' => true],
                ['label' => 'Cashflows',             'icon' => 'cashflow',      'route' => 'cashflows.index-page',           'can' => true],
                ['label' => 'Invoices',              'icon' => 'invoice',       'route' => 'finance.index',                  'can' => true],
                ['label' => 'Chart of Accounts',     'icon' => 'master',        'route' => 'chart-accounts.index',            'can' => true],
                ['label' => 'Journal Entries',       'icon' => 'audit',         'route' => 'journal-entries.index',           'can' => true],
                ['label' => 'Recurring Billings',    'icon' => 'cashflow',      'route' => 'recurring-billings.index',        'can' => true],
                ['label' => 'Payment Reminders',     'icon' => 'reimbursement', 'route' => 'payment-reminders.index',         'can' => true],
                ['label' => 'Vendor Bills',          'icon' => 'invoice',       'route' => 'vendor-bills.index',              'can' => true],
                ['label' => 'Vendor Payments',       'icon' => 'salary',        'route' => 'vendor-payments.index',           'can' => true],
                ['label' => 'Budgets & Forecasts',   'icon' => 'reports',       'route' => 'budgets.index',                   'can' => true],
                ['label' => 'Tax Rules',             'icon' => 'settings',      'route' => 'tax-rules.index',                 'can' => true],
                ['label' => 'Fixed Assets',          'icon' => 'cashflow',      'route' => 'fixed-assets.index',              'can' => true],
                ['label' => 'Currency Rates',        'icon' => 'cashflow',      'route' => 'currency-rates.index',            'can' => true],
                ['label' => 'Revenue Schedules',     'icon' => 'reports',       'route' => 'revenue-schedules.index',         'can' => true],
                ['label' => 'Bank Reconciliations',  'icon' => 'audit',         'route' => 'bank-reconciliation-items.index', 'can' => true],
                ['label' => 'Purchase Matches',      'icon' => 'master',        'route' => 'purchase-matches.index',          'can' => true],
            ];

            $menus['Procurement'] = [
                ['label' => 'Vendor Management',  'icon' => 'users',    'route' => 'vendors.index',               'can' => true],
                ['label' => 'Purchase Requisition','icon' => 'master',  'route' => 'purchase-requisitions.index',  'can' => true],
                ['label' => 'Purchase Order',      'icon' => 'invoice', 'route' => 'purchase-orders.index',        'can' => true],
                ['label' => 'Receipt Verification','icon' => 'reimbursement', 'route' => 'goods-receipts.index',   'can' => true],
                ['label' => 'Procurement Contract','icon' => 'salary',  'route' => 'procurement-contracts.index',  'can' => true],
            ];
        }

        if ($can('admin')) {
            $menus['Admin'] = [
                ['label' => 'Company Setting', 'icon' => 'settings', 'route' => 'admin.index',   'can' => true],
                ['label' => 'Users',           'icon' => 'users',    'route' => 'admin.users',    'can' => true],
                ['label' => 'Master Data',     'icon' => 'master',   'route' => 'admin.masters',  'can' => true],
                ['label' => 'Trash',           'icon' => 'trash',    'route' => 'admin.trash',    'can' => true],
                ['label' => 'Audit Log',       'icon' => 'audit',    'route' => 'admin.audit',    'can' => true],
            ];
        }

        $reports = [];
        if ($can('admin', 'finance')) {
            $reports[] = ['label' => 'Reports',        'icon' => 'reports', 'route' => 'reports.index', 'can' => true];
        }
        if ($can('admin')) {
            $reports[] = ['label' => 'Backup Database', 'icon' => 'backup',  'route' => 'backup.database', 'can' => true];
        }
        if ($reports) {
            $menus['Reports'] = $reports;
        }

        return $menus;
    }

    public function approvals()
    {
        $role = auth()->user()->role;
        $can = fn (...$roles) => $role === 'admin' || in_array($role, $roles, true);

        $items = collect();

        if ($can('admin', 'sales')) {
            $items->push([
                'label' => 'Proposal menunggu approval',
                'route' => 'sales.index',
                'icon' => 'proposal',
                'count' => \App\Models\Proposal::where('status', 'sent')->count(),
                'desc' => 'Draft/sent proposal yang perlu keputusan.',
            ]);
        }

        if ($can('admin', 'finance', 'hr')) {
            $items->push([
                'label' => 'Reimbursement pending',
                'route' => 'reimbursements.index-page',
                'icon' => 'reimbursement',
                'count' => \App\Models\Reimbursement::where('status', 'pending')->count(),
                'desc' => 'Klaim reimbursement menunggu approval/payment.',
            ]);
        }

        if ($can('admin', 'hr', 'finance')) {
            $items->push([
                'label' => 'Salary approved/unpaid',
                'route' => 'salaries.index-page',
                'icon' => 'salary',
                'count' => \App\Models\Salary::whereIn('status', ['draft', 'approved'])->count(),
                'desc' => 'Salary draft atau approved yang belum paid.',
            ]);
        }

        if ($can('admin', 'finance')) {
            $items->push([
                'label' => 'Invoice jatuh tempo',
                'route' => 'finance.index',
                'icon' => 'invoice',
                'count' => \App\Models\Invoice::whereNotIn('status', ['paid', 'void'])->count(),
                'desc' => 'Invoice belum lunas dan perlu follow-up.',
            ]);
        }

        $users = \App\Models\User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'role']);
        $taskStatuses = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];
        $tasks = \App\Models\Task::with(['assignee:id,name,role', 'creator:id,name'])
            ->latest()
            ->get()
            ->groupBy('status');

        return view('erp.all-menu.approvals', compact('items', 'users', 'taskStatuses', 'tasks'));
    }
}
