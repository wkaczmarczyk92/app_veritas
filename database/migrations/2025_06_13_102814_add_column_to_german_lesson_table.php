<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('german_lessons', function (Blueprint $table) {
            $table->integer('test_time')->after('name')->nullable();
            $table->integer('question_count')->after('test_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('german_lessons', function (Blueprint $table) {
            $table->dropColumn('test_time');
            $table->dropColumn('question_count');
        });
    }
};
