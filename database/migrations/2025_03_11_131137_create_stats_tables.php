<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks__stats', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->mediumInteger('count')->unsigned();

            $table->index('date');
        });

        Schema::create('visitors__stats', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->mediumInteger('count')->unsigned();

            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clicks__stats');
        Schema::dropIfExists('visitors__stats');
    }
};
