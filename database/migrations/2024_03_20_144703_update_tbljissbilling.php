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
            $table->bigInteger('serialnumber')->nullable()->default(NULL)->change();
            $table->integer('billingstatusid')->nullable()->default(0)->change();
            $table->integer('approver_1')->nullable()->default(0)->change();
            $table->integer('approver_2')->nullable()->default(0)->change();
            $table->longText('trainees')->change();
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
