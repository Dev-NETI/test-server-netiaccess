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
        Schema::create('tper_evaluation_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tper_id');
            $table->unsignedBigInteger('enroled_id');
            $table->integer('rating')->default(1);
            $table->timestamps();

            $table->foreign('tper_id')->references('id')->on('tper_evaluation_factors');
            // $table->foreign('enroled_id')->references('enroledid')->on('tblenroled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tper_evaluation_ratings');
    }
};
