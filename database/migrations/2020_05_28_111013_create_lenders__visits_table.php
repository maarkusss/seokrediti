<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders__visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('visitor_id')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->boolean('bot')->nullable();
            $table->timestamps();

            $table->index(['visitor_id', 'utm_campaign']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lenders__visits');
    }
}
