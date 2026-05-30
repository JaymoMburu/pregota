<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('creditor_payouts', function (Blueprint $table) {
            $table->text('b2c_response')->nullable()->after('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::table('creditor_payouts', function (Blueprint $table) {
            $table->dropColumn('b2c_response');
        });
    }
};
