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
        Schema::create('bok_request_has_bank_account', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_account_id');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');

            $table->unsignedBigInteger('bok_request_id');
            $table->foreign('bok_request_id')->references('id')->on('bok_requests')->onDelete('cascade');

            $table->index(['bank_account_id', 'bok_request_id'], 'bank_account_bok_request_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bok_request_has_bank_account');
    }
};
