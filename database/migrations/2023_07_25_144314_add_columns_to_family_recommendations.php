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
            $table->boolean('processing_personal_data')->default(true)->after('veritas_id');
            $table->text('rejected_text')->after('processing_personal_data')->nullable();
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
