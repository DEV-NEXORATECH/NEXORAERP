<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            if (! Schema::hasColumn('currency_rates', 'from_currency')) {
                $table->string('from_currency', 3)->default('USD')->after('id');
            }

            if (! Schema::hasColumn('currency_rates', 'to_currency')) {
                $table->string('to_currency', 3)->default('IDR')->after('from_currency');
            }

            if (! Schema::hasColumn('currency_rates', 'rate')) {
                $table->decimal('rate', 15, 4)->default(0)->after('to_currency');
            }

            if (! Schema::hasColumn('currency_rates', 'source')) {
                $table->string('source')->nullable()->after('rate_date');
            }
        });

        if (Schema::hasColumn('currency_rates', 'currency')) {
            DB::table('currency_rates')
                ->where(function ($query) {
                    $query->whereNull('from_currency')->orWhere('from_currency', '');
                })
                ->update(['from_currency' => DB::raw('currency')]);
        }

        if (Schema::hasColumn('currency_rates', 'rate_to_idr')) {
            DB::table('currency_rates')
                ->where('rate', 0)
                ->update(['rate' => DB::raw('rate_to_idr')]);
        }
    }

    public function down(): void
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            if (Schema::hasColumn('currency_rates', 'source')) {
                $table->dropColumn('source');
            }

            if (Schema::hasColumn('currency_rates', 'rate')) {
                $table->dropColumn('rate');
            }

            if (Schema::hasColumn('currency_rates', 'to_currency')) {
                $table->dropColumn('to_currency');
            }

            if (Schema::hasColumn('currency_rates', 'from_currency')) {
                $table->dropColumn('from_currency');
            }
        });
    }
};
