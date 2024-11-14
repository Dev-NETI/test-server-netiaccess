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
        Schema::create('tbldormitoryreservationslogs', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('userid')->nullable(false);
            $table->string('logs', 250)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbldormitoryreservationslogs');
    }
};
