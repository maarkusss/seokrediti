<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    /**
     * Add indexes to the provided table rows.
     * @param $table
     * @param $table_name
     * @param $indexes
     * @return void
     */
    private function addIndexes($table, $table_name, $indexes)
    {
        // Get the DB connection and get the current indexes in the table
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $existing_indexes = $sm->listTableIndexes($table_name);
        // Iterate over the indexes array and see if the values are not
        // already in use, if not, add them
        foreach ($indexes as $index) {
            if (!array_key_exists($index, $existing_indexes)) {
                $table->index($index, $index);
            }
        }
    }

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table_name = 'clicks';
            $indexes = ['visitor_id', 'lender_id', 'utm_campaign', 'created_at'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table_name = 'commissions';
            $indexes = ['uid', 'commission', 'lender_id', 'status', 'utm_campaign', 'gclid', 'commission_date'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('consumers', function (Blueprint $table) {
            $table_name = 'consumers';
            $indexes = ['email'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('lenders', function (Blueprint $table) {
            $table_name = 'lenders';
            $indexes = ['name', 'position', 'active'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('lenders__data', function (Blueprint $table) {
            $table_name = 'lenders__data';
            $indexes = ['lang', 'lender_id'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('lenders__sorting_epc', function (Blueprint $table) {
            $table_name = 'lenders__sorting_epc';
            $indexes = ['sorting_id', 'epc', 'clicks'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('lenders__visits', function (Blueprint $table) {
            $table_name = 'lenders__visits';
            $indexes = ['created_at'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('lenders__visits_sorting', function (Blueprint $table) {
            $table_name = 'lenders__visits_sorting';
            $indexes = ['lender_id', 'position', 'bot', 'created_at'];
            $this->addIndexes($table, $table_name, $indexes);
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table_name = 'visitors';
            $indexes = ['ip', 'created_at', 'updated_at'];
            $this->addIndexes($table, $table_name, $indexes);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('clicks', function (Blueprint $table) {
            // Array of indexes to remove
            $indexes = ['visitor_id', 'lender_id', 'utm_campaign', 'created_at'];
            // Iterate over the indexes to remove them
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('commissions', function (Blueprint $table) {
            $indexes = ['uid', 'commission', 'lender_id', 'status', 'utm_campaign', 'gclid', 'commission_date'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('consumers', function (Blueprint $table) {
            $indexes = ['email'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('lenders', function (Blueprint $table) {
            $indexes = ['name', 'position', 'active'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('lenders__data', function (Blueprint $table) {
            $indexes = ['lang', 'lender_id'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('lenders__sorting_epc', function (Blueprint $table) {
            $indexes = ['sorting_id', 'epc', 'clicks'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('lenders__visits', function (Blueprint $table) {
            $indexes = ['created_at'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('lenders__visits_sorting', function (Blueprint $table) {
            $indexes = ['lender_id', 'position', 'bot', 'created_at'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });

        Schema::table('visitors', function (Blueprint $table) {
            $indexes = ['ip', 'created_at', 'updated_at'];
            foreach ($indexes as $index) {
                $table->dropIndex($index);
            }
        });
    }
}
