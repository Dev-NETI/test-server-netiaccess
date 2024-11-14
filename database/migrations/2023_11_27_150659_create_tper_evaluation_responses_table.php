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
        Schema::create('tper_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tper_question_id');
            $table->unsignedBigInteger('enroled_id');
            $table->text('response');
            $table->timestamps();

            $table->foreign('tper_question_id')->references('id')->on('tper_evaluation_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tper_evaluation_responses');
    }
};
