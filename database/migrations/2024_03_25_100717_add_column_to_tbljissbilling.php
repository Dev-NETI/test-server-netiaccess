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
            $table->double('dorm_expenses')->nullable()->default(0)->after('approver_2');
            $table->double('meal_expenses')->nullable()->default(0)->after('dorm_expenses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljissbilling', function (Blueprint $table) {
            //
        });
    }
};
