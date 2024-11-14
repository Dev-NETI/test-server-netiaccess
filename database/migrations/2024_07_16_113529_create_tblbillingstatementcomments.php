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
        Schema::create('tblbillingstatementcomments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheduleid')->unique();
            $table->text('comment');
            $table->text('creator');
            $table->boolean('isactive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbillingstatementcomments');
    }
};
