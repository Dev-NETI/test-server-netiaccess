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
        Schema::table('tblbillingaccount', function (Blueprint $table) {
            $table->string('billingaccount')->nullable()->default(NULL)->change();
            $table->string('accountname')->nullable()->default(NULL)->change();
            $table->string('accountnumber')->nullable()->default(NULL)->change();
            $table->string('bankname')->nullable()->default(NULL)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblbillingaccount', function (Blueprint $table) {
            //
        });
    }
};
