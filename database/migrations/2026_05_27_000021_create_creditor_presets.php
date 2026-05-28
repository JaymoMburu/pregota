<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditor_presets', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_phone_hash', 64)->index();
            $table->string('label', 80);
            $table->unsignedInteger('amount');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditor_presets');
    }
};
