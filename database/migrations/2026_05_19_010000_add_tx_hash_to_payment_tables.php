<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'tip_transactions',
        'creator_gifts',
        'direct_gifts',
        'collection_contributions',
        'school_payments',
        'vouchers',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->char('tx_hash', 64)->nullable()->unique()->after('mpesa_confirmation_code');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('tx_hash');
            });
        }
    }
};
