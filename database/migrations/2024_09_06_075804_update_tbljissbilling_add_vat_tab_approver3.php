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
        Schema::table('tbljissbilling', function (Blueprint $table) {
            $table->boolean('approver_3')->nullable()->default(0)->after('approver_2');
            $table->boolean('vat_service_charge')->nullable()->default(0)->comment('0 - vat 12%, 1 - service charge')->after('approver_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljissbillingattachments', function (Blueprint $table) {
            $table->dropColumn('approver_3');
            $table->dropColumn('vat_service_charge');
        });
    }
};
