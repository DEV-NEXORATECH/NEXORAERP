<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            if (! Schema::hasColumn('fixed_assets', 'asset_code')) {
                $table->string('asset_code', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('fixed_assets', 'purchase_date')) {
                $table->date('purchase_date')->nullable()->after('category');
            }
            if (! Schema::hasColumn('fixed_assets', 'purchase_cost')) {
                $table->decimal('purchase_cost', 15, 2)->default(0)->after('purchase_date');
            }
            if (! Schema::hasColumn('fixed_assets', 'residual_value')) {
                $table->decimal('residual_value', 15, 2)->default(0)->after('purchase_cost');
            }
            if (! Schema::hasColumn('fixed_assets', 'useful_life_years')) {
                $table->unsignedInteger('useful_life_years')->default(3)->after('residual_value');
            }
            if (! Schema::hasColumn('fixed_assets', 'depreciation_method')) {
                $table->string('depreciation_method', 50)->default('straight_line')->after('useful_life_years');
            }
            if (! Schema::hasColumn('fixed_assets', 'monthly_depreciation')) {
                $table->decimal('monthly_depreciation', 15, 2)->default(0)->after('depreciation_method');
            }
            if (! Schema::hasColumn('fixed_assets', 'book_value')) {
                $table->decimal('book_value', 15, 2)->default(0)->after('accumulated_depreciation');
            }
            if (! Schema::hasColumn('fixed_assets', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $cols = [
                'asset_code', 'purchase_date', 'purchase_cost', 'residual_value',
                'useful_life_years', 'depreciation_method', 'monthly_depreciation',
                'book_value', 'notes'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('fixed_assets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
