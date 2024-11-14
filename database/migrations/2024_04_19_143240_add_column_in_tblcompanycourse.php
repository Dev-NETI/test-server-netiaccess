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
        Schema::table('tblcompanycourse', function (Blueprint $table) {
            $table->integer('billing_statement_format')->comment('1 - single billing statement , 2 - Individual Billing Statement , 3 - Individual Billing Statements with Vessel')->default(1)->after('vat');
            $table->integer('billing_statement_template')->comment('1 - PESO , 2 - USD , 3 - USD w/ VAT	')->default(3)->after('billing_statement_format');
            $table->integer('default_currency')->comment('0 - PESO , 1 - USD')->default(1)->after('billing_statement_template');
            $table->double('bank_charge')->default(0)->after('default_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblcompanycourse', function (Blueprint $table) {
            //
        });
    }
};
