<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('loan_types') && !Schema::hasColumn('loan_types', 'text_layout')) {
            Schema::table('loan_types', function (Blueprint $table) {
                $table->string('text_layout', 32)->default('panel')->after('text');
            });
        }

        if (Schema::hasTable('loan_types__history') && !Schema::hasColumn('loan_types__history', 'text_layout')) {
            Schema::table('loan_types__history', function (Blueprint $table) {
                $table->string('text_layout', 32)->default('panel')->after('text');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('loan_types') && Schema::hasColumn('loan_types', 'text_layout')) {
            Schema::table('loan_types', function (Blueprint $table) {
                $table->dropColumn('text_layout');
            });
        }

        if (Schema::hasTable('loan_types__history') && Schema::hasColumn('loan_types__history', 'text_layout')) {
            Schema::table('loan_types__history', function (Blueprint $table) {
                $table->dropColumn('text_layout');
            });
        }
    }
};
