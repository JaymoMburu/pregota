<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manual_entries', function (Blueprint $table) {
            $table->string('source', 100)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('manual_entries', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
