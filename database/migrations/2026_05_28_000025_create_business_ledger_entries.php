<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_phone_hash', 64)->index();
            $table->enum('type', ['income', 'expense']);
            $table->string('category', 60);
            $table->unsignedInteger('amount');
            $table->string('description', 300)->nullable();
            $table->string('source', 60)->nullable(); // 'deni_payment', 'manual'
            $table->unsignedBigInteger('deni_payment_id')->nullable(); // link to auto-entries
            $table->date('entry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_ledger_entries');
    }
};
