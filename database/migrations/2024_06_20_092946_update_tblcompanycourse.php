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
            $table->double('dorm_2s_price_peso')->nullable()->default(0)->after('dorm_price_dollar');
            $table->double('dorm_2s_price_dollar')->nullable()->default(0)->after('dorm_2s_price_peso');
            $table->double('dorm_4s_price_peso')->nullable()->default(0)->after('dorm_2s_price_dollar');
            $table->double('dorm_4s_price_dollar')->nullable()->default(0)->after('dorm_4s_price_peso');
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
