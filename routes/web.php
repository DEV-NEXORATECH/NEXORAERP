<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ErpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ErpController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.store');
    Route::get('/projects', [ErpController::class, 'projectsPage'])->name('projects.index');
    Route::get('/projects/create', [ErpController::class, 'projectCreatePage'])->middleware('role:admin,sales')->name('projects.create-page');
    Route::get('/projects/{project}/edit', [ErpController::class, 'projectEditPage'])->middleware('role:admin,sales')->name('projects.edit-page');
    Route::get('/sales', [ErpController::class, 'salesPage'])->middleware('role:admin,sales')->name('sales.index');
    Route::get('/sales/proposals/create', [ErpController::class, 'proposalCreatePage'])->middleware('role:admin,sales')->name('proposals.create-page');
    Route::get('/proposals/{proposal}/edit', [ErpController::class, 'proposalEditPage'])->middleware('role:admin,sales')->name('proposals.edit-page');
    Route::get('/hr', [ErpController::class, 'hrPage'])->middleware('role:admin,hr')->name('hr.index');
    Route::get('/hr/salaries', [ErpController::class, 'salariesPage'])->middleware('role:admin,hr')->name('salaries.index-page');
    Route::get('/employees/create', [ErpController::class, 'employeeCreatePage'])->middleware('role:admin,hr')->name('employees.create-page');
    Route::get('/employees/{employee}/edit', [ErpController::class, 'employeeEditPage'])->middleware('role:admin,hr')->name('employees.edit-page');
    Route::get('/salaries/create', [ErpController::class, 'salaryCreatePage'])->middleware('role:admin,hr')->name('salaries.create-page');
    Route::get('/salaries/{salary}/edit', [ErpController::class, 'salaryEditPage'])->middleware('role:admin,hr')->name('salaries.edit-page');
    Route::get('/finance', [ErpController::class, 'financePage'])->middleware('role:admin,finance')->name('finance.index');
    Route::get('/finance/reimbursements', [ErpController::class, 'reimbursementsPage'])->middleware('role:admin,finance,hr')->name('reimbursements.index-page');
    Route::get('/finance/cashflows', [ErpController::class, 'cashflowsPage'])->middleware('role:admin,finance')->name('cashflows.index-page');
    Route::get('/reimbursements/create', [ErpController::class, 'reimbursementCreatePage'])->middleware('role:admin,finance,hr')->name('reimbursements.create-page');
    Route::get('/reimbursements/{reimbursement}/edit', [ErpController::class, 'reimbursementEditPage'])->middleware('role:admin,finance,hr')->name('reimbursements.edit-page');
    Route::get('/cashflows/create', [ErpController::class, 'cashflowCreatePage'])->middleware('role:admin,finance')->name('cashflows.create-page');
    Route::get('/cashflows/{cashflow}/edit', [ErpController::class, 'cashflowEditPage'])->middleware('role:admin,finance')->name('cashflows.edit-page');
    Route::get('/invoices/create', [ErpController::class, 'invoiceCreatePage'])->middleware('role:admin,finance')->name('invoices.create-page');
    Route::get('/invoices/{invoice}/edit', [ErpController::class, 'invoiceEditPage'])->middleware('role:admin,finance')->name('invoices.edit-page');
    Route::get('/reports', [ErpController::class, 'reportsPage'])->middleware('role:admin,finance')->name('reports.index');
    Route::get('/admin', [ErpController::class, 'adminPage'])->middleware('role:admin')->name('admin.index');
    Route::get('/admin/users', [ErpController::class, 'usersPage'])->middleware('role:admin')->name('admin.users');
    Route::get('/admin/masters', [ErpController::class, 'mastersPage'])->middleware('role:admin')->name('admin.masters');
    Route::get('/admin/trash', [ErpController::class, 'trashPage'])->middleware('role:admin')->name('admin.trash');
    Route::get('/admin/audit', [ErpController::class, 'auditPage'])->middleware('role:admin')->name('admin.audit');
    Route::get('/projects/{project}', [ErpController::class, 'project'])->name('projects.show');
    Route::get('/exports/cashflows', [ErpController::class, 'exportCashflows'])->middleware('role:admin,finance')->name('exports.cashflows');
    Route::get('/exports/project-finance', [ErpController::class, 'exportProjectFinance'])->middleware('role:admin,finance')->name('exports.project-finance');
    Route::get('/backup/database', [ErpController::class, 'backupDatabase'])->middleware('role:admin')->name('backup.database');
    Route::get('/proposals/{proposal}/pdf', [ErpController::class, 'proposalPdf'])->middleware('role:admin,sales,finance')->name('proposals.pdf');
    Route::get('/salaries/{salary}/pdf', [ErpController::class, 'salaryPdf'])->middleware('role:admin,hr,finance')->name('salaries.pdf');
    Route::get('/invoices/{invoice}/pdf', [ErpController::class, 'invoicePdf'])->middleware('role:admin,finance,sales')->name('invoices.pdf');

    Route::post('/projects', [ErpController::class, 'storeProject'])->middleware('role:admin,sales')->name('projects.store');
    Route::put('/projects/{project}', [ErpController::class, 'updateProject'])->middleware('role:admin,sales')->name('projects.update');
    Route::delete('/projects/{project}', [ErpController::class, 'destroyProject'])->middleware('role:admin')->name('projects.destroy');

    Route::post('/proposals', [ErpController::class, 'storeProposal'])->middleware('role:admin,sales')->name('proposals.store');
    Route::put('/proposals/{proposal}', [ErpController::class, 'updateProposal'])->middleware('role:admin,sales')->name('proposals.update');
    Route::patch('/proposals/{proposal}/status', [ErpController::class, 'updateProposalStatus'])->middleware('role:admin,sales')->name('proposals.status');
    Route::delete('/proposals/{proposal}', [ErpController::class, 'destroyProposal'])->middleware('role:admin')->name('proposals.destroy');

    Route::post('/employees', [ErpController::class, 'storeEmployee'])->middleware('role:admin,hr')->name('employees.store');
    Route::put('/employees/{employee}', [ErpController::class, 'updateEmployee'])->middleware('role:admin,hr')->name('employees.update');
    Route::delete('/employees/{employee}', [ErpController::class, 'destroyEmployee'])->middleware('role:admin')->name('employees.destroy');

    Route::post('/salaries', [ErpController::class, 'storeSalary'])->middleware('role:admin,hr')->name('salaries.store');
    Route::put('/salaries/{salary}', [ErpController::class, 'updateSalary'])->middleware('role:admin,hr')->name('salaries.update');
    Route::patch('/salaries/{salary}/status', [ErpController::class, 'updateSalaryStatus'])->middleware('role:admin,hr,finance')->name('salaries.status');
    Route::delete('/salaries/{salary}', [ErpController::class, 'destroySalary'])->middleware('role:admin')->name('salaries.destroy');

    Route::post('/reimbursements', [ErpController::class, 'storeReimbursement'])->middleware('role:admin,hr,finance')->name('reimbursements.store');
    Route::put('/reimbursements/{reimbursement}', [ErpController::class, 'updateReimbursement'])->middleware('role:admin,hr,finance')->name('reimbursements.update');
    Route::patch('/reimbursements/{reimbursement}/status', [ErpController::class, 'updateReimbursementStatus'])->middleware('role:admin,hr,finance')->name('reimbursements.status');
    Route::delete('/reimbursements/{reimbursement}', [ErpController::class, 'destroyReimbursement'])->middleware('role:admin')->name('reimbursements.destroy');

    Route::post('/cashflows', [ErpController::class, 'storeCashflow'])->middleware('role:admin,finance')->name('cashflows.store');
    Route::put('/cashflows/{cashflow}', [ErpController::class, 'updateCashflow'])->middleware('role:admin,finance')->name('cashflows.update');
    Route::delete('/cashflows/{cashflow}', [ErpController::class, 'destroyCashflow'])->middleware('role:admin')->name('cashflows.destroy');

    Route::post('/invoices', [ErpController::class, 'storeInvoice'])->middleware('role:admin,finance')->name('invoices.store');
    Route::put('/invoices/{invoice}', [ErpController::class, 'updateInvoice'])->middleware('role:admin,finance')->name('invoices.update');
    Route::delete('/invoices/{invoice}', [ErpController::class, 'destroyInvoice'])->middleware('role:admin')->name('invoices.destroy');
    Route::get('/payments/create', [ErpController::class, 'paymentCreatePage'])->middleware('role:admin,finance')->name('payments.create-page');
    Route::post('/payments', [ErpController::class, 'storePayment'])->middleware('role:admin,finance')->name('payments.store');
    Route::delete('/payments/{payment}', [ErpController::class, 'destroyPayment'])->middleware('role:admin')->name('payments.destroy');

    Route::post('/masters/{type}', [ErpController::class, 'storeMaster'])->middleware('role:admin')->name('masters.store');
    Route::delete('/masters/{type}/{id}', [ErpController::class, 'destroyMaster'])->middleware('role:admin')->name('masters.destroy');
    Route::post('/users', [ErpController::class, 'storeUser'])->middleware('role:admin')->name('users.store');
    Route::put('/users/{user}', [ErpController::class, 'updateUser'])->middleware('role:admin')->name('users.update');
    Route::patch('/users/{user}/reset-password', [ErpController::class, 'resetUserPassword'])->middleware('role:admin')->name('users.reset-password');
    Route::delete('/users/{user}', [ErpController::class, 'destroyUser'])->middleware('role:admin')->name('users.destroy');
    Route::post('/company-setting', [ErpController::class, 'updateCompanySetting'])->middleware('role:admin')->name('company-setting.update');
    Route::patch('/trash/{type}/{id}/restore', [ErpController::class, 'restoreTrash'])->middleware('role:admin')->name('trash.restore');
});
