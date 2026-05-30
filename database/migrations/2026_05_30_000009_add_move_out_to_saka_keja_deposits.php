<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saka_keja_deposits', function (Blueprint $table) {
            $table->timestamp('move_out_requested_at')->nullable()->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('saka_keja_deposits', function (Blueprint $table) {
            $table->dropColumn('move_out_requested_at');
        });
    }
};
