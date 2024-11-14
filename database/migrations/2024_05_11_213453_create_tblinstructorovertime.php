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
        Schema::create('tblinstructorovertime', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->date('workdate');
            $table->date('datefiled');
            $table->integer('status');
            $table->boolean('is_approved');
            $table->unsignedBigInteger('approver');
            $table->time('overtime_start');
            $table->time('overtime_end');
            $table->boolean('deletedid')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblinstructorovertime');
    }
};
