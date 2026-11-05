<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types__history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_type_id')->nullable();
            $table->integer('user_id');
            $table->integer('loan_type_id');
            $table->string('lang');
            $table->string('name');
            $table->string('route_name');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('anchor_element_title')->nullable();
            $table->text('h1')->nullable();
            $table->text('h1_description')->nullable();
            $table->text('text')->nullable();
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
        Schema::dropIfExists('loan_types__history');
    }
}
