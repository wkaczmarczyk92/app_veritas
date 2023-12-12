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
        Schema::table('caretaker_recommendations', function (Blueprint $table) {
            $table->boolean('bonus_payout_completed')->default(false)->after('crt_id_caretaker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caretaker_recommendations', function (Blueprint $table) {
            //
        });
    }
};
