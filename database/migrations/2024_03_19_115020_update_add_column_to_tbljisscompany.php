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
        Schema::table('tbljisscompany', function (Blueprint $table) {
            $table->string('recipientname')->after('company');
            $table->string('recipientposition')->after('recipientname');
            $table->string('companyaddressline')->after('recipientposition');
            $table->string('companyaddressline2')->after('companyaddressline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljisscompany', function (Blueprint $table) {
            //
        });
    }
};
