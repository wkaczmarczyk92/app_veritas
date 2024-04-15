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
        Schema::create('model_has_files', function (Blueprint $table) {
            $table->string('model_type', 255);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('file_id');

            $table->index(['model_id', 'model_type'], 'model_morph_index');
            $table->unique(['model_type', 'model_id', 'file_id']);
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->index(['model_id', 'model_type'], 'model_morph_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_files');
    }
};
