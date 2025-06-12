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
        Schema::create('model_has_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');

            $table->unsignedBigInteger('token_id');
            $table->foreign('token_id')->on('tokens')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_tokens');
    }
};
