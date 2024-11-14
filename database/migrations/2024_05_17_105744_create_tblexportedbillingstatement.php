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
        Schema::create('tblexportedbillingstatement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheduleid');
            $table->unsignedBigInteger('companyid');
            $table->unsignedBigInteger('courseid');
            $table->text('enroledid');
            $table->text('serialnumber');
            $table->text('filepath');
            $table->boolean('deletedid')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblexportedbillingstatement');
    }
};
