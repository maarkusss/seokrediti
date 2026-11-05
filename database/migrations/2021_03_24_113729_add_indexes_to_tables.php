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
            $prefixedName = $table_name . '_' . $index . '_index';
            if (!array_key_exists($index, $existing_indexes) && !array_key_exists($prefixedName, $existing_indexes)) {
                $table->index($index, $prefixedName);
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
        $tables = [
            'clicks' => ['visitor_id', 'lender_id', 'utm_campaign', 'created_at'],
            'commissions' => ['uid', 'commission', 'lender_id', 'status', 'utm_campaign', 'gclid', 'commission_date'],
            'consumers' => ['email'],
            'lenders' => ['name', 'position', 'active'],
            'lenders__data' => ['lang', 'lender_id'],
            'lenders__sorting_epc' => ['sorting_id', 'epc', 'clicks'],
            'lenders__visits' => ['created_at'],
            'lenders__visits_sorting' => ['lender_id', 'position', 'bot', 'created_at'],
            'visitors' => ['ip', 'created_at', 'updated_at'],
        ];

        $sm = Schema::getConnection()->getDoctrineSchemaManager();

        foreach ($tables as $tableName => $indexes) {
            $existing = $sm->listTableIndexes($tableName);
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $indexes, $existing) {
                foreach ($indexes as $index) {
                    $prefixedName = $tableName . '_' . $index . '_index';
                    if (array_key_exists($prefixedName, $existing)) {
                        $table->dropIndex($prefixedName);
                    } elseif (array_key_exists($index, $existing)) {
                        $table->dropIndex($index);
                    }
                }
            });
        }
    }
}
