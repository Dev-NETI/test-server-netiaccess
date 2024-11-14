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
        Schema::create('selected_company_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_information_id')->default(0);
            $table->unsignedBigInteger('company_email_id')->default(0);
            $table->timestamps();
            $table->unique(['client_information_id','company_email_id']);
            
            $table->foreign('client_information_id')->references('id')->on('client_information');
            $table->foreign('company_email_id')->references('id')->on('company_emails');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selected_company_emails');
    }
};
