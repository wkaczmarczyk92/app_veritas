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
        Schema::create('level_bonus_values', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->foreignId('level_id')->constrained('levels');
            $table->integer('value')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_bonus_values');
    }
};
