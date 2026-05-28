<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deni_payments', function (Blueprint $table) {
            $table->unsignedInteger('face_value')->default(0)->after('amount');
            $table->unsignedInteger('fee')->default(0)->after('face_value');
        });
    }

    public function down(): void
    {
        Schema::table('deni_payments', function (Blueprint $table) {
            $table->dropColumn(['face_value', 'fee']);
        });
    }
};
