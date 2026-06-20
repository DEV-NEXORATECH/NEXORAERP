<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->string('need')->nullable();
            $table->string('status')->default('new');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('stage')->default('qualified');
            $table->decimal('value', 15, 2)->default(0);
            $table->unsignedTinyInteger('probability')->default(30);
            $table->date('expected_close_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number')->unique();
            $table->string('title');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('order_date');
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('period');
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('sales_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('period');
            $table->decimal('base_amount', 15, 2)->default(0);
            $table->decimal('rate', 8, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('client_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('reminder_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('skill');
            $table->string('level')->default('basic');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('work_mode')->default('office');
            $table->string('status')->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->date('work_date');
            $table->decimal('hours', 8, 2)->default(0);
            $table->string('status')->default('submitted');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('annual');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('period');
            $table->unsignedTinyInteger('kpi_score')->default(0);
            $table->unsignedTinyInteger('okr_score')->default(0);
            $table->string('rating')->default('meeting');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('period');
            $table->decimal('bpjs_health', 15, 2)->default(0);
            $table->decimal('bpjs_employment', 15, 2)->default(0);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('incentive', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number')->unique();
            $table->string('title');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('required_date')->nullable();
            $table->string('status')->default('draft');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number')->unique();
            $table->date('order_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('ordered');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->date('receipt_date');
            $table->string('status')->default('received');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('procurement_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('contract_number')->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('renewal_reminder_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_cost', 15, 2)->default(0);
            $table->unsignedInteger('useful_life_months')->default(36);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency', 3);
            $table->date('rate_date');
            $table->decimal('rate_to_idr', 15, 4);
            $table->timestamps();
            $table->unique(['currency', 'rate_date']);
        });

        Schema::create('currency_variances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->string('currency', 3)->default('USD');
            $table->decimal('foreign_amount', 15, 2)->default(0);
            $table->decimal('invoice_rate', 15, 4)->default(0);
            $table->decimal('payment_rate', 15, 4)->default(0);
            $table->decimal('variance_amount', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('revenue_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->string('recognition_month');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });

        Schema::create('bank_reconciliation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cashflow_id')->nullable()->constrained()->nullOnDelete();
            $table->date('statement_date');
            $table->string('statement_reference')->nullable();
            $table->decimal('statement_amount', 15, 2)->default(0);
            $table->string('match_status')->default('unmatched');
            $table->timestamps();
        });

        Schema::create('purchase_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('goods_receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_bill_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('variance_amount', 15, 2)->default(0);
            $table->string('status')->default('matched');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'purchase_matches',
            'bank_reconciliation_items',
            'revenue_schedules',
            'currency_variances',
            'currency_rates',
            'fixed_assets',
            'procurement_contracts',
            'goods_receipts',
            'purchase_orders',
            'purchase_requisitions',
            'vendors',
            'payroll_benefits',
            'performance_reviews',
            'leave_requests',
            'timesheets',
            'attendances',
            'employee_skills',
            'client_contracts',
            'sales_commissions',
            'sales_targets',
            'sales_orders',
            'sales_leads',
            'sales_inquiries',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
