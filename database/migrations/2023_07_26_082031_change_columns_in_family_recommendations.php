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
            $table->string('family_last_name')->nullable()->change();
            $table->string('country_code')->default(null)->nullable()->change();
            $table->string('phone_number')->nullable()->change();
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
