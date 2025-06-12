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
        Schema::table('user_has_bonuses', function (Blueprint $table) {
            $table->unsignedBigInteger('bonus_status_id')->nullable()->after('bonus_value');
            $table->foreign('bonus_status_id')->references('id')->on('bonus_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_has_bonuses', function (Blueprint $table) {
            $table->dropColumns('bonus_status_id');
        });
    }
};
