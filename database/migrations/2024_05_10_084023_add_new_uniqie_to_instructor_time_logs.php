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
        Schema::table('instructor_time_logs', function (Blueprint $table) {
            $table->dropUnique(['timestamp_type','created_date']); 
            $table->unique(['timestamp_type','created_date','user_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_time_logs', function (Blueprint $table) {
            //
        });
    }
};
