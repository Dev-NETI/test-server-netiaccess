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
            $table->text('month_covered')->nullable()->default(NULL)->after('trainingtitle');
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
