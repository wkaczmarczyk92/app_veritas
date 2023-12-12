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
        Schema::table('user_profile_images', function (Blueprint $table) {
            $table->dropColumn('accepted');
            $table->integer('status')->unsigned()->comment('1-oczekuje|2-odrzucono|3-zaakceptowano')->after('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profile_images', function (Blueprint $table) {
            $table->dropColumn('accepted');
        });
    }
};
