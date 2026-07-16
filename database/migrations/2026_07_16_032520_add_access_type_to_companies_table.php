<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('companies', 'access_type')) {
            Schema::table('companies', function (Blueprint $table): void {
                $table->string('access_type', 20)->default('external')->after('code')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('companies', 'access_type')) {
            Schema::table('companies', function (Blueprint $table): void {
                $table->dropIndex(['access_type']);
                $table->dropColumn('access_type');
            });
        }
    }
};
