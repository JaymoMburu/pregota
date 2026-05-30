<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saka_keja_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('saka_keja_listings')->cascadeOnDelete();
            $table->string('seeker_name');
            $table->string('seeker_phone_hash');
            $table->text('seeker_phone_encrypted');
            $table->string('checkout_request_id')->unique();
            $table->string('status')->default('pending'); // pending|confirmed|failed
            $table->string('receipt_number')->nullable();
            $table->unsignedInteger('amount')->default(200);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saka_keja_connections');
    }
};
