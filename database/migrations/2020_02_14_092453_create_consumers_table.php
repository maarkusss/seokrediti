<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token')->nullable();
            $table->integer('visitor_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('birth')->nullable();
            $table->string('country', 2);
            $table->string('email')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->integer('phone')->nullable();
            $table->integer('amount')->nullable();
            $table->string('hoi_token', 10)->nullable();
            $table->string('fingerprint', 20)->nullable();
            $table->string('term')->nullable();
            $table->string('type')->nullable();
            $table->boolean('email_notifications')->default(0);
            $table->boolean('confirmed')->default(0);
            $table->timestamp('confirmed_at')->nullable();
            $table->boolean('subscribed')->nullable();
            $table->string('ip');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('gclid')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('hoi_token');
            $table->index('fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumers');
    }
}
