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
        Schema::table('tbljisscourses', function (Blueprint $table) {
            $table->text('templateName')->nullable()->default(NULL)->after('coursename');
            $table->text('templatePath')->nullable()->default(NULL)->after('templateName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljisscourses', function (Blueprint $table) {
            //
        });
    }
};
