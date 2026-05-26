<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pay_links', function (Blueprint $table) {
            $table->id();
            $table->string('handle', 40)->unique();
            $table->string('business_name', 100);
            $table->string('category', 40)->nullable();
            $table->text('description')->nullable();
            $table->string('phone_encrypted');
            $table->string('password');
            $table->unsignedInteger('default_amount')->nullable();
            $table->boolean('fixed_amount')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('total_received')->default(0);
            $table->unsignedInteger('payment_count')->default(0);
            $table->timestamps();
        });

        Schema::create('seller_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_link_id')->constrained('pay_links')->cascadeOnDelete();
            $table->string('mpesa_checkout_id', 100)->nullable()->index();
            $table->string('mpesa_ref', 30)->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('fee');
            $table->unsignedInteger('net_amount');
            $table->string('buyer_note', 200)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_payments');
        Schema::dropIfExists('pay_links');
    }
};
