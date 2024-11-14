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
        Schema::table('tblbusmonitoring', function (Blueprint $table) {
            $table->renameColumn('change_passenger','chance_passenger');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblbusmonitoring', function (Blueprint $table) {
            //
        });
    }
};
