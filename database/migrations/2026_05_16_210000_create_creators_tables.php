<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->string('handle')->unique();
            $table->string('display_name');
            $table->string('bio', 200)->nullable();
            $table->string('photo_url')->nullable();
            $table->text('payout_phone_encrypted');   // Laravel encrypt()
            $table->string('password');               // hashed
            $table->string('goal_title')->nullable();
            $table->decimal('goal_amount', 12, 2)->nullable();
            $table->decimal('min_gift_amount', 10, 2)->default(50);
            $table->decimal('total_received', 14, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('alert_token', 64)->unique(); // for OBS overlay auth
            $table->timestamps();
        });

        Schema::create('creator_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('payout_amount', 12, 2);
            $table->decimal('fee_in', 10, 2);
            $table->decimal('fee_out', 10, 2);
            $table->string('fan_name')->nullable();
            $table->string('message', 200)->nullable();
            $table->string('mpesa_checkout_id')->nullable()->index();
            $table->string('mpesa_confirmation_code')->nullable();
            $table->string('b2c_conversation_id')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->boolean('alert_shown')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_gifts');
        Schema::dropIfExists('creators');
    }
};
