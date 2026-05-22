<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('direct_gifts', function (Blueprint $table) {
            $table->id();
            $table->integer('gross_amount');
            $table->integer('gift_amount');
            $table->integer('fee')->default(75);
            $table->string('mpesa_checkout_id', 100)->nullable()->unique();
            $table->string('mpesa_confirmation_code', 20)->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            // Encrypted temporarily; nulled immediately after B2C is initiated
            $table->text('recipient_phone_encrypted')->nullable();
            $table->string('b2c_conversation_id', 100)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direct_gifts');
    }
};
