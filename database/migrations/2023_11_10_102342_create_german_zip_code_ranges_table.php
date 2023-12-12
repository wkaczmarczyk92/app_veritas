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
        Schema::create('german_zip_codes', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->foreignId('land_id')->constrained('lands')->unsigned();
            $table->string('zip_code');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('german_zip_code_ranges');
    }
};
