<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_collections', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('school_name', 120);
            $table->string('term_label', 60);          // e.g. "Term 2 · 2026"
            $table->unsignedInteger('amount_per_student')->default(1850);
            $table->string('admin_name', 60);
            $table->text('recipient_phone_encrypted')->nullable(); // nulled after payout
            $table->string('admin_token', 48)->unique();
            $table->enum('status', ['open', 'closed', 'paid'])->default('open');
            $table->unsignedBigInteger('total_raised')->default(0);
            $table->unsignedInteger('contributor_count')->default(0);
            $table->string('b2c_conversation_id')->nullable();
            $table->timestamp('paid_out_at')->nullable();
            $table->timestamps();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_collection_id')->constrained()->cascadeOnDelete();
            $table->string('class_name', 60);          // e.g. "Form 2B"
            $table->string('teacher_name', 60);
            $table->string('class_token', 32)->unique();
            $table->unsignedBigInteger('total_raised')->default(0);
            $table->unsignedInteger('contributor_count')->default(0);
            $table->timestamps();
        });

        Schema::create('school_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->string('student_name', 80);
            $table->unsignedInteger('amount');
            $table->unsignedInteger('fee')->default(30);
            $table->unsignedInteger('gross_amount');
            $table->string('mpesa_checkout_id')->nullable()->index();
            $table->string('mpesa_confirmation_code')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_payments');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('school_collections');
    }
};
