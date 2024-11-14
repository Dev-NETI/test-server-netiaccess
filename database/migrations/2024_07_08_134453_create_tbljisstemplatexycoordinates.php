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
        Schema::create('tbljisstemplatexycoordinates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courseid');
            $table->text('sn_cds')->nullable()->default('0,0');
            $table->text('recipients_cds')->nullable()->default('0,0');
            $table->text('datebilled_cds')->nullable()->default('0,0');
            $table->text('trainingtitle_cds')->nullable()->default('0,0');
            $table->text('course_cds')->nullable()->default('0,0');
            $table->text('trainees_cds')->nullable()->default('0,0');
            $table->text('nationality_cds')->nullable()->default('0,0');
            $table->text('amount_cds')->nullable()->default('0,0');
            $table->text('total_cds')->nullable()->default('0,0');
            $table->text('servicechange_cds')->nullable()->default('0,0');
            $table->text('overalltotal_cds')->nullable()->default('0,0');
            $table->text('signature1_cds')->nullable()->default('0,0');
            $table->text('signature2_cds')->nullable()->default('0,0');
            $table->text('signature3_cds')->nullable()->default('0,0');
            $table->boolean('deletedid')->nullable()->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbljisstemplatexycoordinates');
    }
};
