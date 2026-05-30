<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pay_links', function (Blueprint $table) {
            $table->string('phone_hash', 64)->nullable()->index()->after('phone_encrypted');
        });

        // Backfill: normalise to 2547XXXXXXXX then SHA-256 — matches SellerService::hashPhone
        \App\Models\PayLink::whereNull('phone_hash')->each(function ($pl) {
            try {
                $phone  = \Illuminate\Support\Facades\Crypt::decryptString($pl->phone_encrypted);
                $digits = preg_replace('/\D/', '', $phone);
                if (str_starts_with($digits, '0'))                                       $digits = '254' . substr($digits, 1);
                elseif (str_starts_with($digits, '7') || str_starts_with($digits, '1')) $digits = '254' . $digits;
                $pl->updateQuietly(['phone_hash' => hash('sha256', $digits)]);
            } catch (\Throwable $e) {
                // skip if decryption fails
            }
        });
    }

    public function down(): void
    {
        Schema::table('pay_links', function (Blueprint $table) {
            $table->dropColumn('phone_hash');
        });
    }
};
