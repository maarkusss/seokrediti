<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image');
            $table->string('affiliate_link');
            $table->string('route_name');
            $table->integer('position')->nullable();
            $table->integer('frequency')->nullable();
            $table->integer('daily_epc')->nullable();
            $table->integer('daily_epc_before')->nullable();
            $table->float('guaranteed_epc')->nullable();
            $table->float('earnings')->default(0);
            $table->integer('clicks')->default(0);
            $table->float('epc')->default(0);
            $table->string('first_loan');
            $table->integer('min_amount')->default(0);
            $table->integer('max_amount')->default(0);
            $table->string('min_term');
            $table->string('max_term');
            $table->string('min_years');
            $table->string('max_years');
            $table->string('receiving_time');
            $table->boolean('active')->default(0);
            $table->boolean('zero_percent')->default(0);
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
        Schema::dropIfExists('lenders');
    }
}
