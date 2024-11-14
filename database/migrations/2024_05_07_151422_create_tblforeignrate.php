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
        Schema::create('tblforeignrate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyid')->unique();
            $table->unsignedBigInteger('courseid')->unique();
            $table->double('course_rate');
            $table->double('bf_rate');
            $table->double('lh_rate');
            $table->double('dn_rate');
            $table->double('dorm_am_checkin');
            $table->double('dorm_pm_checkin');
            $table->double('dorm_am_checkout');
            $table->double('dorm_pm_checkout');
            $table->double('dorm_rate');
            $table->double('meal_rate');
            $table->double('transpo');
            $table->double('bank_charge');
            $table->integer('format');
            $table->integer('template');
            $table->integer('deletedid')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblforeignrate');
    }
};
