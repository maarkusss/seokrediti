<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('visitor_id')->nullable();
            $table->integer('uid');
            $table->string('transaction_id');
            $table->float('commission');
            $table->string('lender_id');
            $table->string('referrer', 500)->nullable();
            $table->string('ip')->nullable();
            $table->string('status');
            $table->string('utm_campaign')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('gclid')->nullable();
            $table->timestamp('commission_date');
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
        Schema::dropIfExists('commissions');
    }
}
