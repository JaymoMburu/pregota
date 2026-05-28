<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('creditor_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_phone_hash', 64)->index();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('recipient_name', 100);
            $table->text('recipient_phone_encrypted')->nullable();
            $table->string('recipient_till', 10)->nullable();
            $table->unsignedInteger('amount');
            $table->string('category', 60)->default('payout');
            $table->string('description', 200)->nullable();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('status', 20)->default('pending');
            $table->string('receipt_number', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditor_payouts');
    }
};
