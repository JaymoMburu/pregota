<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pay_link_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_link_id')->constrained()->cascadeOnDelete();
            $table->string('label', 80);
            $table->unsignedInteger('amount');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pay_link_fares');
    }
};
