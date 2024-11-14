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
            $table->text('servicechangetxt_cds')->nullable()->default('0,0')->after('servicechange_cds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljisstemplatexycoordinates', function (Blueprint $table) {
            $table->dropColumn('servicechangetxt_cds');
        });
    }
};
