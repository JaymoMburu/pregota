<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_link_id')->constrained('pay_links')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('amount');
            $table->enum('frequency', ['monthly', 'quarterly', 'annually']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->string('phone_hash', 64)->index();
            $table->string('reminder_token', 64)->unique();
            $table->enum('status', ['active', 'overdue', 'cancelled'])->default('active');
            $table->date('last_paid_at')->nullable();
            $table->date('next_due_at')->nullable();
            $table->string('checkout_request_id', 100)->nullable();
            $table->string('receipt_number', 30)->nullable();
            $table->timestamps();

            $table->unique(['plan_id', 'phone_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
