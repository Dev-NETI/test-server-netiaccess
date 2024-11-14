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
        Schema::create('tblbillingbilledlogs', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('enroledid');
            $table->integer('scheduleid');
            $table->text('billingserialnumber')->nullable()->default(NULL);
            $table->text('remarks')->nullable();
            $table->text('modifier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_tblbillingbilledlogs');
    }
};
