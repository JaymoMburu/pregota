<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_payments', function (Blueprint $table) {
            $table->unsignedInteger('tip_amount')->default(0)->after('net_amount');
            $table->string('tip_recipient', 20)->nullable()->after('tip_amount'); // 'conductor' | 'driver'
            $table->string('tip_comment', 200)->nullable()->after('tip_recipient');
        });
    }

    public function down(): void
    {
        Schema::table('seller_payments', function (Blueprint $table) {
            $table->dropColumn(['tip_amount', 'tip_recipient', 'tip_comment']);
        });
    }
};
