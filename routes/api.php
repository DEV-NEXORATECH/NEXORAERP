<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CmsApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NEXORA ERP API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    Route::get('/cms/content', [CmsApiController::class, 'content'])->middleware('feature')->name('api.cms.content');
    Route::get('/cms/blog', [CmsApiController::class, 'blog'])->middleware('feature')->name('api.cms.blog');
    Route::get('/cms/blog/{slug}', [CmsApiController::class, 'showBlog'])->middleware('feature')->name('api.cms.blog.show');

    // ── Auth ────────────────────────────────────────────────────────────────
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'user'])->middleware('auth:sanctum')->name('api.auth.me');

    // ── Authenticated API Routes ────────────────────────────────────────────
    Route::middleware('auth:sanctum', 'feature')->as('api.')->group(function () {

        // ── Dashboard & Sub-Dashboards ───────────────────────────────────────
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/executive', [\App\Http\Controllers\DashboardController::class, 'executive'])->name('dashboard.executive');
        Route::get('/dashboard/sales', [\App\Http\Controllers\DashboardController::class, 'sales'])->name('dashboard.sales');
        Route::get('/dashboard/projects', [\App\Http\Controllers\DashboardController::class, 'projects'])->name('dashboard.projects');
        Route::get('/dashboard/hr', [\App\Http\Controllers\DashboardController::class, 'hr'])->name('dashboard.hr');
        Route::get('/dashboard/finance', [\App\Http\Controllers\DashboardController::class, 'finance'])->name('dashboard.finance');
        Route::get('/dashboard/procurement', [\App\Http\Controllers\DashboardController::class, 'procurement'])->name('dashboard.procurement');
        Route::get('/dashboard/inventory', [\App\Http\Controllers\DashboardController::class, 'inventory'])->name('dashboard.inventory');
        Route::get('/dashboard/cms', [\App\Http\Controllers\DashboardController::class, 'cms'])->name('dashboard.cms');
        Route::get('/dashboard/workspace', [\App\Http\Controllers\DashboardController::class, 'workspace'])->name('dashboard.workspace');

        // ── Projects ────────────────────────────────────────────────────────
        Route::apiResource('projects', \App\Http\Controllers\Api\ProjectApiController::class);

        // ── Proposals ───────────────────────────────────────────────────────
        Route::apiResource('proposals', \App\Http\Controllers\Api\ProposalApiController::class);
        Route::patch('/proposals/{proposal}/status', [\App\Http\Controllers\ProposalController::class, 'updateStatus'])->name('proposals.status');

        // ── Sales ───────────────────────────────────────────────────────────
        Route::apiResource('sales-inquiries', \App\Http\Controllers\Api\SalesInquiryApiController::class);
        Route::apiResource('sales-leads', \App\Http\Controllers\Api\SalesLeadApiController::class);
        Route::apiResource('sales-orders', \App\Http\Controllers\Api\SalesOrderApiController::class);
        Route::apiResource('sales-targets', \App\Http\Controllers\Api\SalesTargetApiController::class);
        Route::apiResource('sales-commissions', \App\Http\Controllers\Api\SalesCommissionApiController::class);
        Route::apiResource('client-contracts', \App\Http\Controllers\Api\ClientContractApiController::class);

        // ── HR: Employees ───────────────────────────────────────────────────
        Route::apiResource('employees', \App\Http\Controllers\Api\EmployeeApiController::class);
        Route::apiResource('employee-skills', \App\Http\Controllers\Api\EmployeeSkillApiController::class);
        Route::apiResource('attendances', \App\Http\Controllers\Api\AttendanceApiController::class);
        Route::apiResource('timesheets', \App\Http\Controllers\Api\TimesheetApiController::class);
        Route::apiResource('leave-requests', \App\Http\Controllers\Api\LeaveRequestApiController::class);
        Route::apiResource('performance-reviews', \App\Http\Controllers\Api\PerformanceReviewApiController::class);
        Route::apiResource('payroll-benefits', \App\Http\Controllers\Api\PayrollBenefitApiController::class);

        // ── HR: Salaries ────────────────────────────────────────────────────
        Route::apiResource('salaries', \App\Http\Controllers\Api\SalaryApiController::class);
        Route::patch('/salaries/{salary}/status', [\App\Http\Controllers\SalaryController::class, 'updateStatus'])->name('salaries.status');

        // ── Finance: Reimbursements ─────────────────────────────────────────
        Route::apiResource('reimbursements', \App\Http\Controllers\Api\ReimbursementApiController::class);
        Route::patch('/reimbursements/{reimbursement}/status', [\App\Http\Controllers\ReimbursementController::class, 'updateStatus'])->name('reimbursements.status');

        // ── Finance: Cashflows ──────────────────────────────────────────────
        Route::apiResource('cashflows', \App\Http\Controllers\Api\CashflowApiController::class);

        // ── Finance: Invoices ───────────────────────────────────────────────
        Route::apiResource('invoices', \App\Http\Controllers\Api\InvoiceApiController::class);

        // ── Finance: Payments ───────────────────────────────────────────────
        Route::apiResource('payments', \App\Http\Controllers\Api\PaymentApiController::class)->only(['store', 'destroy']);

        // ── Finance Suite ───────────────────────────────────────────────────
        Route::apiResource('chart-accounts', \App\Http\Controllers\Api\ChartAccountApiController::class);
        Route::apiResource('journal-entries', \App\Http\Controllers\Api\JournalEntryApiController::class);
        Route::apiResource('recurring-billings', \App\Http\Controllers\Api\RecurringBillingApiController::class);
        Route::apiResource('payment-reminders', \App\Http\Controllers\Api\PaymentReminderApiController::class);
        Route::apiResource('vendor-bills', \App\Http\Controllers\Api\VendorBillApiController::class);
        Route::apiResource('vendor-payments', \App\Http\Controllers\Api\VendorPaymentApiController::class);
        Route::apiResource('budgets', \App\Http\Controllers\Api\BudgetApiController::class);
        Route::apiResource('tax-rules', \App\Http\Controllers\Api\TaxRuleApiController::class);
        Route::apiResource('fixed-assets', \App\Http\Controllers\Api\FixedAssetApiController::class);
        Route::apiResource('currency-rates', \App\Http\Controllers\Api\CurrencyRateApiController::class);
        Route::apiResource('currency-variances', \App\Http\Controllers\Api\CurrencyVarianceApiController::class);
        Route::apiResource('revenue-schedules', \App\Http\Controllers\Api\RevenueScheduleApiController::class);
        Route::apiResource('bank-reconciliation-items', \App\Http\Controllers\Api\BankReconciliationItemApiController::class);
        Route::apiResource('purchase-matches', \App\Http\Controllers\Api\PurchaseMatchApiController::class);

        // ── Accounting Master ────────────────────────────────────────────────
        Route::apiResource('accounting-journals', \App\Http\Controllers\Api\AccountingJournalApiController::class);
        Route::apiResource('payment-terms', \App\Http\Controllers\Api\PaymentTermApiController::class);
        Route::apiResource('currencies', \App\Http\Controllers\Api\CurrencyApiController::class);
        Route::apiResource('fiscal-periods', \App\Http\Controllers\Api\FiscalPeriodApiController::class);

        // ── Procurement ─────────────────────────────────────────────────────
        Route::apiResource('vendors', \App\Http\Controllers\Api\VendorApiController::class);
        Route::apiResource('purchase-requisitions', \App\Http\Controllers\Api\PurchaseRequisitionApiController::class);
        Route::apiResource('purchase-orders', \App\Http\Controllers\Api\PurchaseOrderApiController::class);
        Route::apiResource('goods-receipts', \App\Http\Controllers\Api\GoodsReceiptApiController::class);
        Route::apiResource('procurement-contracts', \App\Http\Controllers\Api\ProcurementContractApiController::class);

        // ── Inventory ───────────────────────────────────────────────────────
        Route::apiResource('products', \App\Http\Controllers\Api\ProductApiController::class);
        Route::get('products-stock', [\App\Http\Controllers\Api\StockMovementApiController::class, 'stock'])->name('products-stock');
        Route::apiResource('product-categories', \App\Http\Controllers\Api\ProductCategoryApiController::class);
        Route::apiResource('product-units', \App\Http\Controllers\Api\UnitOfMeasureApiController::class);
        Route::apiResource('warehouses', \App\Http\Controllers\Api\WarehouseApiController::class);
        Route::apiResource('stock-locations', \App\Http\Controllers\Api\StockLocationApiController::class);
        Route::apiResource('stock-movements', \App\Http\Controllers\Api\StockMovementApiController::class);
        Route::apiResource('stock-adjustments', \App\Http\Controllers\Api\StockAdjustmentApiController::class);
        Route::post('stock-adjustments/{adjustment}/post', [\App\Http\Controllers\Api\StockAdjustmentApiController::class, 'post'])->name('stock-adjustments.post');

        // ── Feature Flags (admin only — deliberately not feature-gated) ──────
        Route::get('/feature-flags', [\App\Http\Controllers\Api\FeatureFlagApiController::class, 'index'])->middleware('role:admin')->name('feature-flags');
        Route::post('/feature-flags', [\App\Http\Controllers\Api\FeatureFlagApiController::class, 'store'])->middleware('role:admin')->name('feature-flags.store');
        Route::post('/feature-flags/toggle', [\App\Http\Controllers\Api\FeatureFlagApiController::class, 'toggle'])->middleware('role:admin')->name('feature-flags.toggle');
        Route::put('/feature-flags/{flag}', [\App\Http\Controllers\Api\FeatureFlagApiController::class, 'update'])->middleware('role:admin')->name('feature-flags.update');
        Route::delete('/feature-flags/{flag}', [\App\Http\Controllers\Api\FeatureFlagApiController::class, 'destroy'])->middleware('role:admin')->name('feature-flags.destroy');

        // ── Admin ───────────────────────────────────────────────────────────
        Route::get('/companies', [\App\Http\Controllers\Api\CompanyApiController::class, 'index'])->name('companies');
        Route::get('/company-settings', [\App\Http\Controllers\CompanySettingController::class, 'index'])->name('company-settings.show');
        Route::put('/company-settings', [\App\Http\Controllers\CompanySettingController::class, 'update'])->name('company-settings.update');
        Route::get('/cms/sections', [CmsApiController::class, 'sections'])->name('cms.sections');
        Route::put('/cms/sections/{section}', [CmsApiController::class, 'updateSection'])->name('cms.sections.update');
        Route::get('/cms/blog-posts', [CmsApiController::class, 'blog'])->name('cms.blog-posts');
        Route::post('/cms/blog', [CmsApiController::class, 'storeBlog'])->name('cms.blog.store');
        Route::put('/cms/blog/{post}', [CmsApiController::class, 'updateBlog'])->name('cms.blog.update');
        Route::delete('/cms/blog/{post}', [CmsApiController::class, 'destroyBlog'])->name('cms.blog.destroy');

        Route::apiResource('users', \App\Http\Controllers\Api\UserApiController::class);
        Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\UserManagementController::class, 'resetPassword'])->name('users.reset-password');

        Route::apiResource('clients', \App\Http\Controllers\Api\ClientApiController::class);
        Route::apiResource('departments', \App\Http\Controllers\Api\DepartmentApiController::class);
        Route::apiResource('job-positions', \App\Http\Controllers\Api\JobPositionApiController::class);
        Route::apiResource('expense-categories', \App\Http\Controllers\Api\ExpenseCategoryApiController::class);
        Route::apiResource('bank-accounts', \App\Http\Controllers\Api\BankAccountApiController::class);

        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs');

        Route::get('/trash', [\App\Http\Controllers\TrashController::class, 'index'])->name('trash');
        Route::patch('/trash/{type}/{id}/restore', [\App\Http\Controllers\TrashController::class, 'restore'])->name('trash.restore');

        // ── Tasks ───────────────────────────────────────────────────────────
        Route::apiResource('tasks', \App\Http\Controllers\Api\TaskApiController::class)->only(['store', 'update', 'destroy']);
        Route::patch('/tasks/{task}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.status');

        // ── Reports ─────────────────────────────────────────────────────────
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
            Route::get('/profit-loss', [\App\Http\Controllers\ReportProfitLossController::class, 'index'])->name('profit-loss');
            Route::get('/balance-sheet', [\App\Http\Controllers\ReportBalanceSheetController::class, 'index'])->name('balance-sheet');
            Route::get('/cash-flow', [\App\Http\Controllers\ReportCashFlowController::class, 'index'])->name('cash-flow');
            Route::get('/project', [\App\Http\Controllers\ReportProjectProfitabilityController::class, 'index'])->name('project');
            Route::get('/aging/{type}', [\App\Http\Controllers\ReportAgingController::class, 'index'])->name('aging');
            Route::get('/tax', [\App\Http\Controllers\ReportTaxSummaryController::class, 'index'])->name('tax');
            Route::get('/budget', [\App\Http\Controllers\ReportBudgetVsActualController::class, 'index'])->name('budget');
            Route::get('/transactions', [\App\Http\Controllers\ReportTransactionsController::class, 'index'])->name('transactions');
            Route::get('/reconciliation', [\App\Http\Controllers\ReportBankReconciliationController::class, 'index'])->name('reconciliation');
            Route::get('/trial-balance', [\App\Http\Controllers\ReportTrialBalanceController::class, 'index'])->name('trial-balance');
            Route::get('/general-ledger', [\App\Http\Controllers\ReportGeneralLedgerController::class, 'index'])->name('general-ledger');
        });
    });
});
