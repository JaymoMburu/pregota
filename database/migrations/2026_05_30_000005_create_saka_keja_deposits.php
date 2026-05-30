<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saka_keja_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('saka_keja_listings')->cascadeOnDelete();
            $table->string('token')->unique(); // unique link token
            $table->string('seeker_name');
            $table->string('seeker_phone_hash');
            $table->text('seeker_phone_encrypted');
            $table->unsignedInteger('deposit_amount');  // actual deposit (rent amount)
            $table->unsignedInteger('escrow_fee')->default(200);
            $table->unsignedInteger('total_paid');      // deposit_amount + escrow_fee
            $table->string('checkout_request_id')->unique();
            $table->string('receipt_number')->nullable();
            $table->string('status')->default('pending'); // pending|held|confirmed|refunded|failed
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saka_keja_deposits');
    }
};
