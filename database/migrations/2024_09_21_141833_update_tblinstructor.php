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
        Schema::table('tblinstructor', function (Blueprint $table) {
            $table->date('datestarttoneti')->nullable()->after('datestartedwithTDG');
            $table->text('viberno')->nullable()->default('NULL')->after('mobilenumber');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblinstructor', function (Blueprint $table) {
            $table->dropColumn('datestarttoneti');
            $table->dropColumn('viberno');
        });
    }
};
