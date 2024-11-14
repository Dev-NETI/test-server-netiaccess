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
            $table->string('billingaccount')->nullable()->default('')->change();
            $table->string('accountname')->nullable()->default('')->change();
            $table->string('accountnumber')->nullable()->default('')->change();
            $table->string('bankname')->nullable()->default('')->change();
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
