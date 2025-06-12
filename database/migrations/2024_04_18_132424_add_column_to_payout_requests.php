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
            $table->unsignedBigInteger('user_id_approved_by')->nullable()->after('user_id_completed_by');
            $table->foreign('user_id_approved_by')->references('id')->on('users')->nullOnDelete();

            $table->text('black_list_comment')->after('user_id_approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id_approved_by']);
            $table->dropColumn(['user_id_approved_by', 'black_list_comment']);
        });
    }
};
