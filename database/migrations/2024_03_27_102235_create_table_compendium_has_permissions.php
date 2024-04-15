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
        Schema::create('compendium_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('compendium_id');
            $table->foreign('compendium_id')->references('id')->on('compendia')->onDelete('cascade');

            $table->morphs('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compendium_has_permissions');
    }
};
