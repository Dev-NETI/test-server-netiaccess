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
            $table->date('created_date')->after('modified_by')->nullable();
            $table->unique(['timestamp_type','created_date']);
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
