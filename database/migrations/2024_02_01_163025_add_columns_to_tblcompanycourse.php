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
            $table->double('meal_price_dollar')->after('meal_price')->default(0);
            $table->double('dorm_price_dollar')->after('dorm_price')->default(0);
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
