<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'employees',
        'projects',
        'proposals',
        'salaries',
        'reimbursements',
        'cashflows',
        'clients',
        'departments',
        'job_positions',
        'expense_categories',
        'bank_accounts',
        'invoices',
        'payments',
        'audit_logs',
        'chart_accounts',
        'journal_entries',
        'journal_lines',
        'recurring_billings',
        'payment_reminders',
        'vendor_bills',
        'vendor_payments',
        'budgets',
        'tax_rules',
        'tasks',
        'sales_inquiries',
        'sales_leads',
        'sales_orders',
        'sales_targets',
        'sales_commissions',
        'client_contracts',
        'employee_skills',
        'attendances',
        'timesheets',
        'leave_requests',
        'performance_reviews',
        'payroll_benefits',
        'vendors',
        'purchase_requisitions',
        'purchase_orders',
        'goods_receipts',
        'procurement_contracts',
        'fixed_assets',
        'currency_rates',
        'currency_variances',
        'revenue_schedules',
        'bank_reconciliation_items',
        'purchase_matches',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $t) use ($table): void {
                    $t->foreignId('company_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
                    $t->index('company_id');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $t) use ($table): void {
                    $t->dropForeign(['company_id']);
                    $t->dropIndex(['company_id']);
                    $t->dropColumn('company_id');
                });
            }
        }
    }
};
