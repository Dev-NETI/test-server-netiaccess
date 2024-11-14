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
            $table->boolean('toggleBillingEmailNotification')->after('lastpde_serialnumber')->default(true);
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
