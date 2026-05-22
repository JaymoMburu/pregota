<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen the enum to include 'recalled'
        DB::statement("ALTER TABLE vouchers MODIFY status ENUM('pending','active','redeemed','expired','cancelled','recalled') NOT NULL DEFAULT 'pending'");

        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('recall_token', 15)->nullable()->unique()->after('expires_at');
            $table->timestamp('recalled_at')->nullable()->after('recall_token');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['recall_token', 'recalled_at']);
        });
        DB::statement("ALTER TABLE vouchers MODIFY status ENUM('pending','active','redeemed','expired','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
