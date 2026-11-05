<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders__data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lang');
            $table->integer('lender_id');
            $table->string('slogan')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('max_apr')->nullable();
            $table->string('apr_example')->nullable();
            $table->string('title')->nullable();
            $table->string('meta_description')->nullable();
            $table->text('description')->nullable();
            $table->string('h1')->nullable();
            $table->string('h1_description')->nullable();
            $table->string('additional_text_1')->nullable();
            $table->string('additional_text_2')->nullable();
            $table->string('additional_text_3')->nullable();
            $table->string('additional_text_4')->nullable();
            $table->string('text_under_box')->nullable();
            $table->string('work_time_m_f')->nullable();
            $table->string('work_time_sa')->nullable();
            $table->string('work_time_su')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->string('anchor_title')->nullable();
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
        Schema::dropIfExists('lenders__data');
    }
}
