<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('restaurant'); // restaurant, salon, hotel, other
            $table->string('logo_emoji', 10)->default('🏢');
            $table->string('description', 200)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('city', 60)->nullable();
            $table->timestamps();
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('handle')->unique(); // used in URL: /t/{handle}
            $table->string('name');
            $table->string('role', 60)->nullable(); // Waitress, Barista, etc.
            $table->string('branch', 100)->nullable();
            $table->string('avatar_emoji', 10)->default('😊');
            $table->text('payout_phone_encrypted');
            $table->string('alert_token', 32)->unique();
            $table->boolean('active')->default(true);
            $table->decimal('total_received', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('tip_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('tip_amount', 10, 2);   // what staff receives
            $table->decimal('fee_in', 10, 2)->default(0);
            $table->decimal('fee_out', 10, 2)->default(0);
            $table->enum('status', ['pending', 'active', 'paid', 'failed'])->default('pending');
            $table->string('mpesa_checkout_id')->nullable()->index();
            $table->string('mpesa_confirmation_code')->nullable();
            $table->string('b2c_conversation_id')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('feedback_submitted')->default(false);
            $table->timestamps();
        });

        Schema::create('tip_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tip_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->json('tags')->nullable();
            $table->string('comment', 300)->nullable();
            $table->timestamps();
        });

        Schema::create('feedback_tags', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->string('emoji', 10)->default('👍');
            $table->string('category')->default('general'); // restaurant, salon, hotel, general
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_tags');
        Schema::dropIfExists('tip_feedback');
        Schema::dropIfExists('tip_transactions');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('businesses');
    }
};
