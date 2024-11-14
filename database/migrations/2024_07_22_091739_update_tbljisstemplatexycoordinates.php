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
            $table->text('recipientposition_cds')->nullable()->default('0,0')->after('recipients_cds');
            $table->text('recipientcompany_cds')->nullable()->default('0,0')->after('recipientposition_cds');
            $table->text('recipientaddressline1_cds')->nullable()->default('0,0')->after('recipientcompany_cds');
            $table->text('recipientaddressline2_cds')->nullable()->default('0,0')->after('recipientaddressline1_cds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljisstemplatexycoordinates', function (Blueprint $table) {
            $table->dropColumn('recipientposition_cds');
            $table->dropColumn('recipientaddressline1_cds');
            $table->dropColumn('recipientaddressline2_cds');
        });
    }
};
