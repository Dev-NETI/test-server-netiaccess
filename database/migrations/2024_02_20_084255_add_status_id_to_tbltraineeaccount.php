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
        Schema::table('tbltraineeaccount', function (Blueprint $table) {
            $table->integer('status_id')->default(1)->after('vessel')->comment("1 - On Board , 2 - On Vacation , 3 - Earmark , 4 - Inactive , 5 - No Record , 6 - Reserve Crew");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbltraineeaccount', function (Blueprint $table) {
            //
        });
    }
};
