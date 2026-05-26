<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_entries', function (Blueprint $table) {
            $table->id();
            $table->string('phone_hash', 64)->index();
            $table->enum('type', ['expense', 'income'])->default('expense');
            $table->unsignedInteger('amount');
            $table->string('category', 40)->nullable();
            $table->string('description', 200)->nullable();
            $table->date('entry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_entries');
    }
};
