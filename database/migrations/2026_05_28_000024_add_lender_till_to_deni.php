<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->string('lender_till', 10)->nullable()->after('lender_phone_hash');
        });
    }

    public function down(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->dropColumn('lender_till');
        });
    }
};
