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
        Schema::table('tbltrainingreport', function (Blueprint $table) {
            $table->integer('isTFF')->default(0)->after('Q3');
            $table->integer('isCAS')->default(0)->after('isTFF');
            $table->integer('isSPER')->default(0)->after('isCAS');
            $table->integer('isTR')->default(0)->after('isSPER');
            $table->integer('isOthers')->default(0)->after('isTR');
            $table->string('isOtherForms')->default(NULL)->after('isOthers');
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
