<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Route;

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',          [AuthController::class, 'login'])->name('login.store');
Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',       [AuthController::class, 'register'])->name('register.store');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('password.forgot.store');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password',  [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.store');

    // ── Dashboard ─────────────────────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/sales/proposals/create',            [ProposalController::class, 'create'])->middleware('role:admin,sales')->name('proposals.create-page');
    Route::get('/proposals/{proposal}/edit',         [ProposalController::class, 'edit'])->middleware('role:admin,sales')->name('proposals.edit-page');
    Route::get('/proposals/{proposal}/pdf',          [ProposalController::class, 'pdf'])->middleware('role:admin,sales,finance')->name('proposals.pdf');
    Route::post('/proposals',                        [ProposalController::class, 'store'])->middleware('role:admin,sales')->name('proposals.store');
    Route::put('/proposals/{proposal}',              [ProposalController::class, 'update'])->middleware('role:admin,sales')->name('proposals.update');
    Route::patch('/proposals/{proposal}/status',     [ProposalController::class, 'updateStatus'])->middleware('role:admin,sales')->name('proposals.status');
    Route::delete('/proposals/{proposal}',           [ProposalController::class, 'destroy'])->middleware('role:admin')->name('proposals.destroy');

    // ── Employees / HR ────────────────────────────────────────────────────────
    Route::get('/hr',                                [EmployeeController::class, 'index'])->middleware('role:admin,hr')->name('hr.index');
    Route::get('/employees/create',                  [EmployeeController::class, 'create'])->middleware('role:admin,hr')->name('employees.create-page');
    Route::get('/employees/{employee}/edit',         [EmployeeController::class, 'edit'])->middleware('role:admin,hr')->name('employees.edit-page');
    Route::post('/employees',                        [EmployeeController::class, 'store'])->middleware('role:admin,hr')->name('employees.store');
    Route::put('/employees/{employee}',              [EmployeeController::class, 'update'])->middleware('role:admin,hr')->name('employees.update');
    Route::delete('/employees/{employee}',           [EmployeeController::class, 'destroy'])->middleware('role:admin')->name('employees.destroy');

    // ── Salaries ──────────────────────────────────────────────────────────────
    Route::get('/hr/salaries',                       [SalaryController::class, 'index'])->middleware('role:admin,hr')->name('salaries.index-page');
    Route::get('/salaries/create',                   [SalaryController::class, 'create'])->middleware('role:admin,hr')->name('salaries.create-page');
    Route::get('/salaries/{salary}/edit',            [SalaryController::class, 'edit'])->middleware('role:admin,hr')->name('salaries.edit-page');
    Route::get('/salaries/{salary}/pdf',             [SalaryController::class, 'pdf'])->middleware('role:admin,hr,finance')->name('salaries.pdf');
    Route::post('/salaries',                         [SalaryController::class, 'store'])->middleware('role:admin,hr')->name('salaries.store');
    Route::put('/salaries/{salary}',                 [SalaryController::class, 'update'])->middleware('role:admin,hr')->name('salaries.update');
    Route::patch('/salaries/{salary}/status',        [SalaryController::class, 'updateStatus'])->middleware('role:admin,hr,finance')->name('salaries.status');
    Route::delete('/salaries/{salary}',              [SalaryController::class, 'destroy'])->middleware('role:admin')->name('salaries.destroy');

    // ── Reimbursements ────────────────────────────────────────────────────────
    Route::get('/finance/reimbursements',                    [ReimbursementController::class, 'index'])->middleware('role:admin,finance,hr')->name('reimbursements.index-page');
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

    // ── Reports & Exports ─────────────────────────────────────────────────────
    Route::get('/reports',                           [ReportController::class, 'index'])->middleware('role:admin,finance')->name('reports.index');
    Route::get('/exports/cashflows',                 [ReportController::class, 'exportCashflows'])->middleware('role:admin,finance')->name('exports.cashflows');
    Route::get('/exports/project-finance',           [ReportController::class, 'exportProjectFinance'])->middleware('role:admin,finance')->name('exports.project-finance');

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::get('/admin',                             [AdminController::class, 'companyPage'])->middleware('role:admin')->name('admin.index');
    Route::get('/admin/users',                       [AdminController::class, 'usersPage'])->middleware('role:admin')->name('admin.users');
    Route::get('/admin/masters',                     [AdminController::class, 'mastersPage'])->middleware('role:admin')->name('admin.masters');
    Route::get('/admin/trash',                       [AdminController::class, 'trashPage'])->middleware('role:admin')->name('admin.trash');
    Route::get('/admin/audit',                       [AdminController::class, 'auditPage'])->middleware('role:admin')->name('admin.audit');
    Route::get('/backup/database',                   [AdminController::class, 'backupDatabase'])->middleware('role:admin')->name('backup.database');

    Route::post('/company-setting',                  [AdminController::class, 'updateCompanySetting'])->middleware('role:admin')->name('company-setting.update');
    Route::post('/users',                            [AdminController::class, 'storeUser'])->middleware('role:admin')->name('users.store');
    Route::put('/users/{user}',                      [AdminController::class, 'updateUser'])->middleware('role:admin')->name('users.update');
    Route::patch('/users/{user}/reset-password',     [AdminController::class, 'resetUserPassword'])->middleware('role:admin')->name('users.reset-password');
    Route::delete('/users/{user}',                   [AdminController::class, 'destroyUser'])->middleware('role:admin')->name('users.destroy');
    Route::post('/masters/{type}',                   [AdminController::class, 'storeMaster'])->middleware('role:admin')->name('masters.store');
    Route::delete('/masters/{type}/{id}',            [AdminController::class, 'destroyMaster'])->middleware('role:admin')->name('masters.destroy');
    Route::patch('/trash/{type}/{id}/restore',       [AdminController::class, 'restoreTrash'])->middleware('role:admin')->name('trash.restore');

});
