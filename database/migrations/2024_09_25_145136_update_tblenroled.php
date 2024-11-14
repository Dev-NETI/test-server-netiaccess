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
        Schema::table('tblenroled', function (Blueprint $table) {
            $table->unsignedBigInteger('companyid')->nullable()->default(NULL)->after('traineeid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblenroled', function (Blueprint $table) {
            $table->dropColumn('companyid');
        });
    }
};
