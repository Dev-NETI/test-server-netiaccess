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
            $table->boolean('change_passenger')->after('created_date')->default(0);
            $table->text('scanned_by')->after('change_passenger')->nullable();
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
