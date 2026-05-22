<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_collections', function (Blueprint $table) {
            $table->boolean('phone_verified')->default(false)->after('status');
            $table->boolean('is_frozen')->default(false)->after('phone_verified');
            $table->text('freeze_reason')->nullable()->after('is_frozen');
            $table->string('verification_checkout_id', 100)->nullable()->after('freeze_reason');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('phone_verified')->default(false)->after('status');
            $table->boolean('is_frozen')->default(false)->after('phone_verified');
            $table->text('freeze_reason')->nullable()->after('is_frozen');
            $table->string('verification_checkout_id', 100)->nullable()->after('freeze_reason');
        });

        Schema::create('fraud_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason');
            $table->timestamps();
            $table->index(['reportable_type', 'reportable_id']);
        });
    }

    public function down(): void
    {
        Schema::table('school_collections', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'is_frozen', 'freeze_reason', 'verification_checkout_id']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'is_frozen', 'freeze_reason', 'verification_checkout_id']);
        });

        Schema::dropIfExists('fraud_reports');
    }
};
