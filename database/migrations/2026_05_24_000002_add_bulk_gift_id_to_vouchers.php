<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->foreignId('bulk_gift_id')->nullable()->after('id')->constrained('bulk_gifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\BulkGift::class);
            $table->dropColumn('bulk_gift_id');
        });
    }
};
