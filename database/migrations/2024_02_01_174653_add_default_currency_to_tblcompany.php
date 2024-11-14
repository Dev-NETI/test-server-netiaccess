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
        Schema::table('tblcompany', function (Blueprint $table) {
            $table->integer('default_currency')->default(1)->after('bank_charge')->comment('0 - peso , 1 - dollar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblcompany', function (Blueprint $table) {
            //
        });
    }
};
