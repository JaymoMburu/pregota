<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->text('till_encrypted')->nullable()->after('payout_phone_encrypted');
            $table->string('till_type', 10)->nullable()->after('till_encrypted'); // paybill|till
        });
    }

    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropColumn(['till_encrypted', 'till_type']);
        });
    }
};
