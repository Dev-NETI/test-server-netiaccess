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
        Schema::create('tbljissbilling', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('company');
            $table->integer('courseid');
            $table->text('trainees');
            $table->bigInteger('serialnumber');
            $table->integer('billingstatusid');
            $table->integer('approver_1');
            $table->integer('approver_2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbljissbilling');
    }
};
