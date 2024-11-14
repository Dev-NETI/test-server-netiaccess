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
        Schema::create('tblbillingboardhistory', function (Blueprint $table) {
            $table->id();
            $table->text('scheduleid');
            $table->text('companyid');
            $table->text('isunderbycompanyid')->nullable()->default(NULL);
            $table->text('serialnumber')->nullable()->default(NULL);
            $table->text('fromboard')->nullable()->default(NULL);
            $table->text('toboard')->nullable()->default(NULL);;
            $table->text('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbillingboardhistory');
    }
};
