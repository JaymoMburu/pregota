<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->string('debtor_name', 100)->nullable()->after('debtor_phone_encrypted');
        });
    }

    public function down(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->dropColumn('debtor_name');
        });
    }
};
