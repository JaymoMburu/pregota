<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saka_keja_listings', function (Blueprint $table) {
            $table->id();
            $table->string('landlord_phone_hash');
            $table->text('landlord_phone_encrypted');
            $table->string('landlord_name');
            $table->string('location');
            $table->string('unit_type');
            $table->unsignedInteger('rent');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('verification_checkout_id')->nullable();
            $table->unsignedInteger('listing_fee')->default(200);
            $table->string('status')->default('pending_verification'); // pending_verification|active|rented|inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saka_keja_listings');
    }
};
