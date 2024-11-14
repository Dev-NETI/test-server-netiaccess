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
        Schema::create('payroll_logs', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('course_id');
            $table->integer('no_day');
            $table->integer('no_hr');
            $table->integer('no_ot');
            $table->double('rate_per_day');
            $table->double('rate_per_hr');
            $table->double('subtotal');
            $table->double('total');
            $table->date('date_covered_start')->nullable();
            $table->date('date_covered_end')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_logs');
    }
};
