<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->boolean('must_change_password')->default(false)->after('is_active');
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('must_change_password');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            $table->timestamp('password_changed_at')->nullable()->after('locked_until');
            $table->softDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('contract_file_path')->nullable()->after('contract_value');
            $table->softDeletes();
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->string('number')->nullable()->unique()->after('id');
            $table->string('signed_file_path')->nullable()->after('valid_until');
            $table->softDeletes();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->string('slip_number')->nullable()->unique()->after('id');
            $table->softDeletes();
        });

        Schema::table('reimbursements', function (Blueprint $table) {
            $table->string('receipt_file_path')->nullable()->after('cashflow_id');
            $table->softDeletes();
        });

        Schema::table('cashflows', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->default(0)->after('paid_amount');
            $table->text('payment_terms')->nullable()->after('notes');
            $table->softDeletes();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_file_path')->nullable()->after('reference');
            $table->softDeletes();
        });

        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('NEXORA');
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('npwp')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('signature_name')->nullable();
            $table->foreignId('default_bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
