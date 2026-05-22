<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->string('teacher_token', 32)->nullable()->unique()->after('class_token');
        });

        // Backfill existing rows
        \App\Models\SchoolClass::whereNull('teacher_token')->each(function ($c) {
            $c->update(['teacher_token' => Str::random(32)]);
        });
    }

    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropColumn('teacher_token');
        });
    }
};
