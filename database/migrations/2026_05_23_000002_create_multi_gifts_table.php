<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multi_gifts', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();   // MULTI-XXXX-YYYY
            $table->json('items');                        // [{creator_id, handle, display_name, amount, b2c_conv_id, b2c_status}]
            $table->unsignedInteger('total_payout');      // sum of all creator amounts
            $table->unsignedInteger('fee_in');            // Pregota deposit fee (from sender)
            $table->unsignedInteger('fee_out_total');     // B2C buffer (per-creator × N)
            $table->unsignedInteger('gross_amount');      // total_payout + fee_in + fee_out_total
            $table->enum('status', ['pending','active','distributing','complete','failed'])->default('pending');
            $table->string('mpesa_checkout_id', 60)->nullable()->index();
            $table->string('mpesa_confirmation_code', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multi_gifts');
    }
};
