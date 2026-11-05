<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumersHoiTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumers__hoi_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('consumer_id');
            $table->string('hoi_token', 10);
            $table->boolean('clicked')->default(0);
            $table->timestamp('clicked_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('id');
            $table->index('consumer_id');
            $table->index('hoi_token');
            $table->index('clicked');
            $table->index('clicked_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumers__hoi_token');
    }
}
