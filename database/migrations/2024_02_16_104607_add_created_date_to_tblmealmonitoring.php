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
        Schema::table('tblmealmonitoring', function (Blueprint $table) {
            $table->date('created_date')->after('deletedid')->default(DB::raw('CURRENT_DATE'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblmealmonitoring', function (Blueprint $table) {
            //
        });
    }
};
