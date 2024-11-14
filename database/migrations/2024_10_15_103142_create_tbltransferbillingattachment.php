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
        Schema::create('tbltransferbillingattachment', function (Blueprint $table) {
            $table->id();
            $table->text('scheduleid');
            $table->text('enroledid');
            $table->text('title')->nullable()->default(NULL);
            $table->text('attachmenttypeid')->nullable()->default(NULL);
            $table->text('filepath')->nullable()->default(NULL);
            $table->text('posted_by')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbltransferbillingattachment');
    }
};
