<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 30)->index();
            $table->string('buyer_phone_encrypted');
            $table->string('issue_type', 30);
            $table->text('description');
            $table->enum('status', ['open', 'investigating', 'resolved', 'dismissed'])->default('open');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::table('pay_links', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
        Schema::table('pay_links', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });
    }
};
