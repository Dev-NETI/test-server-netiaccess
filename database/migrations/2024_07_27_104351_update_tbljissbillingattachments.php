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
        Schema::table('tbljissbillingattachments', function (Blueprint $table) {
            $table->unsignedBigInteger('jissbillingid')->after('id');
            $table->integer('filetype')->after('jissbillingid')->comment('1 - Proof of Payment, 2 - Official Receipt');
            $table->text('attachmentpath')->after('filetype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbljissbillingattachments', function (Blueprint $table) {
            $table->dropColumn('jissbillingid');
            $table->dropColumn('filetype');
            $table->dropColumn('attachmentpath');
        });
    }
};
