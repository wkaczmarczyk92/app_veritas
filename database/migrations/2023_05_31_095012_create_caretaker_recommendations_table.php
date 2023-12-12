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
        Schema::create('caretaker_recommendations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('caretaker_first_name')->nullable();
            $table->string('caretaker_last_name')->nullable();
            $table->string('caretaker_email')->nullable();
            $table->string('caretaker_phone_number')->nullable();

            $table->foreignId('language_id')->nullable()->constrained('languages');

            $table->unsignedBigInteger('crm_lead_id')->unique()->nullable();
            $table->unsignedBigInteger('crt_id_caretaker')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caretaker_recommendations');
    }
};
