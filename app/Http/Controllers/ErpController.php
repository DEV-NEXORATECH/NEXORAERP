<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\JobPosition;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ErpController extends Controller
{
    public function dashboard(Request $request): View
    {
        return $this->dashboardPage($request, 'dashboard', 'Dashboard');
    }

    public function projectsPage(Request $request): View
    {
        return $this->dashboardPage($request, 'projects', 'Projects');
    }

    public function projectCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'project-create', 'Tambah Project');
    }

    public function projectEditPage(Request $request, Project $project): View
    {
        return $this->dashboardPage($request, 'project-edit', 'Edit Project')->with('editingProject', $project);
    }

    public function salesPage(Request $request): View
    {
        return $this->dashboardPage($request, 'sales', 'Proposal List');
    }

    public function proposalCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'proposal-create', 'Tambah Proposal');
    }

    public function proposalEditPage(Request $request, Proposal $proposal): View
    {
        return $this->dashboardPage($request, 'proposal-edit', 'Edit Proposal')->with('editingProposal', $proposal);
    }

    public function hrPage(Request $request): View
    {
        return $this->dashboardPage($request, 'employees', 'Employees');
    }

    public function salariesPage(Request $request): View
    {
        return $this->dashboardPage($request, 'salaries', 'Salary & Payroll');
    }

    public function employeeCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'employee-create', 'Tambah Karyawan');
    }

    public function employeeEditPage(Request $request, Employee $employee): View
    {
        return $this->dashboardPage($request, 'employee-edit', 'Edit Karyawan')->with('editingEmployee', $employee);
    }

    public function salaryCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'salary-create', 'Tambah Salary');
    }

    public function salaryEditPage(Request $request, Salary $salary): View
    {
        return $this->dashboardPage($request, 'salary-edit', 'Edit Salary')->with('editingSalary', $salary);
    }

    public function financePage(Request $request): View
    {
        return $this->dashboardPage($request, 'invoices', 'Invoice & Payment');
    }

    public function reimbursementsPage(Request $request): View
    {
        return $this->dashboardPage($request, 'reimbursements', 'Reimbursement');
    }

    public function cashflowsPage(Request $request): View
    {
        return $this->dashboardPage($request, 'cashflows', 'Cashflow & Costing');
    }

    public function reimbursementCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'reimbursement-create', 'Tambah Reimbursement');
    }

    public function reimbursementEditPage(Request $request, Reimbursement $reimbursement): View
    {
        return $this->dashboardPage($request, 'reimbursement-edit', 'Edit Reimbursement')->with('editingReimbursement', $reimbursement);
    }

    public function cashflowCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'cashflow-create', 'Tambah Cashflow');
    }

    public function cashflowEditPage(Request $request, Cashflow $cashflow): View
    {
        return $this->dashboardPage($request, 'cashflow-edit', 'Edit Cashflow')->with('editingCashflow', $cashflow);
    }

    public function invoiceCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'invoice-create', 'Buat Invoice');
    }

    public function invoiceEditPage(Request $request, Invoice $invoice): View
    {
        return $this->dashboardPage($request, 'invoice-edit', 'Edit Invoice')->with('editingInvoice', $invoice);
    }

    public function paymentCreatePage(Request $request): View
    {
        return $this->dashboardPage($request, 'payment-create', 'Catat Payment');
    }

    public function reportsPage(Request $request): View
    {
        return $this->dashboardPage($request, 'reports', 'Reports');
    }

    public function adminPage(Request $request): View
    {
        return $this->dashboardPage($request, 'company', 'Company Setting');
    }

    public function usersPage(Request $request): View
    {
        return $this->dashboardPage($request, 'users', 'User Management');
    }

    public function mastersPage(Request $request): View
    {
        return $this->dashboardPage($request, 'masters', 'Master Data');
    }

    public function trashPage(Request $request): View
    {
        return $this->dashboardPage($request, 'trash', 'Trash Restore');
    }

    public function auditPage(Request $request): View
    {
        return $this->dashboardPage($request, 'audit', 'Audit Log');
    }

    private function dashboardPage(Request $request, string $activePage, string $pageTitle): View
    {
        // ── Input sanitization (security) ───────────────────────────────────
        $selectedProjectId = $request->integer('project_id') ?: null;
        $q = $request->filled('q') ? (string) $request->string('q') : null;

        $allowedProposalStatuses     = ['draft', 'sent', 'approved', 'rejected'];
        $allowedInvoiceStatuses      = ['draft', 'sent', 'partial', 'paid', 'void'];
        $allowedSalaryStatuses       = ['draft', 'approved', 'paid'];
        $allowedReimbursementStatuses = ['pending', 'approved', 'paid', 'rejected'];

        $proposalStatus     = $request->filled('proposal_status') && in_array($request->proposal_status, $allowedProposalStatuses, true)
            ? $request->proposal_status : null;
        $invoiceStatus      = $request->filled('invoice_status') && in_array($request->invoice_status, $allowedInvoiceStatuses, true)
            ? $request->invoice_status : null;
        $salaryStatus       = $request->filled('salary_status') && in_array($request->salary_status, $allowedSalaryStatuses, true)
            ? $request->salary_status : null;
        $reimbursementStatus = $request->filled('reimbursement_status') && in_array($request->reimbursement_status, $allowedReimbursementStatuses, true)
            ? $request->reimbursement_status : null;

        // ── Cashflow query (filtered) ────────────────────────────────────────
        $cashflowQuery = Cashflow::query()->with(['project:id,code', 'bankAccount:id,name'])->latest('transaction_date');

        if ($selectedProjectId) {
            $cashflowQuery->where('project_id', $selectedProjectId);
        }
        if ($q) {
            $cashflowQuery->where(function ($query) use ($q) {
                $query->where('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('vendor', 'like', "%{$q}%");
            });
        }
        if ($request->filled('date_from')) {
            $cashflowQuery->whereDate('transaction_date', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $cashflowQuery->whereDate('transaction_date', '<=', $request->date('date_to'));
        }

        $cashflows     = (clone $cashflowQuery)->get();
        $cashflowPages = (clone $cashflowQuery)->paginate(15, ['*'], 'cashflows_page')->withQueryString();

        // ── Projects (full list for dropdowns + paginated for table) ────────
        $projectBaseQuery = Project::query()
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%")
                ->orWhere('client', 'like', "%{$q}%"))
            ->latest();

        $projects = (clone $projectBaseQuery)->with(['clientRecord'])->get();

        // Load heavy relations only when needed for reports/dashboard
        if (in_array($activePage, ['dashboard', 'projects', 'reports'], true)) {
            $projects->load(['cashflows', 'proposals', 'invoices']);
        }

        $projectsPage = (clone $projectBaseQuery)
            ->with(['clientRecord'])
            ->paginate(15, ['*'], 'projects_page')
            ->withQueryString();

        // ── Employees ───────────────────────────────────────────────────────
        $employees = Employee::query()
            ->with(['departmentRecord:id,name', 'jobPosition:id,name'])
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->get();

        $employeesPage = Employee::query()
            ->with(['departmentRecord:id,name', 'jobPosition:id,name'])
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(15, ['*'], 'employees_page')
            ->withQueryString();

        // ── Proposals ───────────────────────────────────────────────────────
        $proposals = Proposal::query()
            ->with('project:id,code,name')
            ->when($proposalStatus, fn ($query) => $query->where('status', $proposalStatus))
            ->latest()
            ->get();

        $proposalsPage = Proposal::query()
            ->with('project:id,code,name')
            ->when($proposalStatus, fn ($query) => $query->where('status', $proposalStatus))
            ->latest()
            ->paginate(15, ['*'], 'proposals_page')
            ->withQueryString();

        // ── Salaries ────────────────────────────────────────────────────────
        $salaries = Salary::query()
            ->with(['employee:id,name', 'project:id,code'])
            ->when($salaryStatus, fn ($query) => $query->where('status', $salaryStatus))
            ->latest()
            ->get();

        $salariesPage = Salary::query()
            ->with(['employee:id,name', 'project:id,code'])
            ->when($salaryStatus, fn ($query) => $query->where('status', $salaryStatus))
            ->latest()
            ->paginate(15, ['*'], 'salaries_page')
            ->withQueryString();

        // ── Reimbursements ───────────────────────────────────────────────────
        $reimbursements = Reimbursement::query()
            ->with(['employee:id,name', 'project:id,code'])
            ->when($reimbursementStatus, fn ($query) => $query->where('status', $reimbursementStatus))
            ->latest()
            ->get();

        $reimbursementsPage = Reimbursement::query()
            ->with(['employee:id,name', 'project:id,code'])
            ->when($reimbursementStatus, fn ($query) => $query->where('status', $reimbursementStatus))
            ->latest()
            ->paginate(15, ['*'], 'reimbursements_page')
            ->withQueryString();

        // ── Invoices ─────────────────────────────────────────────────────────
        $invoices = Invoice::query()
            ->with(['project:id,code', 'proposal:id,title', 'payments'])
            ->when($invoiceStatus, fn ($query) => $query->where('status', $invoiceStatus))
            ->latest()
            ->get();

        $invoicesPage = Invoice::query()
            ->with(['project:id,code', 'payments'])
            ->when($invoiceStatus, fn ($query) => $query->where('status', $invoiceStatus))
            ->latest()
            ->paginate(15, ['*'], 'invoices_page')
            ->withQueryString();

        // ── Other data ───────────────────────────────────────────────────────
        $payments         = Payment::query()->with(['invoice.project:id,code', 'bankAccount:id,name'])->latest('payment_date')->paginate(15, ['*'], 'payments_page')->withQueryString();
        $clients          = Client::orderBy('name')->get();
        $departments      = Department::orderBy('name')->get();
        $jobPositions     = JobPosition::orderBy('name')->get();
        $expenseCategories = ExpenseCategory::orderBy('name')->get();
        $bankAccounts     = BankAccount::orderBy('name')->get();
        $auditLogs        = AuditLog::with('user:id,name')->latest()->paginate(15, ['*'], 'audit_page')->withQueryString();
        $users            = User::withTrashed()->latest()->get();
        $companySetting   = CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $trash = [
            'projects'  => Project::onlyTrashed()->latest()->limit(10)->get(),
            'proposals' => Proposal::onlyTrashed()->latest()->limit(10)->get(),
            'invoices'  => Invoice::onlyTrashed()->latest()->limit(10)->get(),
        ];

        // ── Summary & month cashflows ────────────────────────────────────────
        $summary = $this->cashflowSummary($cashflows);
        $monthCashflows = Cashflow::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->get();

        $dashboard = [
            'month_income'           => $monthCashflows->where('type', 'income')->sum('amount'),
            'month_expense'          => $monthCashflows->where('type', 'expense')->sum('amount'),
            'outstanding_invoice'    => $invoices->sum(fn (Invoice $invoice) => max(0, $invoice->amount - $invoice->paid_amount)),
            'pending_reimbursement'  => $reimbursements->where('status', 'pending')->sum('amount'),
            'month_payroll'          => $salaries->filter(fn (Salary $salary) => str_starts_with($salary->period, now()->format('Y-m')))->sum('net_salary'),
            'total_projects'         => $projects->count(),
            'active_projects'        => $projects->where('status', 'active')->count(),
        ];

        // ── Monthly chart — single query, group in PHP ───────────────────────
        $sixMonthsStart   = now()->subMonths(5)->startOfMonth()->toDateString();
        $sixMonthRawFlows = Cashflow::select('transaction_date', 'type', 'amount')
            ->where('transaction_date', '>=', $sixMonthsStart)
            ->get();

        $monthlyChart = collect(range(5, 0))->map(function (int $monthsAgo) use ($sixMonthRawFlows) {
            $month    = now()->subMonths($monthsAgo);
            $monthKey = $month->format('Y-m');
            $flows    = $sixMonthRawFlows->filter(fn ($f) => str_starts_with((string) $f->transaction_date, $monthKey));

            return [
                'label'   => $month->format('M'),
                'income'  => (float) $flows->where('type', 'income')->sum('amount'),
                'expense' => (float) $flows->where('type', 'expense')->sum('amount'),
            ];
        });
        $monthlyMax = max(1, $monthlyChart->max(fn (array $row) => max($row['income'], $row['expense'])));

        // ── Filter quick-stat counts ─────────────────────────────────────────
        $filterCounts = [
            'projects_total'          => Project::count(),
            'projects_active'         => Project::where('status', 'active')->count(),
            'proposals_pending'       => Proposal::where('status', 'sent')->count(),
            'invoices_outstanding'    => Invoice::whereNotIn('status', ['paid', 'void'])->count(),
            'employees_total'         => Employee::count(),
            'reimbursements_pending'  => Reimbursement::where('status', 'pending')->count(),
        ];

        // ── Notifications ────────────────────────────────────────────────────
        $notifications = collect([
            ['type' => 'Reimbursement pending', 'count' => $reimbursements->where('status', 'pending')->count(), 'message' => 'Butuh approval finance/admin.', 'danger' => false],
            ['type' => 'Invoice jatuh tempo', 'count' => $invoices->filter(fn (Invoice $invoice) => $invoice->status !== 'paid' && $invoice->due_date && now()->parse($invoice->due_date)->lte(now()->addDays(7)))->count(), 'message' => 'Jatuh tempo dalam 7 hari.', 'danger' => true],
            ['type' => 'Project over budget', 'count' => $projects->filter(fn (Project $project) => $project->budget > 0 && $project->cashflows->where('type', 'expense')->sum('amount') > $project->budget)->count(), 'message' => 'Expense melewati budget.', 'danger' => true],
            ['type' => 'Salary belum dibayar', 'count' => $salaries->where('status', 'approved')->count(), 'message' => 'Menunggu finance untuk paid.', 'danger' => false],
            ['type' => 'Proposal menunggu', 'count' => $proposals->where('status', 'sent')->count(), 'message' => 'Menunggu sales lead/admin.', 'danger' => false],
        ])->filter(fn ($item) => $item['count'] > 0);

        // ── Project reports ───────────────────────────────────────────────────
        $projectReports = $projects->map(function (Project $project) {
            $summary      = $this->cashflowSummary($project->cashflows);
            $profitMargin = $summary['income'] > 0 ? (($summary['balance'] / $summary['income']) * 100) : 0;

            return [
                'project'             => $project,
                'summary'             => $summary,
                'proposal_total'      => $project->proposals->sum('amount'),
                'invoice_total'       => $project->invoices->sum('amount'),
                'salary_total'        => $project->salaries()->sum('net_salary'),
                'reimbursement_total' => $project->reimbursements()->sum('amount'),
                'profit_margin'       => $profitMargin,
            ];
        });
        $projectChart = $projectReports
            ->sortByDesc(fn (array $report) => abs($report['summary']['balance']))
            ->take(5)
            ->values();
        $projectMax = max(1, $projectChart->max(fn (array $report) => max($report['summary']['income'], $report['summary']['expense'])));

        // ── Expense breakdown ─────────────────────────────────────────────────
        $expenseTotal     = max(1, $summary['expense']);
        $expenseBreakdown = $cashflows
            ->where('type', 'expense')
            ->groupBy('cost_type')
            ->map(fn (Collection $flows, string $type) => [
                'type'    => $type ?: 'operational',
                'amount'  => (float) $flows->sum('amount'),
                'percent' => round(($flows->sum('amount') / $expenseTotal) * 100, 1),
            ])
            ->sortByDesc('amount')
            ->values();

        return view('erp.dashboard', compact(
            'activePage',
            'auditLogs',
            'bankAccounts',
            'cashflowPages',
            'cashflows',
            'clients',
            'companySetting',
            'dashboard',
            'departments',
            'employees',
            'employeesPage',
            'expenseBreakdown',
            'expenseCategories',
            'filterCounts',
            'invoices',
            'invoicesPage',
            'jobPositions',
            'monthlyChart',
            'monthlyMax',
            'notifications',
            'pageTitle',
            'payments',
            'projectChart',
            'projectMax',
            'projectReports',
            'projects',
            'projectsPage',
            'proposals',
            'proposalsPage',
            'reimbursements',
            'reimbursementsPage',
            'salaries',
            'salariesPage',
            'selectedProjectId',
            'summary',
            'trash',
            'users',
        ));
    }

    public function project(Project $project): View
    {
        $project->load(['clientRecord', 'cashflows', 'proposals', 'invoices.payments', 'salaries.employee', 'reimbursements.employee']);
        $summary = $this->cashflowSummary($project->cashflows);
        $profitMargin = $summary['income'] > 0 ? (($summary['balance'] / $summary['income']) * 100) : 0;
        $timeline = collect()
            ->merge($project->proposals->map(fn ($item) => ['date' => $item->created_at, 'label' => 'Proposal dibuat', 'desc' => $item->number.' '.$item->title]))
            ->merge($project->invoices->map(fn ($item) => ['date' => $item->created_at, 'label' => 'Invoice dibuat', 'desc' => $item->number]))
            ->merge($project->invoices->flatMap->payments->map(fn ($item) => ['date' => $item->created_at, 'label' => 'Payment masuk', 'desc' => $item->reference.' Rp '.number_format($item->amount, 0, ',', '.')]))
            ->merge($project->cashflows->map(fn ($item) => ['date' => $item->created_at, 'label' => 'Cost/cashflow ditambahkan', 'desc' => $item->category.' '.$item->description]))
            ->merge($project->reimbursements->where('status', 'paid')->map(fn ($item) => ['date' => $item->updated_at, 'label' => 'Reimbursement paid', 'desc' => $item->employee->name.' '.$item->category]))
            ->merge($project->salaries->where('status', 'paid')->map(fn ($item) => ['date' => $item->updated_at, 'label' => 'Salary paid', 'desc' => $item->employee->name.' '.$item->period]))
            ->sortByDesc('date')
            ->values();

        return view('erp.project-detail', compact('project', 'summary', 'profitMargin', 'timeline'));
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $data = $request->validate($this->projectRules());
        $data['code'] = $data['code'] ?: $this->nextNumber('NX-PRJ', Project::withTrashed()->count() + 1);
        $data['client'] = Client::find($data['client_id'])?->name ?? $data['client'];
        $data['contract_file_path'] = $this->storeUpload($request, 'contract_file', 'contracts');
        unset($data['contract_file']);
        $project = Project::create($data);
        $this->audit('created', $project, 'Project dibuat');

        return back()->with('status', 'Project berhasil dibuat.');
    }

    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate($this->projectRules($project));
        $data['client'] = Client::find($data['client_id'])?->name ?? $data['client'];
        $data['contract_file_path'] = $this->storeUpload($request, 'contract_file', 'contracts') ?? $project->contract_file_path;
        unset($data['contract_file']);
        $old = $project->toArray();
        $project->update($data);
        $this->audit('updated', $project, 'Project diedit', $old, $project->fresh()->toArray());

        return back()->with('status', 'Project berhasil diupdate.');
    }

    public function destroyProject(Project $project): RedirectResponse
    {
        $this->audit('deleted', $project, 'Project dihapus', $project->toArray());
        $project->delete();

        return back()->with('status', 'Project berhasil dihapus.');
    }

    public function storeProposal(Request $request): RedirectResponse
    {
        $data = $request->validate($this->proposalRules());
        $data['number'] = $data['number'] ?: $this->nextNumber('PRP-NX', Proposal::withTrashed()->count() + 1);
        $data['signed_file_path'] = $this->storeUpload($request, 'signed_file', 'proposals');
        unset($data['signed_file']);
        $proposal = Proposal::create($data);
        $this->audit('created', $proposal, 'Proposal dibuat');

        return back()->with('status', 'Proposal berhasil dicatat.');
    }

    public function updateProposal(Request $request, Proposal $proposal): RedirectResponse
    {
        $old = $proposal->toArray();
        $data = $request->validate($this->proposalRules());
        $data['signed_file_path'] = $this->storeUpload($request, 'signed_file', 'proposals') ?? $proposal->signed_file_path;
        unset($data['signed_file']);
        $proposal->update($data);
        $this->audit('updated', $proposal, 'Proposal diedit', $old, $proposal->fresh()->toArray());

        return back()->with('status', 'Proposal berhasil diupdate.');
    }

    public function updateProposalStatus(Request $request, Proposal $proposal): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['draft', 'sent', 'approved', 'rejected'])]]);
        if (in_array($data['status'], ['approved', 'rejected'], true) && ! in_array(auth()->user()->role, ['admin', 'sales'], true)) {
            abort(403, 'Hanya sales/admin yang bisa approve proposal.');
        }
        $old = $proposal->toArray();
        $proposal->update($data);
        $this->audit('status_updated', $proposal, 'Status proposal menjadi '.$proposal->status, $old, $proposal->fresh()->toArray());

        return back()->with('status', 'Status proposal berhasil diupdate.');
    }

    public function destroyProposal(Proposal $proposal): RedirectResponse
    {
        $this->audit('deleted', $proposal, 'Proposal dihapus', $proposal->toArray());
        $proposal->delete();

        return back()->with('status', 'Proposal berhasil dihapus.');
    }

    public function proposalPdf(Proposal $proposal): View
    {
        $proposal->load('project.clientRecord');

        return view('erp.proposal-print', compact('proposal'));
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $data = $request->validate($this->employeeRules());
        $data = $this->hydrateEmployeeLabels($data);
        $employee = Employee::create($data);
        $this->audit('created', $employee, 'Karyawan dibuat');

        return back()->with('status', 'Karyawan berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $old = $employee->toArray();
        $employee->update($this->hydrateEmployeeLabels($request->validate($this->employeeRules())));
        $this->audit('updated', $employee, 'Karyawan diedit', $old, $employee->fresh()->toArray());

        return back()->with('status', 'Karyawan berhasil diupdate.');
    }

    public function destroyEmployee(Employee $employee): RedirectResponse
    {
        $this->audit('deleted', $employee, 'Karyawan dihapus', $employee->toArray());
        $employee->delete();

        return back()->with('status', 'Karyawan berhasil dihapus.');
    }

    public function storeSalary(Request $request): RedirectResponse
    {
        $data = $request->validate($this->salaryRules());
        $data['allowance'] = $data['allowance'] ?? 0;
        $data['deduction'] = $data['deduction'] ?? 0;
        $data['net_salary'] = $data['base_salary'] + $data['allowance'] - $data['deduction'];
        $data['slip_number'] = $this->nextNumber('SLP-NX', Salary::withTrashed()->count() + 1);
        $salary = Salary::create($data);
        $this->audit('created', $salary, 'Salary dibuat');

        return back()->with('status', 'Salary berhasil disimpan sebagai draft.');
    }

    public function updateSalary(Request $request, Salary $salary): RedirectResponse
    {
        $data = $request->validate($this->salaryRules($salary));
        $data['allowance'] = $data['allowance'] ?? 0;
        $data['deduction'] = $data['deduction'] ?? 0;
        $data['net_salary'] = $data['base_salary'] + $data['allowance'] - $data['deduction'];
        $old = $salary->toArray();
        $salary->update($data);
        $this->audit('updated', $salary, 'Salary diedit', $old, $salary->fresh()->toArray());

        return back()->with('status', 'Salary berhasil diupdate.');
    }

    public function updateSalaryStatus(Request $request, Salary $salary): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['draft', 'approved', 'paid'])]]);
        if ($data['status'] === 'approved' && ! in_array(auth()->user()->role, ['admin', 'hr'], true)) {
            abort(403, 'Hanya HR/admin yang bisa approve salary.');
        }
        if ($data['status'] === 'paid' && ! in_array(auth()->user()->role, ['admin', 'finance'], true)) {
            abort(403, 'Hanya finance/admin yang bisa paid salary.');
        }
        $old = $salary->toArray();
        $salary->update($data);
        if ($salary->status === 'paid') {
            $this->ensureSalaryCashflow($salary);
        }
        $this->audit('status_updated', $salary, 'Status salary menjadi '.$salary->status, $old, $salary->fresh()->toArray());

        return back()->with('status', 'Status salary berhasil diupdate.');
    }

    public function destroySalary(Salary $salary): RedirectResponse
    {
        $this->audit('deleted', $salary, 'Salary dihapus', $salary->toArray());
        $salary->delete();

        return back()->with('status', 'Salary berhasil dihapus.');
    }

    public function salaryPdf(Salary $salary): View
    {
        $salary->load(['employee', 'project']);

        return view('erp.salary-print', compact('salary'));
    }

    public function storeReimbursement(Request $request): RedirectResponse
    {
        $data = $request->validate($this->reimbursementRules());
        $data['receipt_file_path'] = $this->storeUpload($request, 'receipt_file', 'reimbursements');
        unset($data['receipt_file']);
        $reimbursement = Reimbursement::create($data);
        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }
        $this->audit('created', $reimbursement, 'Reimbursement dibuat');

        return back()->with('status', 'Reimbursement berhasil dicatat.');
    }

    public function updateReimbursement(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $old = $reimbursement->toArray();
        $data = $request->validate($this->reimbursementRules());
        $data['receipt_file_path'] = $this->storeUpload($request, 'receipt_file', 'reimbursements') ?? $reimbursement->receipt_file_path;
        unset($data['receipt_file']);
        $reimbursement->update($data);
        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }
        $this->audit('updated', $reimbursement, 'Reimbursement diedit', $old, $reimbursement->fresh()->toArray());

        return back()->with('status', 'Reimbursement berhasil diupdate.');
    }

    public function updateReimbursementStatus(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'approved', 'paid', 'rejected'])]]);
        if ($data['status'] === 'approved' && ! in_array(auth()->user()->role, ['admin', 'finance'], true)) {
            abort(403, 'Hanya finance/admin yang bisa approve reimbursement.');
        }
        if ($data['status'] === 'paid' && ! in_array(auth()->user()->role, ['admin', 'finance'], true)) {
            abort(403, 'Hanya finance/admin yang bisa paid reimbursement.');
        }
        $old = $reimbursement->toArray();
        $reimbursement->update($data);
        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }
        $this->audit('status_updated', $reimbursement, 'Status reimbursement menjadi '.$reimbursement->status, $old, $reimbursement->fresh()->toArray());

        return back()->with('status', 'Status reimbursement berhasil diupdate.');
    }

    public function destroyReimbursement(Reimbursement $reimbursement): RedirectResponse
    {
        $this->audit('deleted', $reimbursement, 'Reimbursement dihapus', $reimbursement->toArray());
        $reimbursement->delete();

        return back()->with('status', 'Reimbursement berhasil dihapus.');
    }

    public function storeCashflow(Request $request): RedirectResponse
    {
        $cashflow = Cashflow::create($request->validate($this->cashflowRules()));
        $this->audit('created', $cashflow, 'Cashflow dibuat');

        return back()->with('status', 'Cashflow berhasil dicatat.');
    }

    public function updateCashflow(Request $request, Cashflow $cashflow): RedirectResponse
    {
        $old = $cashflow->toArray();
        $cashflow->update($request->validate($this->cashflowRules()));
        $this->audit('updated', $cashflow, 'Cashflow diedit', $old, $cashflow->fresh()->toArray());

        return back()->with('status', 'Cashflow berhasil diupdate.');
    }

    public function destroyCashflow(Cashflow $cashflow): RedirectResponse
    {
        $this->audit('deleted', $cashflow, 'Cashflow dihapus', $cashflow->toArray());
        $cashflow->delete();

        return back()->with('status', 'Cashflow berhasil dihapus.');
    }

    public function storeInvoice(Request $request): RedirectResponse
    {
        $data = $request->validate($this->invoiceRules());
        $data['number'] = $data['number'] ?: $this->nextNumber('INV-NX', Invoice::withTrashed()->count() + 1);
        if (! empty($data['proposal_id'])) {
            $proposal = Proposal::findOrFail($data['proposal_id']);
            if ($proposal->status !== 'approved') {
                return back()->withErrors(['proposal_id' => 'Invoice hanya bisa dibuat dari proposal approved.'])->withInput();
            }
        }
        $invoice = Invoice::create($data);
        $this->audit('created', $invoice, 'Invoice dibuat');

        return back()->with('status', 'Invoice berhasil dibuat.');
    }

    public function updateInvoice(Request $request, Invoice $invoice): RedirectResponse
    {
        $old = $invoice->toArray();
        $invoice->update($request->validate($this->invoiceRules($invoice)));
        $this->audit('updated', $invoice, 'Invoice diedit', $old, $invoice->fresh()->toArray());

        return back()->with('status', 'Invoice berhasil diupdate.');
    }

    public function destroyInvoice(Invoice $invoice): RedirectResponse
    {
        $this->audit('deleted', $invoice, 'Invoice dihapus', $invoice->toArray());
        $invoice->delete();

        return back()->with('status', 'Invoice berhasil dihapus.');
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $data = $request->validate($this->paymentRules());
        $data['reference'] = $data['reference'] ?: $this->nextNumber('PAY-NX', Payment::withTrashed()->count() + 1);
        $data['proof_file_path'] = $this->storeUpload($request, 'proof_file', 'payments');
        unset($data['proof_file']);
        $invoice = Invoice::with('project')->findOrFail($data['invoice_id']);
        $remaining = max(0, $invoice->amount - $invoice->paid_amount);
        if ($data['amount'] > $remaining) {
            return back()->withErrors(['amount' => 'Payment melebihi sisa invoice. Sisa: Rp '.number_format($remaining, 0, ',', '.')])->withInput();
        }

        $cashflow = Cashflow::create([
            'project_id' => $invoice->project_id,
            'type' => 'income',
            'category' => 'Invoice Payment',
            'bank_account_id' => $data['bank_account_id'] ?? null,
            'cost_type' => 'client_payment',
            'description' => 'Payment invoice '.$invoice->number,
            'amount' => $data['amount'],
            'transaction_date' => $data['payment_date'],
        ]);
        $payment = Payment::create($data + ['cashflow_id' => $cashflow->id]);
        $this->refreshInvoiceStatus($invoice);
        $this->audit('created', $payment, 'Payment invoice dibuat dan masuk cashflow');

        return back()->with('status', 'Payment berhasil dicatat dan otomatis masuk cashflow income.');
    }

    public function destroyPayment(Payment $payment): RedirectResponse
    {
        $invoice = $payment->invoice;
        $this->audit('deleted', $payment, 'Payment dihapus', $payment->toArray());
        $payment->cashflow_id ? Cashflow::find($payment->cashflow_id)?->delete() : null;
        $payment->delete();
        $this->refreshInvoiceStatus($invoice);

        return back()->with('status', 'Payment berhasil dihapus.');
    }

    public function invoicePdf(Invoice $invoice): View
    {
        $invoice->load(['project.clientRecord', 'payments.bankAccount']);
        $companySetting = CompanySetting::with('defaultBankAccount')->firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);

        return view('erp.invoice-print', compact('invoice', 'companySetting'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'password' => ['nullable', 'min:8'],
        ]);
        $plain = $data['password'] ?: 'Nexora-'.random_int(100000, 999999);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($plain),
            'must_change_password' => true,
            'is_active' => true,
        ]);
        $this->audit('created', $user, 'User dibuat');

        return back()->with('temporary_password', 'Password user baru: '.$plain);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'is_active' => ['nullable', 'boolean'],
            'must_change_password' => ['nullable', 'boolean'],
        ]);
        $old = $user->toArray();
        $user->update($data + ['is_active' => false, 'must_change_password' => false]);
        $this->audit('updated', $user, 'User diupdate', $old, $user->fresh()->toArray());

        return back()->with('status', 'User berhasil diupdate.');
    }

    public function resetUserPassword(User $user): RedirectResponse
    {
        $plain = 'Nexora-'.random_int(100000, 999999);
        $user->forceFill([
            'password' => Hash::make($plain),
            'must_change_password' => true,
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();
        $this->audit('password_reset', $user, 'Password user direset');

        return back()->with('temporary_password', 'Password sementara: '.$plain);
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 422, 'Tidak bisa hapus user yang sedang login.');
        $this->audit('deleted', $user, 'User dihapus', $user->toArray());
        $user->delete();

        return back()->with('status', 'User berhasil dinonaktifkan/dihapus.');
    }

    public function updateCompanySetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'max:255'],
            'address' => ['nullable'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:50'],
            'npwp' => ['nullable', 'max:100'],
            'signature_name' => ['nullable', 'max:255'],
            'default_bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'logo' => ['nullable', 'file', 'max:2048'],
        ]);
        $setting = CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $data['logo_path'] = $this->storeUpload($request, 'logo', 'company') ?? $setting->logo_path;
        unset($data['logo']);
        $setting->update($data);
        $this->audit('updated', $setting, 'Company setting diupdate');

        return back()->with('status', 'Setting perusahaan berhasil disimpan.');
    }

    public function backupDatabase()
    {
        $path = database_path('database.sqlite');
        abort_unless(file_exists($path), 404);

        return response()->download($path, 'nexora-backup-'.now()->format('Ymd-His').'.sqlite');
    }

    public function restoreTrash(string $type, int $id): RedirectResponse
    {
        $models = [
            'projects' => Project::class,
            'proposals' => Proposal::class,
            'invoices' => Invoice::class,
            'users' => User::class,
        ];
        abort_unless(isset($models[$type]), 404);
        $model = $models[$type]::onlyTrashed()->findOrFail($id);
        $model->restore();
        $this->audit('restored', $model, 'Data '.$type.' direstore');

        return back()->with('status', 'Data berhasil direstore.');
    }

    public function storeMaster(Request $request, string $type): RedirectResponse
    {
        $models = $this->masterModels();
        abort_unless(isset($models[$type]), 404);
        $rules = ['name' => ['required', 'max:255', Rule::unique($type, 'name')]];
        $extra = [];
        if ($type === 'clients') {
            $extra = $request->validate($rules + [
                'contact_name' => ['nullable', 'max:255'],
                'email' => ['nullable', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable'],
            ]);
        } elseif ($type === 'bank_accounts') {
            $extra = $request->validate($rules + [
                'bank_name' => ['nullable', 'max:100'],
                'account_number' => ['nullable', 'max:100'],
                'opening_balance' => ['nullable', 'numeric', 'min:0'],
            ]);
        } elseif ($type === 'expense_categories') {
            $extra = $request->validate($rules + ['type' => ['required', 'max:50']]);
        } else {
            $extra = $request->validate($rules);
        }

        $model = $models[$type]::create($extra);
        $this->audit('created', $model, 'Master data '.$type.' dibuat');

        return back()->with('status', 'Master data berhasil ditambahkan.');
    }

    public function destroyMaster(string $type, int $id): RedirectResponse
    {
        $models = $this->masterModels();
        abort_unless(isset($models[$type]), 404);
        $model = $models[$type]::findOrFail($id);
        $this->audit('deleted', $model, 'Master data '.$type.' dihapus', $model->toArray());
        $model->delete();

        return back()->with('status', 'Master data berhasil dihapus.');
    }

    public function exportCashflows(): StreamedResponse
    {
        return $this->csv('cashflows.csv', ['Tanggal', 'Project', 'Type', 'Category', 'Vendor', 'Amount', 'Description'], Cashflow::with('project')->orderBy('transaction_date')->get()->map(fn (Cashflow $flow) => [
            $flow->transaction_date,
            $flow->project?->code ?? 'Company',
            $flow->type,
            $flow->category,
            $flow->vendor,
            $flow->amount,
            $flow->description,
        ]));
    }

    public function exportProjectFinance(): StreamedResponse
    {
        $rows = Project::with('cashflows')->get()->map(function (Project $project) {
            $summary = $this->cashflowSummary($project->cashflows);
            $margin = $summary['income'] > 0 ? round(($summary['balance'] / $summary['income']) * 100, 2) : 0;

            return [$project->code, $project->name, $project->client, $project->contract_value, $summary['income'], $summary['expense'], $summary['balance'], $margin.'%'];
        });

        return $this->csv('project-finance.csv', ['Code', 'Project', 'Client', 'Contract', 'Income', 'Expense', 'Profit/Loss', 'Margin'], $rows);
    }

    private function projectRules(?Project $project = null): array
    {
        return [
            'code' => ['nullable', 'max:50', Rule::unique('projects', 'code')->ignore($project)],
            'name' => ['required', 'max:255'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'client' => ['nullable', 'max:255'],
            'status' => ['required', Rule::in(['planning', 'active', 'done', 'hold'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],
            'contract_file' => ['nullable', 'file', 'max:4096'],
        ];
    }

    private function proposalRules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'number' => ['nullable', 'max:100', Rule::unique('proposals', 'number')->ignore(request()->route('proposal'))],
            'title' => ['required', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'sent', 'approved', 'rejected'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'scope' => ['nullable'],
            'valid_until' => ['nullable', 'date'],
            'signed_file' => ['nullable', 'file', 'max:4096'],
        ];
    }

    private function employeeRules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'position' => ['nullable', 'max:255'],
            'job_position_id' => ['nullable', 'exists:job_positions,id'],
            'department' => ['nullable', 'max:100'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'base_salary' => ['required', 'numeric', 'min:0'],
        ];
    }

    private function salaryRules(?Salary $salary = null): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'period' => ['required', 'max:20', Rule::unique('salaries')->where(fn ($query) => $query->where('employee_id', request('employee_id')))->ignore($salary)],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'deduction' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', Rule::in(['draft', 'approved', 'paid'])],
        ];
    }

    private function reimbursementRules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'category' => ['required', 'max:100'],
            'description' => ['nullable'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'approved', 'paid', 'rejected'])],
            'expense_date' => ['required', 'date'],
            'receipt_file' => ['nullable', 'file', 'max:4096'],
        ];
    }

    private function cashflowRules(): array
    {
        return [
            'project_id' => ['nullable', 'exists:projects,id'],
            'type' => ['required', 'in:income,expense'],
            'category' => ['required', 'max:100'],
            'expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'cost_type' => ['required', Rule::in(['operational', 'salary', 'reimbursement', 'tools', 'cloud', 'vendor', 'subcontractor', 'client_payment'])],
            'vendor' => ['nullable', 'max:255'],
            'description' => ['required', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
        ];
    }

    private function invoiceRules(?Invoice $invoice = null): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'number' => ['nullable', 'max:100', Rule::unique('invoices', 'number')->ignore($invoice)],
            'status' => ['required', Rule::in(['draft', 'sent', 'partial', 'paid', 'void'])],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable'],
            'payment_terms' => ['nullable'],
        ];
    }

    private function paymentRules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date'],
            'method' => ['required', 'max:50'],
            'reference' => ['nullable', 'max:100'],
            'proof_file' => ['nullable', 'file', 'max:4096'],
        ];
    }

    private function hydrateEmployeeLabels(array $data): array
    {
        $data['department'] = Department::find($data['department_id'] ?? null)?->name ?? ($data['department'] ?? 'IT');
        $data['position'] = JobPosition::find($data['job_position_id'] ?? null)?->name ?? ($data['position'] ?? 'Staff');

        return $data;
    }

    private function ensureSalaryCashflow(Salary $salary): void
    {
        if ($salary->cashflow_id) {
            return;
        }

        $salary->load('employee');
        $cashflow = Cashflow::create([
            'project_id' => $salary->project_id,
            'type' => 'expense',
            'category' => 'Payroll',
            'cost_type' => 'salary',
            'description' => 'Gaji '.$salary->employee->name.' periode '.$salary->period,
            'amount' => $salary->net_salary,
            'transaction_date' => now()->toDateString(),
        ]);
        $salary->update(['cashflow_id' => $cashflow->id]);
    }

    private function ensureReimbursementCashflow(Reimbursement $reimbursement): void
    {
        if ($reimbursement->cashflow_id) {
            return;
        }

        $reimbursement->load('employee');
        $cashflow = Cashflow::create([
            'project_id' => $reimbursement->project_id,
            'type' => 'expense',
            'category' => 'Reimbursement',
            'cost_type' => 'reimbursement',
            'description' => $reimbursement->category.' - '.$reimbursement->employee->name,
            'amount' => $reimbursement->amount,
            'transaction_date' => $reimbursement->expense_date,
        ]);
        $reimbursement->update(['cashflow_id' => $cashflow->id]);
    }

    private function refreshInvoiceStatus(Invoice $invoice): void
    {
        $paid = $invoice->payments()->sum('amount');
        $status = $paid <= 0 ? $invoice->status : ($paid >= $invoice->amount ? 'paid' : 'partial');
        $invoice->update(['paid_amount' => $paid, 'status' => $status]);
    }

    private function cashflowSummary(Collection $cashflows): array
    {
        $income = $cashflows->where('type', 'income')->sum('amount');
        $expense = $cashflows->where('type', 'expense')->sum('amount');

        return ['income' => $income, 'expense' => $expense, 'balance' => $income - $expense];
    }

    private function audit(string $action, Model $model, string $description, ?array $old = null, ?array $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => class_basename($model),
            'auditable_id' => $model->getKey(),
            'description' => $description,
            'changes' => ['old' => $old, 'new' => $new],
        ]);
    }

    private function nextNumber(string $prefix, int $sequence): string
    {
        return $prefix.'-'.now()->format('Ym').'-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    private function storeUpload(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($folder, 'public');
    }

    private function masterModels(): array
    {
        return [
            'clients' => Client::class,
            'departments' => Department::class,
            'job_positions' => JobPosition::class,
            'expense_categories' => ExpenseCategory::class,
            'bank_accounts' => BankAccount::class,
        ];
    }

    private function csv(string $filename, array $header, Collection $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $header);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
