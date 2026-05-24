<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE vouchers MODIFY status ENUM('pending','active','redeemed','expired','cancelled','recalled','claimed') NOT NULL DEFAULT 'pending'");

        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('recipient_phone', 20)->nullable()->after('recalled_at');
            $table->timestamp('claimed_at')->nullable()->after('recipient_phone');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['recipient_phone', 'claimed_at']);
        });
        DB::statement("ALTER TABLE vouchers MODIFY status ENUM('pending','active','redeemed','expired','cancelled','recalled') NOT NULL DEFAULT 'pending'");
    }
};
