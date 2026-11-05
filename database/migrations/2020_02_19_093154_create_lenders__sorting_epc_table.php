<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersSortingEpcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders__sorting_epc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sorting_id');
            $table->integer('lender_id');
            $table->integer('position')->nullable();
            $table->float('earnings')->nullable();
            $table->double('epc', 8, 2)->default(0);
            $table->integer('clicks')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lenders__sorting_epc');
    }
}
