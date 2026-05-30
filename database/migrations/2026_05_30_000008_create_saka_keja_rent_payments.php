<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saka_keja_rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained('saka_keja_deposits')->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained('saka_keja_listings')->cascadeOnDelete();
            $table->string('rent_month'); // e.g. "2026-06"
            $table->unsignedInteger('gross_amount');  // full rent paid by tenant
            $table->unsignedInteger('fee_amount');    // 2% Pregota fee
            $table->unsignedInteger('net_amount');    // gross - fee, sent to landlord
            $table->string('checkout_request_id')->unique();
            $table->string('status')->default('pending'); // pending|confirmed|failed
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saka_keja_rent_payments');
    }
};
