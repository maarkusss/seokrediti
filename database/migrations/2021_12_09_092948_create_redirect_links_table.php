<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect_links', function (Blueprint $table) {
            $table->id();
            $table->string('url_from');
            $table->string('url_to');
            $table->timestamps();

            // Indexes
            $table->index('url_from');
            $table->index('url_to');
            $table->index(['url_from', 'url_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirect_links');
    }
}
