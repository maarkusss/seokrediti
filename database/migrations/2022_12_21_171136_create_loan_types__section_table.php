<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types__section', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_type_id');
            $table->string('heading')->nullable();
            $table->text('first_step')->nullable();
            $table->text('second_step')->nullable();
            $table->text('third_step')->nullable();
            $table->text('fourth_step')->nullable();
            $table->string('second_heading')->nullable();
            $table->text('second_first_step')->nullable();
            $table->text('second_second_step')->nullable();
            $table->text('second_third_step')->nullable();
            $table->text('second_fourth_step')->nullable();
            $table->text('second_first_image')->nullable();
            $table->text('second_second_image')->nullable();
            $table->text('second_third_image')->nullable();
            $table->text('second_fourth_image')->nullable();
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
        Schema::dropIfExists('loan_types__section');
    }
}
