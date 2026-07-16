<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NEXORA ERP API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── Auth ────────────────────────────────────────────────────────────────
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'user'])->middleware('auth:sanctum')->name('api.auth.me');

    // ── Authenticated API Routes ────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // ── Dashboard ───────────────────────────────────────────────────────
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);

        // ── Projects ────────────────────────────────────────────────────────
        Route::apiResource('projects', \App\Http\Controllers\Api\ProjectApiController::class);

        // ── Proposals ───────────────────────────────────────────────────────
        Route::apiResource('proposals', \App\Http\Controllers\Api\ProposalApiController::class);
        Route::patch('/proposals/{proposal}/status', [\App\Http\Controllers\ProposalController::class, 'updateStatus']);

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
        Route::patch('/salaries/{salary}/status', [\App\Http\Controllers\SalaryController::class, 'updateStatus']);

        // ── Finance: Reimbursements ─────────────────────────────────────────
        Route::apiResource('reimbursements', \App\Http\Controllers\Api\ReimbursementApiController::class);
        Route::patch('/reimbursements/{reimbursement}/status', [\App\Http\Controllers\ReimbursementController::class, 'updateStatus']);

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

        // ── Procurement ─────────────────────────────────────────────────────
        Route::apiResource('vendors', \App\Http\Controllers\Api\VendorApiController::class);
        Route::apiResource('purchase-requisitions', \App\Http\Controllers\Api\PurchaseRequisitionApiController::class);
        Route::apiResource('purchase-orders', \App\Http\Controllers\Api\PurchaseOrderApiController::class);
        Route::apiResource('goods-receipts', \App\Http\Controllers\Api\GoodsReceiptApiController::class);
        Route::apiResource('procurement-contracts', \App\Http\Controllers\Api\ProcurementContractApiController::class);

        // ── Admin ───────────────────────────────────────────────────────────
        Route::get('/companies', [\App\Http\Controllers\Api\CompanyApiController::class, 'index']);
        Route::get('/company-settings', [\App\Http\Controllers\CompanySettingController::class, 'index']);
        Route::put('/company-settings', [\App\Http\Controllers\CompanySettingController::class, 'update']);

        Route::apiResource('users', \App\Http\Controllers\Api\UserApiController::class);
        Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\UserManagementController::class, 'resetPassword']);

        Route::apiResource('clients', \App\Http\Controllers\Api\ClientApiController::class);
        Route::apiResource('departments', \App\Http\Controllers\Api\DepartmentApiController::class);
        Route::apiResource('job-positions', \App\Http\Controllers\Api\JobPositionApiController::class);
        Route::apiResource('expense-categories', \App\Http\Controllers\Api\ExpenseCategoryApiController::class);
        Route::apiResource('bank-accounts', \App\Http\Controllers\Api\BankAccountApiController::class);

        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index']);

        Route::get('/trash', [\App\Http\Controllers\TrashController::class, 'index']);
        Route::patch('/trash/{type}/{id}/restore', [\App\Http\Controllers\TrashController::class, 'restore']);

        // ── Tasks ───────────────────────────────────────────────────────────
        Route::apiResource('tasks', \App\Http\Controllers\Api\TaskApiController::class)->only(['store', 'update', 'destroy']);
        Route::patch('/tasks/{task}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus']);

        // ── Reports ─────────────────────────────────────────────────────────
        Route::prefix('reports')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'index']);
            Route::get('/profit-loss', [\App\Http\Controllers\ReportProfitLossController::class, 'index']);
            Route::get('/balance-sheet', [\App\Http\Controllers\ReportBalanceSheetController::class, 'index']);
            Route::get('/cash-flow', [\App\Http\Controllers\ReportCashFlowController::class, 'index']);
            Route::get('/project', [\App\Http\Controllers\ReportProjectProfitabilityController::class, 'index']);
            Route::get('/aging/{type}', [\App\Http\Controllers\ReportAgingController::class, 'index']);
            Route::get('/tax', [\App\Http\Controllers\ReportTaxSummaryController::class, 'index']);
            Route::get('/budget', [\App\Http\Controllers\ReportBudgetVsActualController::class, 'index']);
            Route::get('/transactions', [\App\Http\Controllers\ReportTransactionsController::class, 'index']);
            Route::get('/reconciliation', [\App\Http\Controllers\ReportBankReconciliationController::class, 'index']);
        });
    });
});
