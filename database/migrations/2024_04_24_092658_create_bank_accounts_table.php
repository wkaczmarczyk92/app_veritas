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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('owner_name', 255);

            $table->unsignedBigInteger('account_type_id')->nullable();
            $table->foreign('account_type_id')->references('id')->on('account_types')->nullOnDelete();

            $table->string('account_number', 255)->unique();
            $table->string('bank_name', 255);
            $table->string('swift', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
