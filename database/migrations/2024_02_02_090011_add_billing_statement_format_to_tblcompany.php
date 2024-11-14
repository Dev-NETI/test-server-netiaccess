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
            $table->integer('billing_statement_format')->after('default_currency')->default(1)->comment('1 - single billing statement , 2 - Individual Billing Statement , 3 - Individual Billing Statements with Vessel');
            $table->integer('billing_statement_template')->after('billing_statement_format')->default(1)->comment('1 - PESO , 2 - USD , 3 - USD w/ VAT');
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
