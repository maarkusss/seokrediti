<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections__history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('standard');
            $table->integer('user_id');
            $table->integer('section_id');
            $table->string('parent_section_id')->nullable();
            $table->string('lang');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('anchor_element_title')->nullable();
            $table->string('h1')->nullable();
            $table->string('h1_description')->nullable();
            $table->string('route_name');
            $table->string('name');
            $table->string('redirect_link')->nullable();
            $table->text('source')->nullable();
            $table->mediumtext('text')->nullable();
            $table->boolean('display_in_the_header')->default(0);
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
        Schema::dropIfExists('sections__history');
    }
}
