<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_type_id')->nullable();
            $table->string('lang');
            $table->integer('order');
            $table->string('name');
            $table->string('route_name');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('anchor_element_title')->nullable();
            $table->text('h1')->nullable();
            $table->text('h1_description')->nullable();
            $table->text('text')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('display_dropdown')->default(0);
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
        Schema::dropIfExists('loan_types');
    }
}
