<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();          // e.g. PRG-7492-X8Q1
            $table->decimal('face_value', 12, 2);          // what sender paid (after fee_in)
            $table->decimal('gross_amount', 12, 2);        // original STK push amount
            $table->decimal('fee_in', 12, 2)->default(0);  // deposit fee
            $table->decimal('fee_out', 12, 2)->default(0); // redemption fee (calculated later)
            $table->decimal('payout_amount', 12, 2)->default(0); // what recipient gets
            $table->text('message')->nullable();            // optional gift message
            $table->enum('status', ['pending','active','redeemed','expired','cancelled'])->default('pending');
            $table->string('mpesa_checkout_id', 100)->nullable();     // STK CheckoutRequestID
            $table->string('mpesa_confirmation_code', 50)->nullable(); // Safaricom confirmation
            $table->string('b2c_conversation_id', 100)->nullable();    // B2C ConversationID
            $table->string('b2c_confirmation_code', 50)->nullable();   // B2C ResultCode confirm
            $table->string('prev_hash', 64)->nullable();   // hash of previous ledger entry
            $table->string('entry_hash', 64)->nullable();  // SHA256 of this voucher's data
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
