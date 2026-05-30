<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('saka_keja_listings', function (Blueprint $table) {
            $table->unsignedInteger('deposit_amount')->nullable()->after('rent'); // same as rent by default
            $table->json('utility_fees')->nullable()->after('deposit_amount');    // [{name, amount}]
        });
    }

    public function down(): void
    {
        Schema::table('saka_keja_listings', function (Blueprint $table) {
            $table->dropColumn(['deposit_amount', 'utility_fees']);
        });
    }
};
