<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_auth_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('checkout_request_id', 100)->unique();
            $table->enum('type', ['register', 'login'])->default('register');
            $table->string('phone_hash', 64)->index();
            $table->text('phone_encrypted');
            $table->unsignedBigInteger('pay_link_id')->nullable(); // login only
            $table->json('pending_data')->nullable();              // register only
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_auth_sessions');
    }
};
