<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tblsla', function (Blueprint $table) {
            $table->id(); // id (auto-increment primary key)
            $table->unsignedBigInteger('instructorid')->nullable()->unique(); // instructorid
            $table->unsignedBigInteger('userid')->nullable()->unique(); // userid
            $table->date('datesigned')->nullable(); // datesigned
            $table->string('period')->nullable(); // period
            $table->date('datefrom')->nullable(); // datefrom
            $table->date('dateto')->nullable(); // dateto
            $table->boolean('isNMC')->default(false); // isNMC
            $table->boolean('isMandatory')->default(false); // isMandatory
            $table->boolean('isUpgrading')->default(false); // isUpgrading
            $table->boolean('isIMMAJPJMCC')->default(false); // isIMMAJPJMCC
            $table->decimal('rate', 8, 2)->nullable(); // rate
            $table->unsignedBigInteger('modified_by')->nullable(); // modified_by
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblsla');
    }
};
