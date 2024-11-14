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
        Schema::table('tblpde', function (Blueprint $table) {
            $table->integer('assessmentresult')->nullable()->default(NULL)->after('PDECertDeptHeadID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblpde', function (Blueprint $table) {
            //
        });
    }
};
