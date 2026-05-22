<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bill_splits', function (Blueprint $table) {
            $table->string('payout_type', 10)->default('paybill')->after('payout_phone_encrypted');
        });
    }

    public function down(): void
    {
        Schema::table('bill_splits', function (Blueprint $table) {
            $table->dropColumn('payout_type');
        });
    }
};
