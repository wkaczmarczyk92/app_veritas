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
        Schema::create('family_recommendations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('family_last_name');
            $table->string('country_code')->default('49');
            $table->string('phone_number');
            $table->string('info')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_recommendations');
    }
};
