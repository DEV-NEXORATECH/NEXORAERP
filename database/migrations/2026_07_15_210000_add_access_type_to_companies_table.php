<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            if (!Schema::hasColumn('companies', 'access_type')) {
                $table->string('access_type', 20)->default('external')->after('code')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            if (Schema::hasColumn('companies', 'access_type')) {
                $table->dropIndex(['access_type']);
                $table->dropColumn('access_type');
            }
        });
    }
};
