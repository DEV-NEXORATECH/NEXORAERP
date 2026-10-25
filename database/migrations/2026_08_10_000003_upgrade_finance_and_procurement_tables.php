<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('currency_variances', function (Blueprint $table) {
            if (! Schema::hasColumn('currency_variances', 'rate_id')) {
                $table->unsignedBigInteger('rate_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('currency_variances', 'variance_percent')) {
                $table->decimal('variance_percent', 8, 4)->default(0)->after('rate_id');
            }
            if (! Schema::hasColumn('currency_variances', 'period')) {
                $table->string('period', 20)->nullable()->after('variance_amount');
            }
            if (! Schema::hasColumn('currency_variances', 'notes')) {
                $table->text('notes')->nullable()->after('period');
            }
        });

        Schema::table('revenue_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('revenue_schedules', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('revenue_schedules', 'schedule_date')) {
                $table->date('schedule_date')->nullable()->after('project_id');
            }
            if (! Schema::hasColumn('revenue_schedules', 'status')) {
                $table->string('status', 50)->default('scheduled')->after('amount');
            }
            if (! Schema::hasColumn('revenue_schedules', 'title')) {
                $table->string('title', 255)->nullable()->after('status');
            }
            if (! Schema::hasColumn('revenue_schedules', 'notes')) {
                $table->text('notes')->nullable()->after('title');
            }
        });

        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            if (! Schema::hasColumn('bank_reconciliation_items', 'description')) {
                $table->string('description', 255)->nullable()->after('statement_date');
            }
            if (! Schema::hasColumn('bank_reconciliation_items', 'reference')) {
                $table->string('reference', 100)->nullable()->after('description');
            }
            if (! Schema::hasColumn('bank_reconciliation_items', 'amount')) {
                $table->decimal('amount', 15, 2)->default(0)->after('reference');
            }
            if (! Schema::hasColumn('bank_reconciliation_items', 'type')) {
                $table->string('type', 50)->default('credit')->after('amount');
            }
            if (! Schema::hasColumn('bank_reconciliation_items', 'reconciled')) {
                $table->boolean('reconciled')->default(false)->after('type');
            }
            if (! Schema::hasColumn('bank_reconciliation_items', 'reconciled_at')) {
                $table->timestamp('reconciled_at')->nullable()->after('reconciled');
            }
        });

        Schema::table('purchase_matches', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_matches', 'match_status')) {
                $table->string('match_status', 50)->default('matched')->after('variance_amount');
            }
            if (! Schema::hasColumn('purchase_matches', 'matched_at')) {
                $table->timestamp('matched_at')->nullable()->after('match_status');
            }
        });
    }

    public function down(): void
    {
        // Non-destructive down - no drop on production
    }
};
