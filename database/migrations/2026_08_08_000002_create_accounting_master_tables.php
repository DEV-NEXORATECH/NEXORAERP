<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Accounting Journals (OCA account.journal) ───────────────────────
        Schema::create('accounting_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type')->default('general');
            $table->foreignId('default_chart_account_id')->nullable()->constrained('chart_accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('company_id');
        });

        // ── Payment Terms (OCA account.payment.term) ────────────────────────
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('due_days')->default(0);
            $table->string('due_type')->default('day');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('company_id');
        });

        // ── Currencies (OCA res.currency) ───────────────────────────────────
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code', 8)->unique();
            $table->string('name');
            $table->string('symbol', 8)->nullable();
            $table->decimal('rate', 15, 6)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('company_id');
        });

        // ── Fiscal Periods (OCA account.fiscal.year/period) ─────────────────
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('open');
            $table->timestamps();
            $table->index('company_id');
        });

        // ── Extend existing accounting tables ───────────────────────────────
        if (! Schema::hasColumn('journal_entries', 'journal_id')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->foreignId('journal_id')->nullable()->after('number')->constrained('accounting_journals')->nullOnDelete();
                $table->foreignId('currency_id')->nullable()->after('reference')->constrained('currencies')->nullOnDelete();
                $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_id');
                $table->boolean('is_posted')->default(false)->after('memo');
                $table->date('posting_date')->nullable()->after('is_posted');
            });
        }

        if (! Schema::hasColumn('chart_accounts', 'currency_id')) {
            Schema::table('chart_accounts', function (Blueprint $table) {
                $table->foreignId('currency_id')->nullable()->after('parent_id')->constrained('currencies')->nullOnDelete();
                $table->string('account_group')->nullable()->after('type');
            });
        }

        if (! Schema::hasColumn('invoices', 'currency_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('currency_id')->nullable()->after('paid_amount')->constrained('currencies')->nullOnDelete();
                $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_id');
                $table->foreignId('payment_term_id')->nullable()->after('exchange_rate')->constrained('payment_terms')->nullOnDelete();
                $table->foreignId('journal_id')->nullable()->after('payment_term_id')->constrained('accounting_journals')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('vendor_bills', 'currency_id')) {
            Schema::table('vendor_bills', function (Blueprint $table) {
                $table->foreignId('currency_id')->nullable()->after('paid_amount')->constrained('currencies')->nullOnDelete();
                $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_id');
                $table->foreignId('payment_term_id')->nullable()->after('exchange_rate')->constrained('payment_terms')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('payments', 'currency_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('currency_id')->nullable()->after('reference')->constrained('currencies')->nullOnDelete();
                $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_id');
            });
        }

        if (! Schema::hasColumn('vendor_payments', 'currency_id')) {
            Schema::table('vendor_payments', function (Blueprint $table) {
                $table->foreignId('currency_id')->nullable()->after('reference')->constrained('currencies')->nullOnDelete();
                $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['currency_id', 'exchange_rate']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['currency_id', 'exchange_rate']);
        });
        Schema::table('vendor_bills', function (Blueprint $table) {
            $table->dropForeign(['currency_id', 'payment_term_id']);
            $table->dropColumn(['currency_id', 'exchange_rate', 'payment_term_id']);
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['currency_id', 'payment_term_id', 'journal_id']);
            $table->dropColumn(['currency_id', 'exchange_rate', 'payment_term_id', 'journal_id']);
        });
        Schema::table('chart_accounts', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['currency_id', 'account_group']);
        });
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['journal_id', 'currency_id']);
            $table->dropColumn(['journal_id', 'currency_id', 'exchange_rate', 'is_posted', 'posting_date']);
        });
        Schema::dropIfExists('fiscal_periods');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('payment_terms');
        Schema::dropIfExists('accounting_journals');
    }
};
