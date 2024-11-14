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
        Schema::create('tblbillingstatement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyid');
            $table->unsignedBigInteger('scheduleid');
            $table->text('enroledids');
            $table->text('original_name');
            $table->text('billing_attachment_path');
            $table->text('serial_number');
            $table->text('modified_by');
            $table->integer('deletedid')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbillingstatement');
    }
};
