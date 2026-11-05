<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lenders__data', function (Blueprint $table) {
            $table->text('section_html')->nullable()->after('anchor_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lenders__data', function (Blueprint $table) {
            $table->dropcolumn(['section_html']);
        });
    }
};
