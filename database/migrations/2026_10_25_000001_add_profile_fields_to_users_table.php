<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('email');
            $table->string('nik', 50)->nullable()->after('avatar_path');
            $table->string('phone', 40)->nullable()->after('nik');
            $table->string('job_title')->nullable()->after('phone');
            $table->string('department')->nullable()->after('job_title');
            $table->string('address')->nullable()->after('department');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('emergency_contact')->nullable()->after('birth_place');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_path', 'nik', 'phone', 'job_title', 'department', 'address', 'birth_date', 'birth_place', 'emergency_contact']);
        });
    }
};
