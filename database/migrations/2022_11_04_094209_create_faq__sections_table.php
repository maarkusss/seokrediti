<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq__sections', function (Blueprint $table) {
            $table->id();
            $table->integer('faq_id');
            $table->integer('section_id')->nullable();
            $table->integer('section_tag_id')->nullable();
            $table->integer('loan_type_id')->nullable();
            $table->timestamps();

            // Index
            $table->index('faq_id');
            $table->index('section_id');
            $table->index('section_tag_id');
            $table->index('loan_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq__sections');
    }
}
