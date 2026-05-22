<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->string('login_phone', 20)->nullable()->after('alert_token');
            $table->string('password')->nullable()->after('login_phone');
            $table->boolean('is_solo')->default(false)->after('password');
            $table->foreignId('business_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropColumn(['login_phone', 'password', 'is_solo']);
            $table->foreignId('business_id')->nullable(false)->change();
        });
    }
};
