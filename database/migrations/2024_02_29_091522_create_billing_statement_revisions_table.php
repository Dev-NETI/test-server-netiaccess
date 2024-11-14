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
        Schema::create('billing_statement_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id')->default(NULL);
            $table->unsignedBigInteger('company_id')->default(NULL);
            $table->text('body')->default(NULL);
            $table->string('sent_by')->default(NULL);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_statement_revisions');
    }
};
