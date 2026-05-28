<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregota_passes', function (Blueprint $table) {
            $table->id();
            $table->string('phone_hash', 64)->index();
            $table->enum('pass_type', ['daily', 'weekly', 'monthly']);
            $table->unsignedSmallInteger('amount_paid');
            $table->string('checkout_request_id', 100)->unique()->nullable();
            $table->string('receipt_number', 60)->nullable();
            $table->enum('status', ['pending', 'active', 'failed'])->default('pending');
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregota_passes');
    }
};
