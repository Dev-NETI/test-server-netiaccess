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
        Schema::create('tbljisspricematrix', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('companyid');
            $table->integer('courseid');
            $table->integer('PHP_USD')->default(0)->comment('0 - USD ,1 - PHP');
            $table->double('courserate');
            $table->timestamps();

            $table->unique(['companyid', 'courseid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbljisspricematrix');
    }
};
