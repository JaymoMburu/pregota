<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('saka_keja_listings', function (Blueprint $table) {
            $table->unsignedTinyInteger('advance_months')->default(1)->after('deposit_amount');
        });
    }

    public function down(): void
    {
        Schema::table('saka_keja_listings', function (Blueprint $table) {
            $table->dropColumn('advance_months');
        });
    }
};
