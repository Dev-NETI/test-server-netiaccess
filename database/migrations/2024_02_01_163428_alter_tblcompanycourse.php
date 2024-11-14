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
            $table->renameColumn('meal_price', 'meal_price_peso');
            $table->renameColumn('dorm_price', 'dorm_price_peso');
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
