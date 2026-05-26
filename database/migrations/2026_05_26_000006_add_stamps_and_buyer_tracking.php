<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stamp card settings on the seller's pay link
        Schema::table('pay_links', function (Blueprint $table) {
            $table->unsignedSmallInteger('stamps_required')->nullable()->after('payment_count');
            $table->string('stamp_reward', 200)->nullable()->after('stamps_required');
        });

        // Buyer phone hash on each payment — enables /me history + stamp lookup
        Schema::table('seller_payments', function (Blueprint $table) {
            $table->string('buyer_phone_hash', 64)->nullable()->index()->after('buyer_note');
        });

        // One row per buyer (phone hash) per seller — tracks accumulated stamps
        Schema::create('buyer_stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_link_id')->constrained()->cascadeOnDelete();
            $table->string('phone_hash', 64)->index();
            $table->unsignedSmallInteger('stamp_count')->default(0);
            $table->boolean('reward_pending')->default(false);
            $table->timestamp('last_stamp_at')->nullable();
            $table->timestamps();

            $table->unique(['pay_link_id', 'phone_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_stamps');
        Schema::table('seller_payments', fn (Blueprint $t) => $t->dropColumn('buyer_phone_hash'));
        Schema::table('pay_links', fn (Blueprint $t) => $t->dropColumn(['stamps_required', 'stamp_reward']));
    }
};
