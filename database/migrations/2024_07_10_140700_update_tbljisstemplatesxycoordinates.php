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
            $table->text('amountvat_cds')->nullable()->default('0,0')->after('amount_cds');
            $table->text('company_cds')->nullable()->default('0,0')->after('recipients_cds');
            $table->text('monthyear_cds')->nullable()->default('0,0')->after('company_cds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
