<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AccountingJournalController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientContractController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AllMenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeSkillController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\FeatureFlagController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FiscalPeriodController;
use App\Http\Controllers\PaymentTermController;
use App\Http\Controllers\BankReconciliationItemController;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\CurrencyVarianceController;
use App\Http\Controllers\FinanceAdvancedController;
use App\Http\Controllers\FinanceSuiteController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\PurchaseMatchController;
use App\Http\Controllers\RevenueScheduleController;
use App\Http\Controllers\ChartAccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\RecurringBillingController;
use App\Http\Controllers\PaymentReminderController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VendorBillController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TaxRuleController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\HrisController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollBenefitController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProcurementContractController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockLocationController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UnitOfMeasureController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\ReportAgingController;
use App\Http\Controllers\ReportBalanceSheetController;
use App\Http\Controllers\ReportBankReconciliationController;
use App\Http\Controllers\ReportBudgetVsActualController;
use App\Http\Controllers\ReportCashFlowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportProfitLossController;
use App\Http\Controllers\ReportProjectProfitabilityController;
use App\Http\Controllers\ReportTaxSummaryController;
use App\Http\Controllers\ReportTransactionsController;
use App\Http\Controllers\ReportTrialBalanceController;
use App\Http\Controllers\ReportGeneralLedgerController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesCommissionController;
use App\Http\Controllers\SalesCrmController;
use App\Http\Controllers\SalesInquiryController;
use App\Http\Controllers\SalesLeadController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesTargetController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('home');

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',          [AuthController::class, 'login'])->name('login.store');
Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',       [AuthController::class, 'register'])->name('register.store');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('password.forgot.store');

Route::middleware('auth')->middleware('feature')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/account', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password',  [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.store');

    // ── Dashboard & Sub-Dashboards ─────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/executive', [DashboardController::class, 'executive'])->name('dashboard.executive');
    Route::get('/dashboard/sales', [DashboardController::class, 'sales'])->name('dashboard.sales');
    Route::get('/dashboard/projects', [DashboardController::class, 'projects'])->name('dashboard.projects');
    Route::get('/dashboard/hr', [DashboardController::class, 'hr'])->name('dashboard.hr');
    Route::get('/dashboard/finance', [DashboardController::class, 'finance'])->name('dashboard.finance');
    Route::get('/dashboard/procurement', [DashboardController::class, 'procurement'])->name('dashboard.procurement');
    Route::get('/dashboard/inventory', [DashboardController::class, 'inventory'])->name('dashboard.inventory');
    Route::get('/dashboard/cms', [DashboardController::class, 'cms'])->name('dashboard.cms');
    Route::get('/dashboard/workspace', [DashboardController::class, 'workspace'])->name('dashboard.workspace');

    // ── All Menu ──────────────────────────────────────────────────────────────
    Route::get('/all-menu', [\App\Http\Controllers\AllMenuController::class, 'index'])->name('all-menu');
    Route::get('/modules', [\App\Http\Controllers\AllMenuController::class, 'index'])->name('modules.index');
    Route::get('/modules/{section}', [\App\Http\Controllers\AllMenuController::class, 'show'])->name('modules.show');
    Route::get('/approvals', [\App\Http\Controllers\AllMenuController::class, 'approvals'])->name('approvals.index');
    Route::get('/task-board', fn () => redirect()->route('approvals.index', ['tasks_only' => 1]))->name('task-board.index');
    Route::redirect('/settings-admin', '/admin')->name('settings-admin.index');
    Route::get('/admin', [CompanySettingController::class, 'hub'])->middleware('role:admin')->name('admin.index');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->middleware('role:admin')->name('admin.users');
    Route::get('/admin/masters', [ClientController::class, 'index'])->middleware('role:admin')->name('admin.masters');
    Route::get('/admin/trash', [TrashController::class, 'index'])->middleware('role:admin')->name('admin.trash');
    Route::get('/admin/audit', [AuditLogController::class, 'index'])->middleware('role:admin')->name('admin.audit');
    Route::post('/masters/{type}', [AdminController::class, 'storeMaster'])->middleware('role:admin')->name('masters.store');
    Route::delete('/masters/{type}/{id}', [AdminController::class, 'destroyMaster'])->middleware('role:admin')->name('masters.destroy');

    // ── Feature Flags (Pengaturan Fitur) ─────────────────────────────────────
    Route::get('/feature-flags', [FeatureFlagController::class, 'index'])->middleware('role:admin')->name('feature-flags.index');
    Route::get('/feature-flags/create', [FeatureFlagController::class, 'create'])->middleware('role:admin')->name('feature-flags.create-page');
    Route::get('/feature-flags/{flag}/edit', [FeatureFlagController::class, 'edit'])->middleware('role:admin')->name('feature-flags.edit-page');
    Route::post('/feature-flags', [FeatureFlagController::class, 'store'])->middleware('role:admin')->name('feature-flags.store');
    Route::post('/feature-flags/toggle', [FeatureFlagController::class, 'toggle'])->middleware('role:admin')->name('feature-flags.toggle');
    Route::put('/feature-flags/{flag}', [FeatureFlagController::class, 'update'])->middleware('role:admin')->name('feature-flags.update');
    Route::delete('/feature-flags/{flag}', [FeatureFlagController::class, 'destroy'])->middleware('role:admin')->name('feature-flags.destroy');
    Route::prefix('cms')->name('cms.')->middleware('role:admin')->group(function () {
        Route::get('/', [CmsController::class, 'index'])->name('index');
        Route::get('/sections', [CmsController::class, 'sections'])->name('sections.index');
        Route::get('/sections/create', [CmsController::class, 'createSection'])->name('sections.create');
        Route::post('/sections', [CmsController::class, 'storeSection'])->name('sections.store');
        Route::get('/sections/{section}/edit', [CmsController::class, 'editSection'])->name('sections.edit');
        Route::put('/sections/{section}', [CmsController::class, 'updateSection'])->name('sections.update');
        Route::delete('/sections/{section}', [CmsController::class, 'destroySection'])->name('sections.destroy');
        Route::get('/blog', [CmsController::class, 'blog'])->name('blog.index');
        Route::get('/blog/create', [CmsController::class, 'createPost'])->name('blog.create');
        Route::post('/blog', [CmsController::class, 'storePost'])->name('blog.store');
        Route::get('/blog/{post}/edit', [CmsController::class, 'editPost'])->name('blog.edit');
        Route::put('/blog/{post}', [CmsController::class, 'updatePost'])->name('blog.update');
        Route::delete('/blog/{post}', [CmsController::class, 'destroyPost'])->name('blog.destroy');
        Route::get('/api-settings', [CmsController::class, 'apiSettings'])->name('api-settings');
    });
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // ── Projects ──────────────────────────────────────────────────────────────
    Route::get('/projects',                     [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create',              [ProjectController::class, 'create'])->middleware('role:admin,sales')->name('projects.create-page');
    Route::get('/projects/{project}',           [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit',      [ProjectController::class, 'edit'])->middleware('role:admin,sales')->name('projects.edit-page');
    Route::post('/projects',                    [ProjectController::class, 'store'])->middleware('role:admin,sales')->name('projects.store');
    Route::put('/projects/{project}',           [ProjectController::class, 'update'])->middleware('role:admin,sales')->name('projects.update');
    Route::delete('/projects/{project}',        [ProjectController::class, 'destroy'])->middleware('role:admin')->name('projects.destroy');

    // ── Proposals / Sales ─────────────────────────────────────────────────────
    Route::get('/sales',                             [ProposalController::class, 'index'])->middleware('role:admin,sales')->name('sales.index');
    Route::get('/sales-crm',                         [SalesCrmController::class, 'index'])->middleware('role:admin,sales')->name('sales-crm.index');
    Route::post('/sales-crm/inquiries',              [SalesCrmController::class, 'storeInquiry'])->middleware('role:admin,sales')->name('sales-crm.inquiries.store');
    Route::post('/sales-crm/leads',                  [SalesCrmController::class, 'storeLead'])->middleware('role:admin,sales')->name('sales-crm.leads.store');
    Route::post('/sales-crm/orders',                 [SalesCrmController::class, 'storeOrder'])->middleware('role:admin,sales')->name('sales-crm.orders.store');
    Route::post('/sales-crm/targets',                [SalesCrmController::class, 'storeTarget'])->middleware('role:admin,sales')->name('sales-crm.targets.store');
    Route::post('/sales-crm/commissions',            [SalesCrmController::class, 'storeCommission'])->middleware('role:admin,sales')->name('sales-crm.commissions.store');
    Route::post('/sales-crm/contracts',              [SalesCrmController::class, 'storeContract'])->middleware('role:admin,sales')->name('sales-crm.contracts.store');

    // ── Sales: Inquiries ──────────────────────────────────────────────────────────
    Route::get('/sales-inquiries',                     [SalesInquiryController::class, 'index'])->middleware('role:admin,sales')->name('sales-inquiries.index');
    Route::get('/sales-inquiries/create',              [SalesInquiryController::class, 'create'])->middleware('role:admin,sales')->name('sales-inquiries.create-page');
    Route::get('/sales-inquiries/{inquiry}/edit',      [SalesInquiryController::class, 'edit'])->middleware('role:admin,sales')->name('sales-inquiries.edit-page');
    Route::post('/sales-inquiries',                    [SalesInquiryController::class, 'store'])->middleware('role:admin,sales')->name('sales-inquiries.store');
    Route::put('/sales-inquiries/{inquiry}',           [SalesInquiryController::class, 'update'])->middleware('role:admin,sales')->name('sales-inquiries.update');
    Route::delete('/sales-inquiries/{inquiry}',        [SalesInquiryController::class, 'destroy'])->middleware('role:admin')->name('sales-inquiries.destroy');

    // ── Sales: Leads ──────────────────────────────────────────────────────────────
    Route::get('/sales-leads',                         [SalesLeadController::class, 'index'])->middleware('role:admin,sales')->name('sales-leads.index');
    Route::get('/sales-leads/create',                  [SalesLeadController::class, 'create'])->middleware('role:admin,sales')->name('sales-leads.create-page');
    Route::get('/sales-leads/{lead}/edit',             [SalesLeadController::class, 'edit'])->middleware('role:admin,sales')->name('sales-leads.edit-page');
    Route::post('/sales-leads',                        [SalesLeadController::class, 'store'])->middleware('role:admin,sales')->name('sales-leads.store');
    Route::put('/sales-leads/{lead}',                  [SalesLeadController::class, 'update'])->middleware('role:admin,sales')->name('sales-leads.update');
    Route::delete('/sales-leads/{lead}',               [SalesLeadController::class, 'destroy'])->middleware('role:admin')->name('sales-leads.destroy');

    // ── Sales: Orders ─────────────────────────────────────────────────────────────
    Route::get('/sales-orders',                        [SalesOrderController::class, 'index'])->middleware('role:admin,sales')->name('sales-orders.index');
        Route::get('/sales-orders/{order}',                [SalesOrderController::class, 'show'])->middleware('role:admin,sales')->name('sales-orders.show');
Route::get('/sales-orders/create',                 [SalesOrderController::class, 'create'])->middleware('role:admin,sales')->name('sales-orders.create-page');
    Route::get('/sales-orders/{order}/edit',           [SalesOrderController::class, 'edit'])->middleware('role:admin,sales')->name('sales-orders.edit-page');
    Route::post('/sales-orders',                       [SalesOrderController::class, 'store'])->middleware('role:admin,sales')->name('sales-orders.store');
    Route::put('/sales-orders/{order}',                [SalesOrderController::class, 'update'])->middleware('role:admin,sales')->name('sales-orders.update');
    Route::delete('/sales-orders/{order}',             [SalesOrderController::class, 'destroy'])->middleware('role:admin')->name('sales-orders.destroy');

    // ── Sales: Targets ────────────────────────────────────────────────────────────
    Route::get('/sales-targets',                       [SalesTargetController::class, 'index'])->middleware('role:admin,sales')->name('sales-targets.index');
    Route::get('/sales-targets/create',                [SalesTargetController::class, 'create'])->middleware('role:admin,sales')->name('sales-targets.create-page');
    Route::get('/sales-targets/{target}/edit',         [SalesTargetController::class, 'edit'])->middleware('role:admin,sales')->name('sales-targets.edit-page');
    Route::post('/sales-targets',                      [SalesTargetController::class, 'store'])->middleware('role:admin,sales')->name('sales-targets.store');
    Route::put('/sales-targets/{target}',              [SalesTargetController::class, 'update'])->middleware('role:admin,sales')->name('sales-targets.update');
    Route::delete('/sales-targets/{target}',           [SalesTargetController::class, 'destroy'])->middleware('role:admin')->name('sales-targets.destroy');

    // ── Sales: Commissions ────────────────────────────────────────────────────────
    Route::get('/sales-commissions',                   [SalesCommissionController::class, 'index'])->middleware('role:admin,sales')->name('sales-commissions.index');
    Route::get('/sales-commissions/create',            [SalesCommissionController::class, 'create'])->middleware('role:admin,sales')->name('sales-commissions.create-page');
    Route::get('/sales-commissions/{commission}/edit', [SalesCommissionController::class, 'edit'])->middleware('role:admin,sales')->name('sales-commissions.edit-page');
    Route::post('/sales-commissions',                  [SalesCommissionController::class, 'store'])->middleware('role:admin,sales')->name('sales-commissions.store');
    Route::put('/sales-commissions/{commission}',      [SalesCommissionController::class, 'update'])->middleware('role:admin,sales')->name('sales-commissions.update');
    Route::delete('/sales-commissions/{commission}',   [SalesCommissionController::class, 'destroy'])->middleware('role:admin')->name('sales-commissions.destroy');

    // ── Sales: Client Contracts ───────────────────────────────────────────────────
    Route::get('/client-contracts',                    [ClientContractController::class, 'index'])->middleware('role:admin,sales')->name('client-contracts.index');
    Route::get('/client-contracts/create',             [ClientContractController::class, 'create'])->middleware('role:admin,sales')->name('client-contracts.create-page');
    Route::get('/client-contracts/{contract}/edit',    [ClientContractController::class, 'edit'])->middleware('role:admin,sales')->name('client-contracts.edit-page');
    Route::post('/client-contracts',                   [ClientContractController::class, 'store'])->middleware('role:admin,sales')->name('client-contracts.store');
    Route::put('/client-contracts/{contract}',         [ClientContractController::class, 'update'])->middleware('role:admin,sales')->name('client-contracts.update');
    Route::delete('/client-contracts/{contract}',      [ClientContractController::class, 'destroy'])->middleware('role:admin')->name('client-contracts.destroy');

        Route::get('/proposals/{proposal}',              [ProposalController::class, 'show'])->middleware('role:admin,sales,finance')->name('proposals.show');
    Route::get('/sales/{proposal}',                  [ProposalController::class, 'show'])->middleware('role:admin,sales,finance')->name('sales.show');
Route::get('/sales/proposals/create',            [ProposalController::class, 'create'])->middleware('role:admin,sales')->name('proposals.create-page');
    Route::get('/proposals/{proposal}/edit',         [ProposalController::class, 'edit'])->middleware('role:admin,sales')->name('proposals.edit-page');
    Route::get('/proposals/{proposal}/pdf',          [ProposalController::class, 'pdf'])->middleware('role:admin,sales,finance')->name('proposals.pdf');
    Route::post('/proposals',                        [ProposalController::class, 'store'])->middleware('role:admin,sales')->name('proposals.store');
    Route::put('/proposals/{proposal}',              [ProposalController::class, 'update'])->middleware('role:admin,sales')->name('proposals.update');
    Route::patch('/proposals/{proposal}/status',     [ProposalController::class, 'updateStatus'])->middleware('role:admin,sales')->name('proposals.status');
    Route::delete('/proposals/{proposal}',           [ProposalController::class, 'destroy'])->middleware('role:admin')->name('proposals.destroy');

    // ── Employees / HR ────────────────────────────────────────────────────────
    Route::get('/hr',                                [EmployeeController::class, 'index'])->middleware('role:admin,hr')->name('hr.index');
    Route::get('/hris',                              [HrisController::class, 'index'])->middleware('role:admin,hr')->name('hris.index');
    Route::post('/hris/skills',                      [HrisController::class, 'storeSkill'])->middleware('role:admin,hr')->name('hris.skills.store');
    Route::post('/hris/attendances',                 [HrisController::class, 'storeAttendance'])->middleware('role:admin,hr')->name('hris.attendances.store');
    Route::post('/hris/timesheets',                  [HrisController::class, 'storeTimesheet'])->middleware('role:admin,hr')->name('hris.timesheets.store');
    Route::post('/hris/leaves',                      [HrisController::class, 'storeLeave'])->middleware('role:admin,hr')->name('hris.leaves.store');
    Route::post('/hris/reviews',                     [HrisController::class, 'storeReview'])->middleware('role:admin,hr')->name('hris.reviews.store');
    Route::post('/hris/benefits',                    [HrisController::class, 'storeBenefit'])->middleware('role:admin,hr')->name('hris.benefits.store');
        Route::get('/employees/{employee}',              [EmployeeController::class, 'show'])->middleware('role:admin,hr')->name('employees.show');
Route::get('/employees/create',                  [EmployeeController::class, 'create'])->middleware('role:admin,hr')->name('employees.create-page');
    Route::get('/employees/{employee}/edit',         [EmployeeController::class, 'edit'])->middleware('role:admin,hr')->name('employees.edit-page');
    Route::post('/employees',                        [EmployeeController::class, 'store'])->middleware('role:admin,hr')->name('employees.store');
    Route::put('/employees/{employee}',              [EmployeeController::class, 'update'])->middleware('role:admin,hr')->name('employees.update');
    Route::delete('/employees/{employee}',           [EmployeeController::class, 'destroy'])->middleware('role:admin')->name('employees.destroy');

    // ── HR: Employee Skills ───────────────────────────────────────────────────
    Route::get('/employee-skills',                     [EmployeeSkillController::class, 'index'])->middleware('role:admin,hr')->name('employee-skills.index');
    Route::get('/employee-skills/create',              [EmployeeSkillController::class, 'create'])->middleware('role:admin,hr')->name('employee-skills.create-page');
    Route::get('/employee-skills/{skill}/edit',        [EmployeeSkillController::class, 'edit'])->middleware('role:admin,hr')->name('employee-skills.edit-page');
    Route::post('/employee-skills',                    [EmployeeSkillController::class, 'store'])->middleware('role:admin,hr')->name('employee-skills.store');
    Route::put('/employee-skills/{skill}',             [EmployeeSkillController::class, 'update'])->middleware('role:admin,hr')->name('employee-skills.update');
    Route::delete('/employee-skills/{skill}',          [EmployeeSkillController::class, 'destroy'])->middleware('role:admin')->name('employee-skills.destroy');

    // ── HR: Attendances ───────────────────────────────────────────────────────
    Route::get('/attendances',                         [AttendanceController::class, 'index'])->middleware('role:admin,hr')->name('attendances.index');
    Route::get('/attendances/create',                  [AttendanceController::class, 'create'])->middleware('role:admin,hr')->name('attendances.create-page');
    Route::get('/attendances/{attendance}/edit',       [AttendanceController::class, 'edit'])->middleware('role:admin,hr')->name('attendances.edit-page');
    Route::post('/attendances',                        [AttendanceController::class, 'store'])->middleware('role:admin,hr')->name('attendances.store');
    Route::put('/attendances/{attendance}',            [AttendanceController::class, 'update'])->middleware('role:admin,hr')->name('attendances.update');
    Route::delete('/attendances/{attendance}',         [AttendanceController::class, 'destroy'])->middleware('role:admin')->name('attendances.destroy');

    // ── HR: Timesheets ────────────────────────────────────────────────────────
    Route::get('/timesheets',                          [TimesheetController::class, 'index'])->middleware('role:admin,hr')->name('timesheets.index');
    Route::get('/timesheets/create',                   [TimesheetController::class, 'create'])->middleware('role:admin,hr')->name('timesheets.create-page');
    Route::get('/timesheets/{timesheet}/edit',         [TimesheetController::class, 'edit'])->middleware('role:admin,hr')->name('timesheets.edit-page');
    Route::post('/timesheets',                         [TimesheetController::class, 'store'])->middleware('role:admin,hr')->name('timesheets.store');
    Route::put('/timesheets/{timesheet}',              [TimesheetController::class, 'update'])->middleware('role:admin,hr')->name('timesheets.update');
    Route::delete('/timesheets/{timesheet}',           [TimesheetController::class, 'destroy'])->middleware('role:admin')->name('timesheets.destroy');

    // ── HR: Leave Requests ────────────────────────────────────────────────────
    Route::get('/leave-requests',                      [LeaveRequestController::class, 'index'])->middleware('role:admin,hr')->name('leave-requests.index');
    Route::get('/leave-requests/create',               [LeaveRequestController::class, 'create'])->middleware('role:admin,hr')->name('leave-requests.create-page');
    Route::get('/leave-requests/{leave}/edit',         [LeaveRequestController::class, 'edit'])->middleware('role:admin,hr')->name('leave-requests.edit-page');
    Route::post('/leave-requests',                     [LeaveRequestController::class, 'store'])->middleware('role:admin,hr')->name('leave-requests.store');
    Route::put('/leave-requests/{leave}',              [LeaveRequestController::class, 'update'])->middleware('role:admin,hr')->name('leave-requests.update');
    Route::delete('/leave-requests/{leave}',           [LeaveRequestController::class, 'destroy'])->middleware('role:admin')->name('leave-requests.destroy');

    // ── HR: Performance Reviews ───────────────────────────────────────────────
    Route::get('/performance-reviews',                 [PerformanceReviewController::class, 'index'])->middleware('role:admin,hr')->name('performance-reviews.index');
    Route::get('/performance-reviews/create',          [PerformanceReviewController::class, 'create'])->middleware('role:admin,hr')->name('performance-reviews.create-page');
    Route::get('/performance-reviews/{review}/edit',   [PerformanceReviewController::class, 'edit'])->middleware('role:admin,hr')->name('performance-reviews.edit-page');
    Route::post('/performance-reviews',                [PerformanceReviewController::class, 'store'])->middleware('role:admin,hr')->name('performance-reviews.store');
    Route::put('/performance-reviews/{review}',        [PerformanceReviewController::class, 'update'])->middleware('role:admin,hr')->name('performance-reviews.update');
    Route::delete('/performance-reviews/{review}',     [PerformanceReviewController::class, 'destroy'])->middleware('role:admin')->name('performance-reviews.destroy');

    // ── HR: Payroll Benefits ──────────────────────────────────────────────────
    Route::get('/payroll-benefits',                    [PayrollBenefitController::class, 'index'])->middleware('role:admin,hr')->name('payroll-benefits.index');
    Route::get('/payroll-benefits/create',             [PayrollBenefitController::class, 'create'])->middleware('role:admin,hr')->name('payroll-benefits.create-page');
    Route::get('/payroll-benefits/{benefit}/edit',     [PayrollBenefitController::class, 'edit'])->middleware('role:admin,hr')->name('payroll-benefits.edit-page');
    Route::post('/payroll-benefits',                   [PayrollBenefitController::class, 'store'])->middleware('role:admin,hr')->name('payroll-benefits.store');
    Route::put('/payroll-benefits/{benefit}',          [PayrollBenefitController::class, 'update'])->middleware('role:admin,hr')->name('payroll-benefits.update');
    Route::delete('/payroll-benefits/{benefit}',       [PayrollBenefitController::class, 'destroy'])->middleware('role:admin')->name('payroll-benefits.destroy');

    // ── Salaries ──────────────────────────────────────────────────────────────
    Route::get('/hr/salaries',                       [SalaryController::class, 'index'])->middleware('role:admin,hr')->name('salaries.index-page');
        Route::get('/salaries/{salary}',                 [SalaryController::class, 'show'])->middleware('role:admin,hr,finance')->name('salaries.show');
Route::get('/salaries/create',                   [SalaryController::class, 'create'])->middleware('role:admin,hr')->name('salaries.create-page');
    Route::get('/salaries/{salary}/edit',            [SalaryController::class, 'edit'])->middleware('role:admin,hr')->name('salaries.edit-page');
    Route::get('/salaries/{salary}/pdf',             [SalaryController::class, 'pdf'])->middleware('role:admin,hr,finance')->name('salaries.pdf');
    Route::post('/salaries',                         [SalaryController::class, 'store'])->middleware('role:admin,hr')->name('salaries.store');
    Route::put('/salaries/{salary}',                 [SalaryController::class, 'update'])->middleware('role:admin,hr')->name('salaries.update');
    Route::patch('/salaries/{salary}/status',        [SalaryController::class, 'updateStatus'])->middleware('role:admin,hr,finance')->name('salaries.status');
    Route::delete('/salaries/{salary}',              [SalaryController::class, 'destroy'])->middleware('role:admin')->name('salaries.destroy');

    // ── Reimbursements ────────────────────────────────────────────────────────
    Route::get('/finance/reimbursements',                    [ReimbursementController::class, 'index'])->middleware('role:admin,finance,hr')->name('reimbursements.index-page');
        Route::get('/reimbursements/{reimbursement}',    [ReimbursementController::class, 'show'])->middleware('role:admin,finance,hr')->name('reimbursements.show');
Route::get('/reimbursements/create',                     [ReimbursementController::class, 'create'])->middleware('role:admin,finance,hr')->name('reimbursements.create-page');
    Route::get('/reimbursements/{reimbursement}/edit',       [ReimbursementController::class, 'edit'])->middleware('role:admin,finance,hr')->name('reimbursements.edit-page');
    Route::post('/reimbursements',                           [ReimbursementController::class, 'store'])->middleware('role:admin,hr,finance')->name('reimbursements.store');
    Route::put('/reimbursements/{reimbursement}',            [ReimbursementController::class, 'update'])->middleware('role:admin,hr,finance')->name('reimbursements.update');
    Route::patch('/reimbursements/{reimbursement}/status',   [ReimbursementController::class, 'updateStatus'])->middleware('role:admin,hr,finance')->name('reimbursements.status');
    Route::delete('/reimbursements/{reimbursement}',         [ReimbursementController::class, 'destroy'])->middleware('role:admin')->name('reimbursements.destroy');

    // ── Cashflows ─────────────────────────────────────────────────────────────
    Route::get('/finance/cashflows',                 [CashflowController::class, 'index'])->middleware('role:admin,finance')->name('cashflows.index-page');
    Route::get('/cashflows/create',                  [CashflowController::class, 'create'])->middleware('role:admin,finance')->name('cashflows.create-page');
    Route::get('/cashflows/{cashflow}/edit',         [CashflowController::class, 'edit'])->middleware('role:admin,finance')->name('cashflows.edit-page');
    Route::post('/cashflows',                        [CashflowController::class, 'store'])->middleware('role:admin,finance')->name('cashflows.store');
    Route::put('/cashflows/{cashflow}',              [CashflowController::class, 'update'])->middleware('role:admin,finance')->name('cashflows.update');
    Route::delete('/cashflows/{cashflow}',           [CashflowController::class, 'destroy'])->middleware('role:admin')->name('cashflows.destroy');

    // ── Invoices ──────────────────────────────────────────────────────────────
    Route::get('/finance',                           [InvoiceController::class, 'index'])->middleware('role:admin,finance')->name('finance.index');
        Route::get('/invoices/{invoice}',                [InvoiceController::class, 'show'])->middleware('role:admin,finance,sales')->name('invoices.show');
Route::get('/invoices/create',                   [InvoiceController::class, 'create'])->middleware('role:admin,finance')->name('invoices.create-page');
    Route::get('/invoices/{invoice}/edit',           [InvoiceController::class, 'edit'])->middleware('role:admin,finance')->name('invoices.edit-page');
    Route::get('/invoices/{invoice}/pdf',            [InvoiceController::class, 'pdf'])->middleware('role:admin,finance,sales')->name('invoices.pdf');
    Route::post('/invoices',                         [InvoiceController::class, 'store'])->middleware('role:admin,finance')->name('invoices.store');
    Route::put('/invoices/{invoice}',                [InvoiceController::class, 'update'])->middleware('role:admin,finance')->name('invoices.update');
    Route::delete('/invoices/{invoice}',             [InvoiceController::class, 'destroy'])->middleware('role:admin')->name('invoices.destroy');

    // ── Payments ──────────────────────────────────────────────────────────────
    Route::get('/payments/create',                   [PaymentController::class, 'create'])->middleware('role:admin,finance')->name('payments.create-page');
    Route::post('/payments',                         [PaymentController::class, 'store'])->middleware('role:admin,finance')->name('payments.store');
    Route::delete('/payments/{payment}',             [PaymentController::class, 'destroy'])->middleware('role:admin')->name('payments.destroy');

    // Finance nested module aliases
    Route::get('/finance/invoices',                  [InvoiceController::class, 'index'])->middleware('role:admin,finance')->name('finance.invoices.index');
    Route::get('/finance/chart-accounts',            [ChartAccountController::class, 'index'])->middleware('role:admin,finance')->name('finance.chart-accounts.index');
    Route::get('/finance/journal-entries',           [JournalEntryController::class, 'index'])->middleware('role:admin,finance')->name('finance.journal-entries.index');
    Route::get('/finance/recurring-billings',        [RecurringBillingController::class, 'index'])->middleware('role:admin,finance')->name('finance.recurring-billings.index');
    Route::get('/finance/payment-reminders',         [PaymentReminderController::class, 'index'])->middleware('role:admin,finance')->name('finance.payment-reminders.index');
    Route::get('/finance/vendor-bills',              [VendorBillController::class, 'index'])->middleware('role:admin,finance')->name('finance.vendor-bills.index');
    Route::get('/finance/vendor-payments',           [VendorPaymentController::class, 'index'])->middleware('role:admin,finance')->name('finance.vendor-payments.index');
    Route::get('/finance/budgets',                   [BudgetController::class, 'index'])->middleware('role:admin,finance')->name('finance.budgets.index');
    Route::get('/finance/tax-rules',                 [TaxRuleController::class, 'index'])->middleware('role:admin,finance')->name('finance.tax-rules.index');
    Route::get('/finance/fixed-assets',              [FixedAssetController::class, 'index'])->middleware('role:admin,finance')->name('finance.fixed-assets.index');
    Route::get('/finance/currency-rates',            [CurrencyRateController::class, 'index'])->middleware('role:admin,finance')->name('finance.currency-rates.index');
    Route::get('/finance/revenue-schedules',         [RevenueScheduleController::class, 'index'])->middleware('role:admin,finance')->name('finance.revenue-schedules.index');
    Route::get('/finance/bank-reconciliations',      [BankReconciliationItemController::class, 'index'])->middleware('role:admin,finance')->name('finance.bank-reconciliations.index');
    Route::get('/finance/purchase-matches',          [PurchaseMatchController::class, 'index'])->middleware('role:admin,finance')->name('finance.purchase-matches.index');

    // Finance Suite
    Route::get('/finance-suite',                     [FinanceSuiteController::class, 'index'])->middleware('role:admin,finance')->name('finance-suite.index');
    Route::post('/finance-suite/coa',                [FinanceSuiteController::class, 'storeCoa'])->middleware('role:admin,finance')->name('finance-suite.coa.store');
    Route::post('/finance-suite/journals',           [FinanceSuiteController::class, 'storeJournal'])->middleware('role:admin,finance')->name('finance-suite.journals.store');
    Route::post('/finance-suite/recurring',          [FinanceSuiteController::class, 'storeRecurring'])->middleware('role:admin,finance')->name('finance-suite.recurring.store');
    Route::post('/finance-suite/reminders',          [FinanceSuiteController::class, 'storeReminder'])->middleware('role:admin,finance')->name('finance-suite.reminders.store');
    Route::post('/finance-suite/vendor-bills',       [FinanceSuiteController::class, 'storeVendorBill'])->middleware('role:admin,finance')->name('finance-suite.vendor-bills.store');
    Route::post('/finance-suite/vendor-payments',    [FinanceSuiteController::class, 'storeVendorPayment'])->middleware('role:admin,finance')->name('finance-suite.vendor-payments.store');
    Route::post('/finance-suite/budgets',            [FinanceSuiteController::class, 'storeBudget'])->middleware('role:admin,finance')->name('finance-suite.budgets.store');
    Route::post('/finance-suite/taxes',              [FinanceSuiteController::class, 'storeTaxRule'])->middleware('role:admin,finance')->name('finance-suite.taxes.store');

    // ── Finance Suite: Chart of Accounts ────────────────────────────────────────
    Route::get('/chart-accounts',                        [ChartAccountController::class, 'index'])->middleware('role:admin,finance')->name('chart-accounts.index');
    Route::get('/chart-accounts/create',                 [ChartAccountController::class, 'create'])->middleware('role:admin,finance')->name('chart-accounts.create-page');
    Route::get('/chart-accounts/{chartAccount}/edit',    [ChartAccountController::class, 'edit'])->middleware('role:admin,finance')->name('chart-accounts.edit-page');
    Route::post('/chart-accounts',                       [ChartAccountController::class, 'store'])->middleware('role:admin,finance')->name('chart-accounts.store');
    Route::put('/chart-accounts/{chartAccount}',         [ChartAccountController::class, 'update'])->middleware('role:admin,finance')->name('chart-accounts.update');
    Route::delete('/chart-accounts/{chartAccount}',      [ChartAccountController::class, 'destroy'])->middleware('role:admin')->name('chart-accounts.destroy');

    // ── Accounting Master: Journals / Payment Terms / Currencies / Fiscal Periods ──
    Route::get('/accounting-journals',                    [AccountingJournalController::class, 'index'])->middleware('role:admin,finance')->name('accounting-journals.index');
    Route::get('/accounting-journals/create',             [AccountingJournalController::class, 'create'])->middleware('role:admin,finance')->name('accounting-journals.create-page');
    Route::get('/accounting-journals/{journal}/edit',     [AccountingJournalController::class, 'edit'])->middleware('role:admin,finance')->name('accounting-journals.edit-page');
    Route::post('/accounting-journals',                   [AccountingJournalController::class, 'store'])->middleware('role:admin,finance')->name('accounting-journals.store');
    Route::put('/accounting-journals/{journal}',          [AccountingJournalController::class, 'update'])->middleware('role:admin,finance')->name('accounting-journals.update');
    Route::delete('/accounting-journals/{journal}',       [AccountingJournalController::class, 'destroy'])->middleware('role:admin')->name('accounting-journals.destroy');

    Route::get('/payment-terms',                          [PaymentTermController::class, 'index'])->middleware('role:admin,finance')->name('payment-terms.index');
    Route::get('/payment-terms/create',                   [PaymentTermController::class, 'create'])->middleware('role:admin,finance')->name('payment-terms.create-page');
    Route::get('/payment-terms/{term}/edit',              [PaymentTermController::class, 'edit'])->middleware('role:admin,finance')->name('payment-terms.edit-page');
    Route::post('/payment-terms',                         [PaymentTermController::class, 'store'])->middleware('role:admin,finance')->name('payment-terms.store');
    Route::put('/payment-terms/{term}',                   [PaymentTermController::class, 'update'])->middleware('role:admin,finance')->name('payment-terms.update');
    Route::delete('/payment-terms/{term}',                [PaymentTermController::class, 'destroy'])->middleware('role:admin')->name('payment-terms.destroy');

    Route::get('/currencies',                             [CurrencyController::class, 'index'])->middleware('role:admin,finance')->name('currencies.index');
    Route::get('/currencies/create',                      [CurrencyController::class, 'create'])->middleware('role:admin,finance')->name('currencies.create-page');
    Route::get('/currencies/{currency}/edit',             [CurrencyController::class, 'edit'])->middleware('role:admin,finance')->name('currencies.edit-page');
    Route::post('/currencies',                            [CurrencyController::class, 'store'])->middleware('role:admin,finance')->name('currencies.store');
    Route::put('/currencies/{currency}',                  [CurrencyController::class, 'update'])->middleware('role:admin,finance')->name('currencies.update');
    Route::delete('/currencies/{currency}',               [CurrencyController::class, 'destroy'])->middleware('role:admin')->name('currencies.destroy');

    Route::get('/fiscal-periods',                         [FiscalPeriodController::class, 'index'])->middleware('role:admin,finance')->name('fiscal-periods.index');
    Route::get('/fiscal-periods/create',                  [FiscalPeriodController::class, 'create'])->middleware('role:admin,finance')->name('fiscal-periods.create-page');
    Route::get('/fiscal-periods/{period}/edit',           [FiscalPeriodController::class, 'edit'])->middleware('role:admin,finance')->name('fiscal-periods.edit-page');
    Route::post('/fiscal-periods',                        [FiscalPeriodController::class, 'store'])->middleware('role:admin,finance')->name('fiscal-periods.store');
    Route::put('/fiscal-periods/{period}',                [FiscalPeriodController::class, 'update'])->middleware('role:admin,finance')->name('fiscal-periods.update');
    Route::patch('/fiscal-periods/{period}/toggle',       [FiscalPeriodController::class, 'toggle'])->middleware('role:admin,finance')->name('fiscal-periods.toggle');
    Route::delete('/fiscal-periods/{period}',             [FiscalPeriodController::class, 'destroy'])->middleware('role:admin')->name('fiscal-periods.destroy');

    // ── Finance Suite: Journal Entries ──────────────────────────────────────────
    Route::get('/journal-entries',                       [JournalEntryController::class, 'index'])->middleware('role:admin,finance')->name('journal-entries.index');
    Route::get('/journal-entries/create',                [JournalEntryController::class, 'create'])->middleware('role:admin,finance')->name('journal-entries.create-page');
    Route::get('/journal-entries/{journalEntry}/edit',   [JournalEntryController::class, 'edit'])->middleware('role:admin,finance')->name('journal-entries.edit-page');
    Route::post('/journal-entries',                      [JournalEntryController::class, 'store'])->middleware('role:admin,finance')->name('journal-entries.store');
    Route::put('/journal-entries/{journalEntry}',        [JournalEntryController::class, 'update'])->middleware('role:admin,finance')->name('journal-entries.update');
    Route::delete('/journal-entries/{journalEntry}',     [JournalEntryController::class, 'destroy'])->middleware('role:admin')->name('journal-entries.destroy');

    // ── Finance Suite: Recurring Billings ───────────────────────────────────────
    Route::get('/recurring-billings',                    [RecurringBillingController::class, 'index'])->middleware('role:admin,finance')->name('recurring-billings.index');
    Route::get('/recurring-billings/create',             [RecurringBillingController::class, 'create'])->middleware('role:admin,finance')->name('recurring-billings.create-page');
    Route::get('/recurring-billings/{recurringBilling}/edit', [RecurringBillingController::class, 'edit'])->middleware('role:admin,finance')->name('recurring-billings.edit-page');
    Route::post('/recurring-billings',                   [RecurringBillingController::class, 'store'])->middleware('role:admin,finance')->name('recurring-billings.store');
    Route::put('/recurring-billings/{recurringBilling}', [RecurringBillingController::class, 'update'])->middleware('role:admin,finance')->name('recurring-billings.update');
    Route::delete('/recurring-billings/{recurringBilling}', [RecurringBillingController::class, 'destroy'])->middleware('role:admin')->name('recurring-billings.destroy');

    // ── Finance Suite: Payment Reminders ────────────────────────────────────────
    Route::get('/payment-reminders',                     [PaymentReminderController::class, 'index'])->middleware('role:admin,finance')->name('payment-reminders.index');
    Route::get('/payment-reminders/create',              [PaymentReminderController::class, 'create'])->middleware('role:admin,finance')->name('payment-reminders.create-page');
    Route::get('/payment-reminders/{paymentReminder}/edit', [PaymentReminderController::class, 'edit'])->middleware('role:admin,finance')->name('payment-reminders.edit-page');
    Route::post('/payment-reminders',                    [PaymentReminderController::class, 'store'])->middleware('role:admin,finance')->name('payment-reminders.store');
    Route::put('/payment-reminders/{paymentReminder}',   [PaymentReminderController::class, 'update'])->middleware('role:admin,finance')->name('payment-reminders.update');
    Route::delete('/payment-reminders/{paymentReminder}', [PaymentReminderController::class, 'destroy'])->middleware('role:admin')->name('payment-reminders.destroy');

    // ── Finance Suite: Vendor Bills ─────────────────────────────────────────────
    Route::get('/vendor-bills',                          [VendorBillController::class, 'index'])->middleware('role:admin,finance')->name('vendor-bills.index');
    Route::get('/vendor-bills/create',                   [VendorBillController::class, 'create'])->middleware('role:admin,finance')->name('vendor-bills.create-page');
    Route::get('/vendor-bills/{vendorBill}/edit',        [VendorBillController::class, 'edit'])->middleware('role:admin,finance')->name('vendor-bills.edit-page');
    Route::post('/vendor-bills',                         [VendorBillController::class, 'store'])->middleware('role:admin,finance')->name('vendor-bills.store');
    Route::put('/vendor-bills/{vendorBill}',             [VendorBillController::class, 'update'])->middleware('role:admin,finance')->name('vendor-bills.update');
    Route::delete('/vendor-bills/{vendorBill}',          [VendorBillController::class, 'destroy'])->middleware('role:admin')->name('vendor-bills.destroy');

    // ── Finance Suite: Vendor Payments ──────────────────────────────────────────
    Route::get('/vendor-payments',                       [VendorPaymentController::class, 'index'])->middleware('role:admin,finance')->name('vendor-payments.index');
    Route::get('/vendor-payments/create',                [VendorPaymentController::class, 'create'])->middleware('role:admin,finance')->name('vendor-payments.create-page');
    Route::get('/vendor-payments/{vendorPayment}/edit',  [VendorPaymentController::class, 'edit'])->middleware('role:admin,finance')->name('vendor-payments.edit-page');
    Route::post('/vendor-payments',                      [VendorPaymentController::class, 'store'])->middleware('role:admin,finance')->name('vendor-payments.store');
    Route::put('/vendor-payments/{vendorPayment}',       [VendorPaymentController::class, 'update'])->middleware('role:admin,finance')->name('vendor-payments.update');
    Route::delete('/vendor-payments/{vendorPayment}',    [VendorPaymentController::class, 'destroy'])->middleware('role:admin')->name('vendor-payments.destroy');

    // ── Finance Suite: Budgets ──────────────────────────────────────────────────
    Route::get('/budgets',                               [BudgetController::class, 'index'])->middleware('role:admin,finance')->name('budgets.index');
    Route::get('/budgets/create',                        [BudgetController::class, 'create'])->middleware('role:admin,finance')->name('budgets.create-page');
    Route::get('/budgets/{budget}/edit',                 [BudgetController::class, 'edit'])->middleware('role:admin,finance')->name('budgets.edit-page');
    Route::post('/budgets',                              [BudgetController::class, 'store'])->middleware('role:admin,finance')->name('budgets.store');
    Route::put('/budgets/{budget}',                      [BudgetController::class, 'update'])->middleware('role:admin,finance')->name('budgets.update');
    Route::delete('/budgets/{budget}',                   [BudgetController::class, 'destroy'])->middleware('role:admin')->name('budgets.destroy');

    // ── Finance Suite: Tax Rules ────────────────────────────────────────────────
    Route::get('/tax-rules',                             [TaxRuleController::class, 'index'])->middleware('role:admin,finance')->name('tax-rules.index');
    Route::get('/tax-rules/create',                      [TaxRuleController::class, 'create'])->middleware('role:admin,finance')->name('tax-rules.create-page');
    Route::get('/tax-rules/{taxRule}/edit',              [TaxRuleController::class, 'edit'])->middleware('role:admin,finance')->name('tax-rules.edit-page');
    Route::post('/tax-rules',                            [TaxRuleController::class, 'store'])->middleware('role:admin,finance')->name('tax-rules.store');
    Route::put('/tax-rules/{taxRule}',                   [TaxRuleController::class, 'update'])->middleware('role:admin,finance')->name('tax-rules.update');
    Route::delete('/tax-rules/{taxRule}',                [TaxRuleController::class, 'destroy'])->middleware('role:admin')->name('tax-rules.destroy');

    Route::get('/finance-advanced',                  [FinanceAdvancedController::class, 'index'])->middleware('role:admin,finance')->name('finance-advanced.index');
    Route::post('/finance-advanced/assets',          [FinanceAdvancedController::class, 'storeAsset'])->middleware('role:admin,finance')->name('finance-advanced.assets.store');
    Route::post('/finance-advanced/rates',           [FinanceAdvancedController::class, 'storeRate'])->middleware('role:admin,finance')->name('finance-advanced.rates.store');
    Route::post('/finance-advanced/variances',       [FinanceAdvancedController::class, 'storeVariance'])->middleware('role:admin,finance')->name('finance-advanced.variances.store');
    Route::post('/finance-advanced/revenues',        [FinanceAdvancedController::class, 'storeRevenue'])->middleware('role:admin,finance')->name('finance-advanced.revenues.store');
    Route::post('/finance-advanced/reconciliations', [FinanceAdvancedController::class, 'storeReconciliation'])->middleware('role:admin,finance')->name('finance-advanced.reconciliations.store');
    Route::post('/finance-advanced/matches',         [FinanceAdvancedController::class, 'storeMatch'])->middleware('role:admin,finance')->name('finance-advanced.matches.store');

    Route::get('/procurement',                       [ProcurementController::class, 'index'])->middleware('role:admin,finance')->name('procurement.index');
    Route::post('/procurement/vendors',              [ProcurementController::class, 'storeVendor'])->middleware('role:admin,finance')->name('procurement.vendors.store');
    Route::post('/procurement/requisitions',         [ProcurementController::class, 'storeRequisition'])->middleware('role:admin,finance')->name('procurement.requisitions.store');
    Route::post('/procurement/orders',               [ProcurementController::class, 'storeOrder'])->middleware('role:admin,finance')->name('procurement.orders.store');
    Route::post('/procurement/receipts',             [ProcurementController::class, 'storeReceipt'])->middleware('role:admin,finance')->name('procurement.receipts.store');
    Route::post('/procurement/contracts',            [ProcurementController::class, 'storeContract'])->middleware('role:admin,finance')->name('procurement.contracts.store');

    // ── Fixed Assets ──────────────────────────────────────────────────────────
    Route::get('/fixed-assets', [FixedAssetController::class, 'index'])->middleware('role:admin,finance')->name('fixed-assets.index');
    Route::get('/fixed-assets/create', [FixedAssetController::class, 'create'])->middleware('role:admin,finance')->name('fixed-assets.create-page');
    Route::get('/fixed-assets/{asset}/edit', [FixedAssetController::class, 'edit'])->middleware('role:admin,finance')->name('fixed-assets.edit-page');
    Route::post('/fixed-assets', [FixedAssetController::class, 'store'])->middleware('role:admin,finance')->name('fixed-assets.store');
    Route::put('/fixed-assets/{asset}', [FixedAssetController::class, 'update'])->middleware('role:admin,finance')->name('fixed-assets.update');
    Route::delete('/fixed-assets/{asset}', [FixedAssetController::class, 'destroy'])->middleware('role:admin')->name('fixed-assets.destroy');

    // ── Currency Rates ────────────────────────────────────────────────────────
    Route::get('/currency-rates', [CurrencyRateController::class, 'index'])->middleware('role:admin,finance')->name('currency-rates.index');
    Route::get('/currency-rates/create', [CurrencyRateController::class, 'create'])->middleware('role:admin,finance')->name('currency-rates.create-page');
    Route::get('/currency-rates/{rate}/edit', [CurrencyRateController::class, 'edit'])->middleware('role:admin,finance')->name('currency-rates.edit-page');
    Route::post('/currency-rates', [CurrencyRateController::class, 'store'])->middleware('role:admin,finance')->name('currency-rates.store');
    Route::put('/currency-rates/{rate}', [CurrencyRateController::class, 'update'])->middleware('role:admin,finance')->name('currency-rates.update');
    Route::delete('/currency-rates/{rate}', [CurrencyRateController::class, 'destroy'])->middleware('role:admin')->name('currency-rates.destroy');

    // ── Currency Variances ────────────────────────────────────────────────────
    Route::get('/currency-variances', [CurrencyVarianceController::class, 'index'])->middleware('role:admin,finance')->name('currency-variances.index');
    Route::get('/currency-variances/create', [CurrencyVarianceController::class, 'create'])->middleware('role:admin,finance')->name('currency-variances.create-page');
    Route::get('/currency-variances/{variance}/edit', [CurrencyVarianceController::class, 'edit'])->middleware('role:admin,finance')->name('currency-variances.edit-page');
    Route::post('/currency-variances', [CurrencyVarianceController::class, 'store'])->middleware('role:admin,finance')->name('currency-variances.store');
    Route::put('/currency-variances/{variance}', [CurrencyVarianceController::class, 'update'])->middleware('role:admin,finance')->name('currency-variances.update');
    Route::delete('/currency-variances/{variance}', [CurrencyVarianceController::class, 'destroy'])->middleware('role:admin')->name('currency-variances.destroy');

    // ── Revenue Schedules ─────────────────────────────────────────────────────
    Route::get('/revenue-schedules', [RevenueScheduleController::class, 'index'])->middleware('role:admin,finance')->name('revenue-schedules.index');
    Route::get('/revenue-schedules/create', [RevenueScheduleController::class, 'create'])->middleware('role:admin,finance')->name('revenue-schedules.create-page');
    Route::get('/revenue-schedules/{schedule}/edit', [RevenueScheduleController::class, 'edit'])->middleware('role:admin,finance')->name('revenue-schedules.edit-page');
    Route::post('/revenue-schedules', [RevenueScheduleController::class, 'store'])->middleware('role:admin,finance')->name('revenue-schedules.store');
    Route::put('/revenue-schedules/{schedule}', [RevenueScheduleController::class, 'update'])->middleware('role:admin,finance')->name('revenue-schedules.update');
    Route::delete('/revenue-schedules/{schedule}', [RevenueScheduleController::class, 'destroy'])->middleware('role:admin')->name('revenue-schedules.destroy');

    // ── Bank Reconciliation Items ─────────────────────────────────────────────
    Route::get('/bank-reconciliation-items', [BankReconciliationItemController::class, 'index'])->middleware('role:admin,finance')->name('bank-reconciliation-items.index');
    Route::get('/bank-reconciliation-items/create', [BankReconciliationItemController::class, 'create'])->middleware('role:admin,finance')->name('bank-reconciliation-items.create-page');
    Route::get('/bank-reconciliation-items/{reconciliation}/edit', [BankReconciliationItemController::class, 'edit'])->middleware('role:admin,finance')->name('bank-reconciliation-items.edit-page');
    Route::post('/bank-reconciliation-items', [BankReconciliationItemController::class, 'store'])->middleware('role:admin,finance')->name('bank-reconciliation-items.store');
    Route::put('/bank-reconciliation-items/{reconciliation}', [BankReconciliationItemController::class, 'update'])->middleware('role:admin,finance')->name('bank-reconciliation-items.update');
    Route::delete('/bank-reconciliation-items/{reconciliation}', [BankReconciliationItemController::class, 'destroy'])->middleware('role:admin')->name('bank-reconciliation-items.destroy');

    // ── Purchase Matches ──────────────────────────────────────────────────────
    Route::get('/purchase-matches', [PurchaseMatchController::class, 'index'])->middleware('role:admin,finance')->name('purchase-matches.index');
    Route::get('/purchase-matches/create', [PurchaseMatchController::class, 'create'])->middleware('role:admin,finance')->name('purchase-matches.create-page');
    Route::get('/purchase-matches/{match}/edit', [PurchaseMatchController::class, 'edit'])->middleware('role:admin,finance')->name('purchase-matches.edit-page');
    Route::post('/purchase-matches', [PurchaseMatchController::class, 'store'])->middleware('role:admin,finance')->name('purchase-matches.store');
    Route::put('/purchase-matches/{match}', [PurchaseMatchController::class, 'update'])->middleware('role:admin,finance')->name('purchase-matches.update');
    Route::delete('/purchase-matches/{match}', [PurchaseMatchController::class, 'destroy'])->middleware('role:admin')->name('purchase-matches.destroy');

    // ── Procurement: Vendors ─────────────────────────────────────────────────---
    Route::get('/vendors',                           [VendorController::class, 'index'])->middleware('role:admin,finance')->name('vendors.index');
        Route::get('/vendors/{vendor}',                  [VendorController::class, 'show'])->middleware('role:admin,finance')->name('vendors.show');
Route::get('/vendors/create',                    [VendorController::class, 'create'])->middleware('role:admin,finance')->name('vendors.create-page');
    Route::get('/vendors/{vendor}/edit',             [VendorController::class, 'edit'])->middleware('role:admin,finance')->name('vendors.edit-page');
    Route::post('/vendors',                          [VendorController::class, 'store'])->middleware('role:admin,finance')->name('vendors.store');
    Route::put('/vendors/{vendor}',                  [VendorController::class, 'update'])->middleware('role:admin,finance')->name('vendors.update');
    Route::delete('/vendors/{vendor}',               [VendorController::class, 'destroy'])->middleware('role:admin')->name('vendors.destroy');

    // ── Procurement: Purchase Requisitions ───────────────────────────────────────
    Route::get('/purchase-requisitions',             [PurchaseRequisitionController::class, 'index'])->middleware('role:admin,finance')->name('purchase-requisitions.index');
    Route::get('/purchase-requisitions/create',      [PurchaseRequisitionController::class, 'create'])->middleware('role:admin,finance')->name('purchase-requisitions.create-page');
    Route::get('/purchase-requisitions/{requisition}/edit', [PurchaseRequisitionController::class, 'edit'])->middleware('role:admin,finance')->name('purchase-requisitions.edit-page');
    Route::post('/purchase-requisitions',            [PurchaseRequisitionController::class, 'store'])->middleware('role:admin,finance')->name('purchase-requisitions.store');
    Route::put('/purchase-requisitions/{requisition}', [PurchaseRequisitionController::class, 'update'])->middleware('role:admin,finance')->name('purchase-requisitions.update');
    Route::delete('/purchase-requisitions/{requisition}', [PurchaseRequisitionController::class, 'destroy'])->middleware('role:admin')->name('purchase-requisitions.destroy');

    // ── Procurement: Purchase Orders ─────────────────────────────────────────────
    Route::get('/purchase-orders',                   [PurchaseOrderController::class, 'index'])->middleware('role:admin,finance')->name('purchase-orders.index');
        Route::get('/purchase-orders/{order}',           [PurchaseOrderController::class, 'show'])->middleware('role:admin,finance')->name('purchase-orders.show');
Route::get('/purchase-orders/create',            [PurchaseOrderController::class, 'create'])->middleware('role:admin,finance')->name('purchase-orders.create-page');
    Route::get('/purchase-orders/{order}/edit',      [PurchaseOrderController::class, 'edit'])->middleware('role:admin,finance')->name('purchase-orders.edit-page');
    Route::get('/purchase-orders/{order}/pdf',       [PurchaseOrderController::class, 'pdf'])->middleware('role:admin,finance')->name('purchase-orders.pdf');
    Route::post('/purchase-orders',                  [PurchaseOrderController::class, 'store'])->middleware('role:admin,finance')->name('purchase-orders.store');
    Route::put('/purchase-orders/{order}',           [PurchaseOrderController::class, 'update'])->middleware('role:admin,finance')->name('purchase-orders.update');
    Route::delete('/purchase-orders/{order}',        [PurchaseOrderController::class, 'destroy'])->middleware('role:admin')->name('purchase-orders.destroy');

    // ── Procurement: Goods Receipts ──────────────────────────────────────────────
    Route::get('/goods-receipts',                    [GoodsReceiptController::class, 'index'])->middleware('role:admin,finance')->name('goods-receipts.index');
        Route::get('/goods-receipts/{receipt}',          [GoodsReceiptController::class, 'show'])->middleware('role:admin,finance')->name('goods-receipts.show');
Route::get('/goods-receipts/create',             [GoodsReceiptController::class, 'create'])->middleware('role:admin,finance')->name('goods-receipts.create-page');
    Route::get('/goods-receipts/{receipt}/edit',     [GoodsReceiptController::class, 'edit'])->middleware('role:admin,finance')->name('goods-receipts.edit-page');
    Route::post('/goods-receipts',                   [GoodsReceiptController::class, 'store'])->middleware('role:admin,finance')->name('goods-receipts.store');
    Route::put('/goods-receipts/{receipt}',          [GoodsReceiptController::class, 'update'])->middleware('role:admin,finance')->name('goods-receipts.update');
    Route::delete('/goods-receipts/{receipt}',       [GoodsReceiptController::class, 'destroy'])->middleware('role:admin')->name('goods-receipts.destroy');

    // ── Procurement: Contracts ───────────────────────────────────────────────────
    Route::get('/procurement-contracts',             [ProcurementContractController::class, 'index'])->middleware('role:admin,finance')->name('procurement-contracts.index');
    Route::get('/procurement-contracts/create',      [ProcurementContractController::class, 'create'])->middleware('role:admin,finance')->name('procurement-contracts.create-page');
    Route::get('/procurement-contracts/{contract}/edit', [ProcurementContractController::class, 'edit'])->middleware('role:admin,finance')->name('procurement-contracts.edit-page');
    Route::post('/procurement-contracts',            [ProcurementContractController::class, 'store'])->middleware('role:admin,finance')->name('procurement-contracts.store');
    Route::put('/procurement-contracts/{contract}',  [ProcurementContractController::class, 'update'])->middleware('role:admin,finance')->name('procurement-contracts.update');
    Route::delete('/procurement-contracts/{contract}', [ProcurementContractController::class, 'destroy'])->middleware('role:admin')->name('procurement-contracts.destroy');

    // ── Inventory: Products ──────────────────────────────────────────────────
    Route::get('/products',                            [ProductController::class, 'index'])->middleware('role:admin,finance,sales')->name('products.index');
        Route::get('/products/{product}',                  [ProductController::class, 'show'])->middleware('role:admin,finance,sales')->name('products.show');
Route::get('/products/create',                     [ProductController::class, 'create'])->middleware('role:admin,finance,sales')->name('products.create-page');
    Route::get('/products/{product}/edit',             [ProductController::class, 'edit'])->middleware('role:admin,finance,sales')->name('products.edit-page');
    Route::post('/products',                           [ProductController::class, 'store'])->middleware('role:admin,finance,sales')->name('products.store');
    Route::put('/products/{product}',                  [ProductController::class, 'update'])->middleware('role:admin,finance,sales')->name('products.update');
    Route::delete('/products/{product}',               [ProductController::class, 'destroy'])->middleware('role:admin')->name('products.destroy');

    // ── Inventory: Product Categories ────────────────────────────────────────
    Route::get('/product-categories',                  [ProductCategoryController::class, 'index'])->middleware('role:admin,finance,sales')->name('product-categories.index');
    Route::get('/product-categories/create',           [ProductCategoryController::class, 'create'])->middleware('role:admin,finance,sales')->name('product-categories.create-page');
    Route::get('/product-categories/{category}/edit',  [ProductCategoryController::class, 'edit'])->middleware('role:admin,finance,sales')->name('product-categories.edit-page');
    Route::post('/product-categories',                 [ProductCategoryController::class, 'store'])->middleware('role:admin,finance,sales')->name('product-categories.store');
    Route::put('/product-categories/{category}',       [ProductCategoryController::class, 'update'])->middleware('role:admin,finance,sales')->name('product-categories.update');
    Route::delete('/product-categories/{category}',    [ProductCategoryController::class, 'destroy'])->middleware('role:admin')->name('product-categories.destroy');

    // ── Inventory: Units of Measure ──────────────────────────────────────────
    Route::get('/product-units',                       [UnitOfMeasureController::class, 'index'])->middleware('role:admin,finance,sales')->name('product-units.index');
    Route::get('/product-units/create',                [UnitOfMeasureController::class, 'create'])->middleware('role:admin,finance,sales')->name('product-units.create-page');
    Route::get('/product-units/{unit}/edit',           [UnitOfMeasureController::class, 'edit'])->middleware('role:admin,finance,sales')->name('product-units.edit-page');
    Route::post('/product-units',                      [UnitOfMeasureController::class, 'store'])->middleware('role:admin,finance,sales')->name('product-units.store');
    Route::put('/product-units/{unit}',                [UnitOfMeasureController::class, 'update'])->middleware('role:admin,finance,sales')->name('product-units.update');
    Route::delete('/product-units/{unit}',             [UnitOfMeasureController::class, 'destroy'])->middleware('role:admin')->name('product-units.destroy');

    // ── Inventory: Warehouses ────────────────────────────────────────────────
    Route::get('/warehouses',                          [WarehouseController::class, 'index'])->middleware('role:admin,finance,sales')->name('warehouses.index');
    Route::get('/warehouses/create',                   [WarehouseController::class, 'create'])->middleware('role:admin,finance,sales')->name('warehouses.create-page');
    Route::get('/warehouses/{warehouse}/edit',         [WarehouseController::class, 'edit'])->middleware('role:admin,finance,sales')->name('warehouses.edit-page');
    Route::post('/warehouses',                         [WarehouseController::class, 'store'])->middleware('role:admin,finance,sales')->name('warehouses.store');
    Route::put('/warehouses/{warehouse}',              [WarehouseController::class, 'update'])->middleware('role:admin,finance,sales')->name('warehouses.update');
    Route::delete('/warehouses/{warehouse}',           [WarehouseController::class, 'destroy'])->middleware('role:admin')->name('warehouses.destroy');

    // ── Inventory: Stock Locations ───────────────────────────────────────────
    Route::get('/stock-locations',                     [StockLocationController::class, 'index'])->middleware('role:admin,finance,sales')->name('stock-locations.index');
    Route::get('/stock-locations/create',              [StockLocationController::class, 'create'])->middleware('role:admin,finance,sales')->name('stock-locations.create-page');
    Route::get('/stock-locations/{location}/edit',     [StockLocationController::class, 'edit'])->middleware('role:admin,finance,sales')->name('stock-locations.edit-page');
    Route::post('/stock-locations',                    [StockLocationController::class, 'store'])->middleware('role:admin,finance,sales')->name('stock-locations.store');
    Route::put('/stock-locations/{location}',          [StockLocationController::class, 'update'])->middleware('role:admin,finance,sales')->name('stock-locations.update');
    Route::delete('/stock-locations/{location}',       [StockLocationController::class, 'destroy'])->middleware('role:admin')->name('stock-locations.destroy');

    // ── Inventory: Stock Movements ───────────────────────────────────────────
    Route::get('/stock-movements',                     [StockMovementController::class, 'index'])->middleware('role:admin,finance,sales')->name('stock-movements.index');
    Route::get('/stock-movements/create',              [StockMovementController::class, 'create'])->middleware('role:admin,finance,sales')->name('stock-movements.create-page');
    Route::post('/stock-movements',                    [StockMovementController::class, 'store'])->middleware('role:admin,finance,sales')->name('stock-movements.store');
    Route::delete('/stock-movements/{movement}',       [StockMovementController::class, 'destroy'])->middleware('role:admin')->name('stock-movements.destroy');

    // ── Inventory: Stock Adjustments ─────────────────────────────────────────
    Route::get('/stock-adjustments',                   [StockAdjustmentController::class, 'index'])->middleware('role:admin,finance,sales')->name('stock-adjustments.index');
    Route::get('/stock-adjustments/create',            [StockAdjustmentController::class, 'create'])->middleware('role:admin,finance,sales')->name('stock-adjustments.create-page');
    Route::post('/stock-adjustments',                  [StockAdjustmentController::class, 'store'])->middleware('role:admin,finance,sales')->name('stock-adjustments.store');
    Route::post('/stock-adjustments/{adjustment}/post', [StockAdjustmentController::class, 'post'])->middleware('role:admin,finance,sales')->name('stock-adjustments.post');
    Route::delete('/stock-adjustments/{adjustment}',   [StockAdjustmentController::class, 'destroy'])->middleware('role:admin')->name('stock-adjustments.destroy');

    // ── Reports & Exports ─────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->middleware('role:admin,finance')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/profit-loss', [ReportProfitLossController::class, 'index'])->name('profit-loss');
        Route::get('/balance-sheet', [ReportBalanceSheetController::class, 'index'])->name('balance-sheet');
        Route::get('/cash-flow', [ReportCashFlowController::class, 'index'])->name('cash-flow');
        Route::get('/project', [ReportProjectProfitabilityController::class, 'index'])->name('project');
        Route::get('/aging/{type}', [ReportAgingController::class, 'index'])->name('aging');
        Route::get('/tax', [ReportTaxSummaryController::class, 'index'])->name('tax');
        Route::get('/budget', [ReportBudgetVsActualController::class, 'index'])->name('budget');
        Route::get('/transactions', [ReportTransactionsController::class, 'index'])->name('transactions');
        Route::get('/reconciliation', [ReportBankReconciliationController::class, 'index'])->name('reconciliation');
        Route::get('/trial-balance', [ReportTrialBalanceController::class, 'index'])->name('trial-balance');
        Route::get('/general-ledger', [ReportGeneralLedgerController::class, 'index'])->name('general-ledger');
    });
    Route::get('/exports/cashflows',                 [ReportController::class, 'exportCashflows'])->middleware('role:admin,finance')->name('exports.cashflows');
    Route::get('/exports/project-finance',           [ReportController::class, 'exportProjectFinance'])->middleware('role:admin,finance')->name('exports.project-finance');

    // ── Admin: Company Settings ────────────────────────────────────────────────
    Route::get('/company-settings',                      [CompanySettingController::class, 'index'])->middleware('role:admin')->name('company-settings.index');
    Route::put('/company-settings',                      [CompanySettingController::class, 'update'])->middleware('role:admin')->name('company-settings.update');

    // ── Admin: User Management ─────────────────────────────────────────────────
    Route::get('/user-management',                       [UserManagementController::class, 'index'])->middleware('role:admin')->name('user-management.index');
    Route::get('/user-management/create',                [UserManagementController::class, 'create'])->middleware('role:admin')->name('user-management.create-page');
    Route::get('/user-management/{user}/edit',           [UserManagementController::class, 'edit'])->middleware('role:admin')->name('user-management.edit-page');
    Route::post('/user-management',                      [UserManagementController::class, 'store'])->middleware('role:admin')->name('user-management.store');
    Route::put('/user-management/{user}',                [UserManagementController::class, 'update'])->middleware('role:admin')->name('user-management.update');
    Route::delete('/user-management/{user}',             [UserManagementController::class, 'destroy'])->middleware('role:admin')->name('user-management.destroy');
    Route::patch('/user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->middleware('role:admin')->name('user-management.reset-password');

    // ── Admin: Clients ─────────────────────────────────────────────────────────
    Route::get('/clients',                               [ClientController::class, 'index'])->middleware('role:admin')->name('clients.index');
        Route::get('/clients/{client}',                     [ClientController::class, 'show'])->middleware('role:admin')->name('clients.show');
Route::get('/clients/create',                        [ClientController::class, 'create'])->middleware('role:admin')->name('clients.create-page');
    Route::get('/clients/{client}/edit',                 [ClientController::class, 'edit'])->middleware('role:admin')->name('clients.edit-page');
    Route::post('/clients',                              [ClientController::class, 'store'])->middleware('role:admin')->name('clients.store');
    Route::put('/clients/{client}',                      [ClientController::class, 'update'])->middleware('role:admin')->name('clients.update');
    Route::delete('/clients/{client}',                   [ClientController::class, 'destroy'])->middleware('role:admin')->name('clients.destroy');

    // ── Admin: Departments ─────────────────────────────────────────────────────
    Route::get('/departments',                           [DepartmentController::class, 'index'])->middleware('role:admin')->name('departments.index');
    Route::get('/departments/create',                    [DepartmentController::class, 'create'])->middleware('role:admin')->name('departments.create-page');
    Route::get('/departments/{department}/edit',         [DepartmentController::class, 'edit'])->middleware('role:admin')->name('departments.edit-page');
    Route::post('/departments',                          [DepartmentController::class, 'store'])->middleware('role:admin')->name('departments.store');
    Route::put('/departments/{department}',              [DepartmentController::class, 'update'])->middleware('role:admin')->name('departments.update');
    Route::delete('/departments/{department}',           [DepartmentController::class, 'destroy'])->middleware('role:admin')->name('departments.destroy');

    // ── Admin: Job Positions ───────────────────────────────────────────────────
    Route::get('/job-positions',                         [JobPositionController::class, 'index'])->middleware('role:admin')->name('job-positions.index');
    Route::get('/job-positions/create',                  [JobPositionController::class, 'create'])->middleware('role:admin')->name('job-positions.create-page');
    Route::get('/job-positions/{jobPosition}/edit',      [JobPositionController::class, 'edit'])->middleware('role:admin')->name('job-positions.edit-page');
    Route::post('/job-positions',                        [JobPositionController::class, 'store'])->middleware('role:admin')->name('job-positions.store');
    Route::put('/job-positions/{jobPosition}',           [JobPositionController::class, 'update'])->middleware('role:admin')->name('job-positions.update');
    Route::delete('/job-positions/{jobPosition}',        [JobPositionController::class, 'destroy'])->middleware('role:admin')->name('job-positions.destroy');

    // ── Admin: Expense Categories ──────────────────────────────────────────────
    Route::get('/expense-categories',                    [ExpenseCategoryController::class, 'index'])->middleware('role:admin')->name('expense-categories.index');
    Route::get('/expense-categories/create',             [ExpenseCategoryController::class, 'create'])->middleware('role:admin')->name('expense-categories.create-page');
    Route::get('/expense-categories/{expenseCategory}/edit', [ExpenseCategoryController::class, 'edit'])->middleware('role:admin')->name('expense-categories.edit-page');
    Route::post('/expense-categories',                   [ExpenseCategoryController::class, 'store'])->middleware('role:admin')->name('expense-categories.store');
    Route::put('/expense-categories/{expenseCategory}',  [ExpenseCategoryController::class, 'update'])->middleware('role:admin')->name('expense-categories.update');
    Route::delete('/expense-categories/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->middleware('role:admin')->name('expense-categories.destroy');

    // ── Admin: Bank Accounts ───────────────────────────────────────────────────
    Route::get('/bank-accounts',                         [BankAccountController::class, 'index'])->middleware('role:admin')->name('bank-accounts.index');
    Route::get('/bank-accounts/create',                  [BankAccountController::class, 'create'])->middleware('role:admin')->name('bank-accounts.create-page');
    Route::get('/bank-accounts/{bankAccount}/edit',      [BankAccountController::class, 'edit'])->middleware('role:admin')->name('bank-accounts.edit-page');
    Route::post('/bank-accounts',                        [BankAccountController::class, 'store'])->middleware('role:admin')->name('bank-accounts.store');
    Route::put('/bank-accounts/{bankAccount}',           [BankAccountController::class, 'update'])->middleware('role:admin')->name('bank-accounts.update');
    Route::delete('/bank-accounts/{bankAccount}',        [BankAccountController::class, 'destroy'])->middleware('role:admin')->name('bank-accounts.destroy');

    // ── Admin: Audit Log ───────────────────────────────────────────────────────
    Route::get('/audit-logs',                            [AuditLogController::class, 'index'])->middleware('role:admin')->name('audit-logs.index');

    // ── Admin: Trash ───────────────────────────────────────────────────────────
    Route::get('/trash',                                 [TrashController::class, 'index'])->middleware('role:admin')->name('trash.index');
    Route::patch('/trash/{type}/{id}/restore',           [TrashController::class, 'restore'])->middleware('role:admin')->name('trash.restore');

    // ── Admin: Backup ──────────────────────────────────────────────────────────
    Route::get('/backup/database',                       [BackupController::class, 'download'])->middleware('role:admin')->name('backup.download');

});
