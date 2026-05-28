<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('creditor_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_phone_hash', 64)->index();
            $table->string('name', 100);
            $table->text('phone_encrypted')->nullable();
            $table->string('till', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditor_contacts');
    }
};
