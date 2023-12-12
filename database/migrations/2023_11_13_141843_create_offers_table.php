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
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->foreignId('user_id')->constrained('users');
            $table->bigInteger('crm_offer_id')->unsigned();
            $table->string('hp_code');
            $table->date('start_date');
            $table->bigInteger('crm_family_id')->unsigned();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('offers', function (Blueprint $table) {
        //     //
        // });'

        Schema::dropIfExists('offers');
    }
};
