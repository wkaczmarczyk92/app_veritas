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
        Schema::table('family_recommendations', function (Blueprint $table) {
            $table->bigInteger('veritas_id')->unsigned()->after('bonus_payout_completed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_recommendations', function (Blueprint $table) {
            //
        });
    }
};
