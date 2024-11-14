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
        Schema::table('tbldormitoryreservationslogs', function (Blueprint $table) {
            $table->text('updated_by')->after('created_by')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbldormitoryreservationslogs', function (Blueprint $table) {
            //
        });
    }
};
