<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->string('lender_phone_hash', 64)->nullable()->index()->after('lender_phone_encrypted');
        });
    }

    public function down(): void
    {
        Schema::table('deni', function (Blueprint $table) {
            $table->dropColumn('lender_phone_hash');
        });
    }
};
