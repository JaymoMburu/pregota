<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Append-only immutable ledger — no updates, no deletes ever
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers');
            $table->enum('event', [
                'voucher_created',
                'stk_pushed',
                'stk_confirmed',
                'stk_failed',
                'voucher_activated',
                'voucher_claimed',
                'b2c_initiated',
                'b2c_confirmed',
                'b2c_failed',
                'voucher_expired',
                'voucher_cancelled',
            ]);
            $table->json('payload');              // full event data (sanitized — no phone numbers stored raw)
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('mpesa_ref', 100)->nullable();
            $table->string('prev_hash', 64)->nullable();    // hash of previous entry
            $table->string('entry_hash', 64)->unique();     // SHA256(voucher_id+event+payload+prev_hash)
            $table->string('fabric_tx_id', 128)->nullable(); // Hyperledger Fabric TxID when committed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
