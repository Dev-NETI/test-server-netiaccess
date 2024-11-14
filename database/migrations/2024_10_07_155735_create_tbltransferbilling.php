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
        Schema::create('tbltransferbilling', function (Blueprint $table) {
            $table->id();
            $table->text('enroledid');
            $table->text('scheduleid');
            $table->text('serialnumber');
            $table->text('traineeid');
            $table->date('datebilled');
            $table->boolean('sig1')->nullable()->default(0);
            $table->boolean('sig2')->nullable()->default(0);
            $table->boolean('sig3')->nullable()->default(0);
            $table->text('notes_comments')->nullable()->default(NULL);
            $table->text('deletedid')->nullable()->default(0);
            $table->text('vesselid')->nullable()->default(0);
            $table->integer('payeecompanyid');
            $table->integer('billingstatusid');
            $table->text('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbltransferbilling');
    }
};
