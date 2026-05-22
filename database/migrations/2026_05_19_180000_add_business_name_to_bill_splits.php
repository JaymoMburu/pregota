<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bill_splits', function (Blueprint $table) {
            $table->string('business_name', 80)->after('waiter_token');
        });
    }

    public function down(): void
    {
        Schema::table('bill_splits', function (Blueprint $table) {
            $table->dropColumn('business_name');
        });
    }
};
