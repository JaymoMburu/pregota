<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->string('admin_phone_encrypted');
            $table->string('admin_pin_hash');
            $table->unsignedInteger('amount_per_member')->nullable();   // null = open amount
            $table->enum('frequency', ['once', 'monthly', 'quarterly', 'annually'])->default('annually');
            $table->date('next_due')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('group_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('contribution_groups')->cascadeOnDelete();
            $table->string('phone_hash', 64)->index();
            $table->string('reminder_token', 64)->unique();
            $table->unsignedInteger('amount');
            $table->string('period', 20);                               // e.g. "2026", "2026-01", "2026-Q2"
            $table->string('checkout_request_id', 100)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->string('receipt_number', 30)->nullable()->unique();
            $table->timestamps();

            $table->unique(['group_id', 'phone_hash', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_payments');
        Schema::dropIfExists('contribution_groups');
    }
};
