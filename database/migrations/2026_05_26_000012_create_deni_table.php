<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_link_id')->nullable()->constrained('pay_links')->nullOnDelete();
            $table->string('creditor_name', 100)->nullable();
            $table->string('admin_token', 64)->unique();
            $table->string('debtor_token', 64)->unique();
            $table->string('debtor_phone_hash', 64)->nullable()->index();
            $table->string('description', 300);
            $table->unsignedInteger('original_amount');
            $table->unsignedInteger('amount_paid')->default(0);
            $table->enum('status', ['open', 'partial', 'settled'])->default('open');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('deni_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deni_id')->constrained('deni')->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->string('checkout_request_id', 100)->unique()->nullable();
            $table->string('receipt_number', 30)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deni_payments');
        Schema::dropIfExists('deni');
    }
};
