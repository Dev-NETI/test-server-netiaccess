<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tblbusmonitoring', function (Blueprint $table) {
            $table->date('created_date')->default(DB::raw('CURRENT_DATE'))->after('deletedid');

            $table->unique(['enroledid','created_date']);
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
