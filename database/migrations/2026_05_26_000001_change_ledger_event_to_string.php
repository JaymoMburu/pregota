<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE ledger_entries MODIFY event VARCHAR(60) NOT NULL');
    }

    public function down(): void
    {
        // Cannot safely revert to enum without knowing all existing values
    }
};
