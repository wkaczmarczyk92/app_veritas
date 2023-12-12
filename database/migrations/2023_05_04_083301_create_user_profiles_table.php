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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('level')->nullable();            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('recruiter_first_name')->nullable();
            $table->string('recruiter_last_name')->nullable();
            $table->unsignedBigInteger('crt_id_user_recruiter')->nullable();
            $table->unsignedFloat('total_points', 8, 1)->nullable();
            $table->unsignedFloat('current_points', 8, 1)->nullable();
            $table->unsignedFloat('total_days', 8, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
