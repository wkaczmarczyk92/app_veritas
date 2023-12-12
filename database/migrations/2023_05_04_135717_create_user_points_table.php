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
        Schema::create('user_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedFloat('points', 8, 1);
            $table->unsignedFloat('days', 8, 1)->nullable();
            $table->boolean('auto')->default(false);
            $table->unsignedInteger('type')->default(1)->comment('
                1 - dodanie punktów,
                2 - wypłacenie puntków
            ');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points');
    }
};
