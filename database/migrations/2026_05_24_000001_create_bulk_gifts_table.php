<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bulk_gifts', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->string('company_name');
            $table->string('contact_name');
            $table->decimal('amount_per_code', 10, 2);
            $table->unsignedInteger('code_count');
            $table->decimal('total_payout', 10, 2);
            $table->decimal('fee_in_total', 10, 2);
            $table->decimal('fee_out_total', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->enum('status', ['pending', 'active', 'failed'])->default('pending');
            $table->string('mpesa_checkout_id', 100)->nullable();
            $table->string('mpesa_confirmation_code', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_gifts');
    }
};
