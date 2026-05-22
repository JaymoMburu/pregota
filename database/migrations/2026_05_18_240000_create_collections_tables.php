<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title', 120);
            $table->enum('occasion', ['bereavement', 'wedding', 'medical', 'farewell', 'education', 'other']);
            $table->string('organiser_name', 60);
            $table->string('recipient_name', 60);
            $table->text('recipient_phone_encrypted');
            $table->unsignedInteger('target_amount')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->enum('payout_trigger', ['target', 'deadline', 'manual'])->default('manual');
            $table->enum('status', ['open', 'closed', 'paid'])->default('open');
            $table->string('manage_token', 64)->unique();
            $table->unsignedInteger('total_raised')->default(0);
            $table->unsignedInteger('contributor_count')->default(0);
            $table->string('b2c_conversation_id', 100)->nullable();
            $table->timestamp('paid_out_at')->nullable();
            $table->timestamps();
        });

        Schema::create('collection_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('contributor_name', 60)->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('fee')->default(30);
            $table->unsignedInteger('gross_amount');
            $table->string('mpesa_checkout_id', 100)->nullable()->unique();
            $table->string('mpesa_confirmation_code', 20)->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_contributions');
        Schema::dropIfExists('collections');
    }
};
