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
        Schema::create('instructor_time_logs', function (Blueprint $table) {
            $table->id();
            $table->text('hash')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->time('time')->nullable();
            $table->text('timestamp_type')->nullable()->comment('1 - AM time in , 2 - PM time out');
            $table->integer('regular')->nullable();
            $table->integer('late')->nullable();
            $table->integer('undertime')->nullable();
            $table->integer('overtime')->nullable();
            $table->integer('status')->nullable()->comment('1 - Req , 2 - Approve , 3 - Declined');
            $table->boolean('is_active')->default(true);
            $table->text('modified_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_time_logs');
    }
};
