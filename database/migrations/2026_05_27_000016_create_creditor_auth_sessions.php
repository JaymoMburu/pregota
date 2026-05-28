<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('creditor_auth_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('checkout_request_id', 100)->unique();
            $table->string('phone_hash', 64)->index();
            $table->text('phone_encrypted');
            $table->string('display_name', 100)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditor_auth_sessions');
    }
};
