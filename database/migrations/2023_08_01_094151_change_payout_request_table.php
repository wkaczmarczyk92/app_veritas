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
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->foreignId('user_has_bonus_id')->constrained('user_has_bonuses')->after('payout_value');
            // $table->dropColumn('used_points');
            // $table->dropColumn('payment_completed');
            // $table->dropForeign('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            // $table->dropColumn('used_points');
            // $table->dropColumn('payment_completed');
        });
    }
};
