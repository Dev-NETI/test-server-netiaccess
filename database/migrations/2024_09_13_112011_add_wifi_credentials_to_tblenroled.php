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
            $table->text('wifi_username')->nullable();
            $table->text('wifi_password')->nullable();
            $table->text('wifi_expiration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblenroled', function (Blueprint $table) {
            //
        });
    }
};
