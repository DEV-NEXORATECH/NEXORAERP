<?php

namespace App\Http\Controllers;

use App\Models\FeatureFlag;

class AllMenuController extends Controller
{
    public function index()
    {
        $menus = $this->menus();

        return view('erp.all-menu.index', compact('menus'));
    }

    public function show(string $section)
    {
        if ($section === 'admin') {
            return redirect()->route('admin.index');
        }

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
        $enabled = fn (string $flag) => FeatureFlag::isEnabled($flag);
        $menus = [];

        $menus['Main'] = [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard', 'can' => true, 'flag' => 'platform.dashboard'],
        ];

        if ($can('admin', 'sales') && FeatureFlag::moduleEnabled('sales')) {
            $menus['Sales'] = [
                ['label' => 'Projects',          'icon' => 'projects',      'route' => 'projects.index',             'can' => true, 'flag' => 'sales.projects'],
                ['label' => 'Proposals',         'icon' => 'proposal',      'route' => 'sales.index',                'can' => true, 'flag' => 'sales.proposals'],
                ['label' => 'Sales Inquiries',   'icon' => 'inquiry',       'route' => 'sales-inquiries.index',       'can' => true, 'flag' => 'crm.inquiries'],
                ['label' => 'Sales Leads',       'icon' => 'lead',          'route' => 'sales-leads.index',            'can' => true, 'flag' => 'crm.leads'],
                ['label' => 'Sales Orders',      'icon' => 'invoice',       'route' => 'sales-orders.index',           'can' => true, 'flag' => 'sales.orders'],
                ['label' => 'Sales Targets',     'icon' => 'target',        'route' => 'sales-targets.index',          'can' => true, 'flag' => 'sales.targets'],
                ['label' => 'Sales Commissions', 'icon' => 'commission',    'route' => 'sales-commissions.index',      'can' => true, 'flag' => 'sales.commissions'],
                ['label' => 'Client Contracts',  'icon' => 'contract',      'route' => 'client-contracts.index',       'can' => true, 'flag' => 'sales.contracts'],
            ];
        }

        if ($can('admin', 'hr') && FeatureFlag::moduleEnabled('hr')) {
            $menus['HR'] = [
                ['label' => 'Employees',           'icon' => 'employees',     'route' => 'hr.index',                   'can' => true, 'flag' => 'hr.employees'],
                ['label' => 'Salaries',            'icon' => 'salary',        'route' => 'salaries.index-page',        'can' => true, 'flag' => 'hr.salaries'],
                ['label' => 'Employee Skills',     'icon' => 'skills',        'route' => 'employee-skills.index',       'can' => true, 'flag' => 'hr.skills'],
                ['label' => 'Attendances',         'icon' => 'attendance',    'route' => 'attendances.index',           'can' => true, 'flag' => 'hr.attendances'],
                ['label' => 'Timesheets',          'icon' => 'timesheet',     'route' => 'timesheets.index',            'can' => true, 'flag' => 'hr.timesheets'],
                ['label' => 'Leave Requests',      'icon' => 'leave',         'route' => 'leave-requests.index',        'can' => true, 'flag' => 'hr.leaves'],
                ['label' => 'Performance Reviews', 'icon' => 'review',        'route' => 'performance-reviews.index',   'can' => true, 'flag' => 'hr.reviews'],
                ['label' => 'Payroll Benefits',    'icon' => 'benefit',       'route' => 'payroll-benefits.index',      'can' => true, 'flag' => 'hr.benefits'],
            ];
        }

        if ($can('admin', 'finance') && FeatureFlag::moduleEnabled('accounting')) {
            $menus['Finance'] = [
                ['label' => 'Reimbursements',        'icon' => 'reimbursement', 'route' => 'reimbursements.index-page',      'can' => true, 'flag' => 'accounting.reimbursements'],
                ['label' => 'Cashflows',             'icon' => 'cashflow',      'route' => 'cashflows.index-page',           'can' => true, 'flag' => 'accounting.cashflows'],
                ['label' => 'Invoices',              'icon' => 'invoice',       'route' => 'finance.invoices.index',              'can' => true, 'flag' => 'accounting.invoices'],
                ['label' => 'Chart of Accounts',     'icon' => 'accounts',      'route' => 'finance.chart-accounts.index',        'can' => true, 'flag' => 'accounting.chart-accounts'],
                ['label' => 'Journal Entries',       'icon' => 'journal',       'route' => 'finance.journal-entries.index',       'can' => true, 'flag' => 'accounting.journal-entries'],
                ['label' => 'Recurring Billings',    'icon' => 'recurring',     'route' => 'finance.recurring-billings.index',    'can' => true, 'flag' => 'accounting.recurring-billings'],
                ['label' => 'Payment Reminders',     'icon' => 'reminder',      'route' => 'finance.payment-reminders.index',     'can' => true, 'flag' => 'accounting.payment-reminders'],
                ['label' => 'Vendor Bills',          'icon' => 'invoice',       'route' => 'finance.vendor-bills.index',          'can' => true, 'flag' => 'accounting.vendor-bills'],
                ['label' => 'Vendor Payments',       'icon' => 'salary',        'route' => 'finance.vendor-payments.index',       'can' => true, 'flag' => 'accounting.vendor-payments'],
                ['label' => 'Budgets & Forecasts',   'icon' => 'budget',        'route' => 'finance.budgets.index',               'can' => true, 'flag' => 'accounting.budgets'],
                ['label' => 'Tax Rules',             'icon' => 'tax',           'route' => 'finance.tax-rules.index',             'can' => true, 'flag' => 'accounting.tax-rules'],
                ['label' => 'Fixed Assets',          'icon' => 'asset',         'route' => 'finance.fixed-assets.index',          'can' => true, 'flag' => 'accounting.fixed-assets'],
                ['label' => 'Currency Rates',        'icon' => 'currency',      'route' => 'finance.currency-rates.index',        'can' => true, 'flag' => 'accounting.currency-rates'],
                ['label' => 'Revenue Schedules',     'icon' => 'revenue',       'route' => 'finance.revenue-schedules.index',     'can' => true, 'flag' => 'accounting.revenue-schedules'],
                ['label' => 'Bank Reconciliations',  'icon' => 'reconcile',     'route' => 'finance.bank-reconciliations.index',  'can' => true, 'flag' => 'accounting.bank-reconciliation'],
                ['label' => 'Purchase Matches',      'icon' => 'receipt',       'route' => 'finance.purchase-matches.index',      'can' => true, 'flag' => 'accounting.purchase-matches'],
                ['label' => 'Accounting Journals',   'icon' => 'journal',       'route' => 'accounting-journals.index',           'can' => true, 'flag' => 'accounting.journals'],
                ['label' => 'Payment Terms',         'icon' => 'reminder',      'route' => 'payment-terms.index',                 'can' => true, 'flag' => 'accounting.payment-terms'],
                ['label' => 'Currencies',            'icon' => 'currency',      'route' => 'currencies.index',                    'can' => true, 'flag' => 'accounting.currencies'],
                ['label' => 'Fiscal Periods',        'icon' => 'calendar',      'route' => 'fiscal-periods.index',                'can' => true, 'flag' => 'accounting.fiscal-periods'],
            ];
        }

        if ($can('admin', 'finance') && FeatureFlag::moduleEnabled('purchase')) {
            $menus['Procurement'] = [
                ['label' => 'Vendor Management',  'icon' => 'vendor',   'route' => 'vendors.index',               'can' => true, 'flag' => 'purchase.vendors'],
                ['label' => 'Purchase Requisition','icon' => 'purchase','route' => 'purchase-requisitions.index',  'can' => true, 'flag' => 'purchase.requisitions'],
                ['label' => 'Purchase Order',      'icon' => 'purchase','route' => 'purchase-orders.index',        'can' => true, 'flag' => 'purchase.orders'],
                ['label' => 'Receipt Verification','icon' => 'receipt', 'route' => 'goods-receipts.index',         'can' => true, 'flag' => 'purchase.goods-receipts'],
                ['label' => 'Procurement Contract','icon' => 'contract','route' => 'procurement-contracts.index',  'can' => true, 'flag' => 'purchase.contracts'],
            ];
        }

        if ($can('admin', 'finance', 'sales') && FeatureFlag::moduleEnabled('inventory')) {
            $menus['Inventory'] = [
                ['label' => 'Products',          'icon' => 'box',       'route' => 'products.index',             'can' => true, 'flag' => 'inventory.products'],
                ['label' => 'Product Categories','icon' => 'category',  'route' => 'product-categories.index',    'can' => true, 'flag' => 'inventory.categories'],
                ['label' => 'Units of Measure',  'icon' => 'measure',   'route' => 'product-units.index',         'can' => true, 'flag' => 'inventory.units'],
                ['label' => 'Warehouses',        'icon' => 'building',  'route' => 'warehouses.index',            'can' => true, 'flag' => 'inventory.warehouses'],
                ['label' => 'Stock Locations',   'icon' => 'pin',       'route' => 'stock-locations.index',       'can' => true, 'flag' => 'inventory.locations'],
                ['label' => 'Stock Movements',   'icon' => 'move',      'route' => 'stock-movements.index',       'can' => true, 'flag' => 'inventory.stock'],
                ['label' => 'Stock Adjustments', 'icon' => 'adjust',    'route' => 'stock-adjustments.index',     'can' => true, 'flag' => 'inventory.adjustments'],
            ];
        }

        $reports = [];
        if ($can('admin', 'finance') && FeatureFlag::moduleEnabled('reports')) {
            $reports[] = ['label' => 'Report Dashboard', 'icon' => 'reports', 'route' => 'reports.index', 'can' => true, 'flag' => 'reports.profit-loss'];
            $reports[] = ['label' => 'Profit & Loss', 'icon' => 'revenue', 'route' => 'reports.profit-loss', 'can' => true, 'flag' => 'reports.profit-loss'];
            $reports[] = ['label' => 'Balance Sheet', 'icon' => 'accounts', 'route' => 'reports.balance-sheet', 'can' => true, 'flag' => 'reports.balance-sheet'];
            $reports[] = ['label' => 'Cash Flow', 'icon' => 'cashflow', 'route' => 'reports.cash-flow', 'can' => true, 'flag' => 'reports.cash-flow'];
            $reports[] = ['label' => 'Project Profitability', 'icon' => 'projects', 'route' => 'reports.project', 'can' => true, 'flag' => 'reports.project-profitability'];
            $reports[] = ['label' => 'Tax Summary', 'icon' => 'tax', 'route' => 'reports.tax', 'can' => true, 'flag' => 'reports.tax'];
            $reports[] = ['label' => 'Budget vs Actual', 'icon' => 'budget', 'route' => 'reports.budget', 'can' => true, 'flag' => 'reports.budget-vs-actual'];
            $reports[] = ['label' => 'Transactions', 'icon' => 'journal', 'route' => 'reports.transactions', 'can' => true, 'flag' => 'reports.transactions'];
            $reports[] = ['label' => 'Reconciliation', 'icon' => 'reconcile', 'route' => 'reports.reconciliation', 'can' => true, 'flag' => 'reports.reconciliation'];
            $reports[] = ['label' => 'Trial Balance', 'icon' => 'reports', 'route' => 'reports.trial-balance', 'can' => true, 'flag' => 'reports.trial-balance'];
            $reports[] = ['label' => 'General Ledger', 'icon' => 'journal', 'route' => 'reports.general-ledger', 'can' => true, 'flag' => 'reports.general-ledger'];
        }
        if ($can('admin') && $enabled('platform.backup')) {
            $reports[] = ['label' => 'Backup Database', 'icon' => 'backup',  'route' => 'backup.download', 'can' => true, 'flag' => 'platform.backup'];
        }
        if ($reports) {
            $menus['Reports'] = collect($reports)->filter(fn ($r) => $enabled($r['flag']))->values()->all();
        }

        foreach ($menus as $section => $items) {
            if ($section === 'Main' || $section === 'Reports') {
                continue;
            }
            $menus[$section] = collect($items)->filter(fn ($item) => $enabled($item['flag']))->values()->all();
            if (empty($menus[$section])) {
                unset($menus[$section]);
            }
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
