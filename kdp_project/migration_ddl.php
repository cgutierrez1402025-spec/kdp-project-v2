<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Se ejecuta el DDL completo de KDP Author Manager
        DB::unprepared(file_get_contents(database_path('schema/kdp_ddl.sql')));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Desactivar restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Eliminar todas las tablas en orden inverso
        $tables = [
            'delivery_reviews', 'distribution_visits', 'book_deliveries',
            'stock_movements', 'stock_locations', 'physical_print_runs',
            'event_books', 'book_events', 'award_submissions', 'awards',
            'promotion_costs', 'promotion_daily_results', 'book_promotions',
            'kdp_select_periods', 'kdp_metadata', 'publications',
            'marketplaces', 'platforms', 'illustration_anchors',
            'illustration_versions', 'illustrations', 'prompts',
            'ai_tasks', 'ai_tools', 'source_usages', 'sources',
            'chapters', 'manuscript_versions', 'editions',
            'work_languages', 'works', 'series', 'users',
            'aplus_modules', 'aplus_projects', 'asset_versions',
            'promotional_assets', 'import_errors', 'import_rows',
            'import_mappings', 'import_batches', 'translation_jobs',
            'ocr_text_versions', 'ocr_jobs', 'calibre_imports',
            'activity_logs', 'taggables', 'tags', 'checklist_items',
            'checklists', 'comments', 'tasks', 'royalty_payments',
            'payment_thresholds', 'royalty_entries', 'sessions',
            'password_reset_tokens', 'model_has_permissions',
            'model_has_roles', 'permissions', 'roles'
        ];

        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS `$table`");
        }

        // Reactivar restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
