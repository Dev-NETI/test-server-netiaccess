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
        Schema::create('tblfailuretimeinout', function (Blueprint $table) {
            $table->id();
            $table->string('hash');
            $table->unsignedBigInteger('user_id')->comment('created_by');
            $table->dateTime('dateTime');
            $table->unsignedBigInteger('course');
            $table->integer('type')->comment('1 = time in, 2 = time out');
            $table->integer('status')->nullable()->default(1)->comment('1 = Pending, 2 = Approved, 3 = Disapproved');
            $table->text('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblfailuretimeinout');
    }
};
