<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add indexes to a table safely if they don't already exist.
     */
    private function addIndexIfMissing(string $tableName, array $columns, ?string $indexName = null): void
    {
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $existingIndexes = $sm->listTableIndexes($tableName);

        $name = $indexName ?? $tableName . '_' . implode('_', $columns) . '_index';

        if (!array_key_exists($name, $existingIndexes)) {
            Schema::table($tableName, function (Blueprint $table) use ($columns, $name) {
                $table->index($columns, $name);
            });
        }
    }

    /**
     * Drop indexes safely if they exist.
     */
    private function dropIndexIfExists(string $tableName, string $indexName): void
    {
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $existingIndexes = $sm->listTableIndexes($tableName);

        if (array_key_exists($indexName, $existingIndexes)) {
            Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. sections table
        if (Schema::hasTable('sections')) {
            $this->addIndexIfMissing('sections', ['route_name']);
            $this->addIndexIfMissing('sections', ['lang']);
            $this->addIndexIfMissing('sections', ['active']);
            $this->addIndexIfMissing('sections', ['type']);
            $this->addIndexIfMissing('sections', ['parent_section_id']);
            $this->addIndexIfMissing('sections', ['order']);
            // Composite index for exact frontend route resolution:
            $this->addIndexIfMissing('sections', ['route_name', 'lang', 'active'], 'sections_lookup_composite_idx');
        }

        // 2. loan_types table
        if (Schema::hasTable('loan_types')) {
            $this->addIndexIfMissing('loan_types', ['route_name']);
            $this->addIndexIfMissing('loan_types', ['parent_type_id']);
            $this->addIndexIfMissing('loan_types', ['lang']);
            $this->addIndexIfMissing('loan_types', ['active']);
            $this->addIndexIfMissing('loan_types', ['order']);
            // Composite index for exact route resolution:
            $this->addIndexIfMissing('loan_types', ['route_name', 'lang', 'active'], 'loan_types_lookup_composite_idx');
        }

        // 3. settings table
        if (Schema::hasTable('settings')) {
            $this->addIndexIfMissing('settings', ['name']);
        }

        // 4. reviews table
        if (Schema::hasTable('reviews')) {
            $this->addIndexIfMissing('reviews', ['lender_id']);
            $this->addIndexIfMissing('reviews', ['lang']);
            $this->addIndexIfMissing('reviews', ['created_at']);
        }

        // 5. lenders__categories table (pivot)
        if (Schema::hasTable('lenders__categories')) {
            $this->addIndexIfMissing('lenders__categories', ['lender_id']);
            $this->addIndexIfMissing('lenders__categories', ['loan_type_id']);
            $this->addIndexIfMissing('lenders__categories', ['lender_id', 'loan_type_id'], 'lenders_categories_pair_idx');
        }

        // 6. loan_types__section table
        if (Schema::hasTable('loan_types__section')) {
            $this->addIndexIfMissing('loan_types__section', ['loan_type_id']);
        }

        // 7. lendercampaigns table
        if (Schema::hasTable('lendercampaigns')) {
            $this->addIndexIfMissing('lendercampaigns', ['active']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sections')) {
            $this->dropIndexIfExists('sections', 'sections_lookup_composite_idx');
            $this->dropIndexIfExists('sections', 'sections_route_name_index');
            $this->dropIndexIfExists('sections', 'sections_lang_index');
            $this->dropIndexIfExists('sections', 'sections_active_index');
            $this->dropIndexIfExists('sections', 'sections_type_index');
            $this->dropIndexIfExists('sections', 'sections_parent_section_id_index');
            $this->dropIndexIfExists('sections', 'sections_order_index');
        }

        if (Schema::hasTable('loan_types')) {
            $this->dropIndexIfExists('loan_types', 'loan_types_lookup_composite_idx');
            $this->dropIndexIfExists('loan_types', 'loan_types_route_name_index');
            $this->dropIndexIfExists('loan_types', 'loan_types_parent_type_id_index');
            $this->dropIndexIfExists('loan_types', 'loan_types_lang_index');
            $this->dropIndexIfExists('loan_types', 'loan_types_active_index');
            $this->dropIndexIfExists('loan_types', 'loan_types_order_index');
        }

        if (Schema::hasTable('settings')) {
            $this->dropIndexIfExists('settings', 'settings_name_index');
        }

        if (Schema::hasTable('reviews')) {
            $this->dropIndexIfExists('reviews', 'reviews_lender_id_index');
            $this->dropIndexIfExists('reviews', 'reviews_lang_index');
            $this->dropIndexIfExists('reviews', 'reviews_created_at_index');
        }

        if (Schema::hasTable('lenders__categories')) {
            $this->dropIndexIfExists('lenders__categories', 'lenders_categories_pair_idx');
            $this->dropIndexIfExists('lenders__categories', 'lenders__categories_lender_id_index');
            $this->dropIndexIfExists('lenders__categories', 'lenders__categories_loan_type_id_index');
        }

        if (Schema::hasTable('loan_types__section')) {
            $this->dropIndexIfExists('loan_types__section', 'loan_types__section_loan_type_id_index');
        }

        if (Schema::hasTable('lendercampaigns')) {
            $this->dropIndexIfExists('lendercampaigns', 'lendercampaigns_active_index');
        }
    }
};
