<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_splits', function (Blueprint $table) {
            $table->id();
            $table->string('waiter_token', 32)->unique();
            $table->string('split_token', 16)->unique();
            $table->string('label', 60)->nullable();
            $table->integer('total_amount');
            $table->integer('paid_amount')->default(0);
            $table->text('payout_phone_encrypted');
            $table->string('b2c_conversation_id')->nullable();
            $table->enum('status', ['open', 'settling', 'settled', 'expired'])->default('open');
            $table->timestamp('expires_at');
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bill_split_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_split_id')->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->integer('fee')->default(30);
            $table->integer('gross_amount');
            $table->string('mpesa_checkout_id')->nullable()->index();
            $table->string('mpesa_confirmation_code')->nullable();
            $table->string('tx_hash')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_split_payments');
        Schema::dropIfExists('bill_splits');
    }
};
