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
        Schema::table('tbljisstemplatexycoordinates', function (Blueprint $table) {
            $table->text('meal_cds')->nullable()->default('0,0')->after('total_cds');
            $table->text('transpo_cds')->nullable()->default('0,0')->after('meal_cds');
            $table->text('dorm_cds')->nullable()->default('0,0')->after('transpo_cds');
            $table->text('dmt_cds')->nullable()->default('0,0')->after('dorm_cds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljisstemplatexycoordinates', function (Blueprint $table) {
            $table->dropColumn('meal_cds');
            $table->dropColumn('transpo_cds');
            $table->dropColumn('dorm_cds');
            $table->dropColumn('dmt_cds');
        });
    }
};
