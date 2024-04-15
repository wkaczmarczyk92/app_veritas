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
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            $table->text('text_answer')->nullable();
            // kolumna mówiąca czy odpowiedz ma być ręcznie zatwierdzona przez admina, true tylko w przypadku pytań otwartych
            $table->boolean('for_approval')->default(false);

            $table->unsignedInteger('choice_answer_id')->nullable();

            $table->json('multiple_choice_answer_ids')->nullable();

            $table->boolean('is_correct')->default(false);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
