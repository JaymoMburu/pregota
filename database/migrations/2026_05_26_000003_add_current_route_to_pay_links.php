<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pay_links', function (Blueprint $table) {
            $table->string('current_route', 100)->nullable()->after('description');
            $table->unsignedInteger('current_fare')->nullable()->after('current_route');
        });
    }

    public function down(): void
    {
        Schema::table('pay_links', function (Blueprint $table) {
            $table->dropColumn(['current_route', 'current_fare']);
        });
    }
};
