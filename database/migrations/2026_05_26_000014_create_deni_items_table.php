<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deni_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deni_id')->constrained('deni')->cascadeOnDelete();
            $table->string('description', 200);
            $table->unsignedInteger('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deni_items');
    }
};
