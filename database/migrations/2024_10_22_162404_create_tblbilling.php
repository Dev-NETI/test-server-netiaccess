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
        Schema::create('tblbilling', function (Blueprint $table) {
            $table->id();
            $table->text('scheduleid');
            $table->text('batchno');
            $table->text('enroledids');
            $table->text('companyid');
            $table->text('filepath');
            $table->text('serialnumber');
            $table->integer('billingstatusid');
            $table->text('generated_by');
            $table->text('is_deleted')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbilling');
    }
};
