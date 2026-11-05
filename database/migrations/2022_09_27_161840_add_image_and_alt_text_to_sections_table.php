<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageAndAltTextToSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->string('image')->nullable()->after('redirect_link');
            $table->string('image_alt_text')->nullable()->after('image');
        });

        Schema::table('sections__history', function (Blueprint $table) {
            $table->string('image')->nullable()->after('redirect_link');
            $table->string('image_alt_text')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image_alt_text');
        });

        Schema::table('sections__history', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image_alt_text');
        });
    }
}
