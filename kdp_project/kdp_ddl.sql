-- ====================================================================== 
-- DDL COMPLETO PARA KDP AUTHOR MANAGER 
-- Base de datos: MySQL / MariaDB 
-- Motor: InnoDB, charset utf8mb4 
-- Fecha: 2025 
-- ====================================================================== 

-- Eliminar tablas en orden inverso de dependencias (opcional) 
SET FOREIGN_KEY_CHECKS = 0;

-- ====================================================================== 
-- TABLAS AUXILIARES Y DE SISTEMA 
-- ====================================================================== 
-- Usuarios (base para autenticación Laravel) 
CREATE TABLE IF NOT EXISTS `users` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `email` VARCHAR(255) NOT NULL UNIQUE, 
  `email_verified_at` TIMESTAMP NULL, 
  `password` VARCHAR(255) NOT NULL, 
  `remember_token` VARCHAR(100) NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- Roles y permisos (simplificados) 
CREATE TABLE IF NOT EXISTS `roles` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `guard_name` VARCHAR(255) NOT NULL DEFAULT 'web', 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `permissions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `guard_name` VARCHAR(255) NOT NULL DEFAULT 'web', 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `model_has_roles` ( 
  `role_id` BIGINT UNSIGNED NOT NULL, 
  `model_type` VARCHAR(255) NOT NULL, 
  `model_id` BIGINT UNSIGNED NOT NULL, 
  PRIMARY KEY (`role_id`, `model_id`, `model_type`), 
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `model_has_permissions` ( 
  `permission_id` BIGINT UNSIGNED NOT NULL, 
  `model_type` VARCHAR(255) NOT NULL, 
  `model_id` BIGINT UNSIGNED NOT NULL, 
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`), 
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- Tablas de autenticación de Laravel 
CREATE TABLE IF NOT EXISTS `password_reset_tokens` ( 
  `email` VARCHAR(255) NOT NULL PRIMARY KEY, 
  `token` VARCHAR(255) NOT NULL, 
  `created_at` TIMESTAMP NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `sessions` ( 
  `id` VARCHAR(255) NOT NULL PRIMARY KEY, 
  `user_id` BIGINT UNSIGNED NULL, 
  `ip_address` VARCHAR(45) NULL, 
  `user_agent` TEXT NULL, 
  `payload` LONGTEXT NOT NULL, 
  `last_activity` INT NOT NULL, 
  INDEX `sessions_user_id_index` (`user_id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE OBRAS, SERIES, EDICIONES E IDIOMAS 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `series` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `works` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `series_id` BIGINT UNSIGNED NULL, 
  `series_number` INT NULL, 
  `title_internal` VARCHAR(255) NOT NULL, 
  `title_public` VARCHAR(255) NOT NULL, 
  `subtitle` VARCHAR(255) NULL, 
  `author_name` VARCHAR(255) NOT NULL, 
  `pen_name` VARCHAR(255) NULL, 
  `genre` VARCHAR(100) NULL, 
  `subgenre` VARCHAR(100) NULL, 
  `work_type` VARCHAR(100) NULL, 
  `original_language` CHAR(2) NOT NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'idea', 
  `target_audience` VARCHAR(255) NULL, 
  `age_recommendation` VARCHAR(50) NULL, 
  `description_internal` TEXT NULL, 
  `description_marketing` TEXT NULL, 
  `start_date` DATE NULL, 
  `planned_publish_date` DATE NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `works_user_id_index` (`user_id`), 
  INDEX `works_series_id_index` (`series_id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`series_id`) REFERENCES `series`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `work_languages` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `language_code` CHAR(2) NOT NULL, 
  `regional_variant` VARCHAR(10) NULL, 
  `translated_title` VARCHAR(255) NULL, 
  `translated_subtitle` VARCHAR(255) NULL, 
  `translator_name` VARCHAR(255) NULL, 
  `translation_status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `ai_translation_used` BOOLEAN NOT NULL DEFAULT FALSE, 
  `human_review_level` VARCHAR(50) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  UNIQUE KEY `work_language_unique` (`work_id`, `language_code`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `editions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `edition_number` INT NOT NULL DEFAULT 1, 
  `edition_name` VARCHAR(255) NULL, 
  `edition_type` VARCHAR(50) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE MANUSCRITOS Y VERSIONES HTML 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `manuscript_versions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `parent_version_id` BIGINT UNSIGNED NULL, 
  `edition_id` BIGINT UNSIGNED NULL, 
  `version_number` VARCHAR(50) NOT NULL, 
  `name` VARCHAR(255) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft', 
  `html_content` LONGTEXT NULL, 
  `file_path` VARCHAR(512) NULL, 
  `file_hash` VARCHAR(64) NULL, 
  `word_count` INT NULL, 
  `chapter_count` INT NULL, 
  `image_count` INT NULL, 
  `change_summary` TEXT NULL, 
  `is_candidate` BOOLEAN NOT NULL DEFAULT FALSE, 
  `is_final` BOOLEAN NOT NULL DEFAULT FALSE, 
  `is_published` BOOLEAN NOT NULL DEFAULT FALSE, 
  `published_at` TIMESTAMP NULL, 
  `created_by` BIGINT UNSIGNED NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `manuscript_versions_work_id_index` (`work_id`), 
  INDEX `manuscript_versions_parent_version_id_index` (`parent_version_id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`parent_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`edition_id`) REFERENCES `editions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `chapters` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `manuscript_version_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `chapter_order` INT NOT NULL, 
  `level` INT NOT NULL DEFAULT 1, 
  `title` VARCHAR(255) NULL, 
  `slug` VARCHAR(255) NULL, 
  `html_id` VARCHAR(255) NULL, 
  `start_position` INT NULL, 
  `end_position` INT NULL, 
  `word_count` INT NULL, 
  `status` VARCHAR(50) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `chapters_manuscript_version_id_index` (`manuscript_version_id`), 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE FUENTES DOCUMENTALES 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `sources` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `title` VARCHAR(512) NOT NULL, 
  `author` VARCHAR(255) NULL, 
  `year` VARCHAR(20) NULL, 
  `source_type` VARCHAR(100) NOT NULL, 
  `language_code` CHAR(2) NULL, 
  `url` VARCHAR(512) NULL, 
  `consulted_at` DATE NULL, 
  `citation` TEXT NULL, 
  `summary` TEXT NULL, 
  `rights_status` VARCHAR(100) NULL, 
  `license` VARCHAR(255) NULL, 
  `reliability` VARCHAR(50) NULL, 
  `file_path` VARCHAR(512) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `sources_work_id_index` (`work_id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `source_usages` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `source_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `manuscript_version_id` BIGINT UNSIGNED NULL, 
  `chapter_id` BIGINT UNSIGNED NULL, 
  `fragment` TEXT NULL, 
  `usage_type` VARCHAR(100) NULL, 
  `notes` TEXT NULL, 
  `verified` BOOLEAN NOT NULL DEFAULT FALSE, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`source_id`) REFERENCES `sources`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`chapter_id`) REFERENCES `chapters`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================== 
-- MÓDULO DE INTELIGENCIA ARTIFICIAL Y PROMPTS 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `ai_tools` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `provider` VARCHAR(255) NULL, 
  `tool_type` VARCHAR(100) NOT NULL, 
  `model` VARCHAR(255) NULL, 
  `url` VARCHAR(512) NULL, 
  `strengths` TEXT NULL, 
  `weaknesses` TEXT NULL, 
  `cost_notes` VARCHAR(255) NULL, 
  `quality_score` INT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `ai_tasks` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `task_type` VARCHAR(100) NOT NULL, 
  `preferred_ai_tool_id` BIGINT UNSIGNED NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`preferred_ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `prompts` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `task_id` BIGINT UNSIGNED NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `prompt_text` TEXT NOT NULL, 
  `language_code` CHAR(2) NULL, 
  `purpose` VARCHAR(255) NULL, 
  `result_summary` TEXT NULL, 
  `rating` INT NULL, 
  `reused` BOOLEAN NOT NULL DEFAULT FALSE, 
  `generated_final_content` BOOLEAN NOT NULL DEFAULT FALSE, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `prompts_work_id_index` (`work_id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`task_id`) REFERENCES `ai_tasks`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE ILUSTRACIONES, VERSIONES Y ANCLAJES 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `illustrations` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `image_type` VARCHAR(100) NOT NULL, 
  `file_original` VARCHAR(512) NOT NULL, 
  `file_optimized` VARCHAR(512) NULL, 
  `thumbnail` VARCHAR(512) NULL, 
  `format` VARCHAR(20) NULL, 
  `width` INT NULL, 
  `height` INT NULL, 
  `resolution` INT NULL, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `prompt_id` BIGINT UNSIGNED NULL, 
  `rights_status` VARCHAR(100) NULL, 
  `license` VARCHAR(255) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'idea', 
  `approved` BOOLEAN NOT NULL DEFAULT FALSE, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`prompt_id`) REFERENCES `prompts`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `illustration_versions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `illustration_id` BIGINT UNSIGNED NOT NULL, 
  `version_number` INT NOT NULL, 
  `file_path` VARCHAR(512) NOT NULL, 
  `change_summary` TEXT NULL, 
  `created_by` BIGINT UNSIGNED NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`illustration_id`) REFERENCES `illustrations`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `illustration_anchors` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `illustration_id` BIGINT UNSIGNED NOT NULL, 
  `manuscript_version_id` BIGINT UNSIGNED NOT NULL, 
  `chapter_id` BIGINT UNSIGNED NULL, 
  `anchor_type` VARCHAR(50) NOT NULL, 
  `position_type` VARCHAR(50) NULL, 
  `search_text` TEXT NULL, 
  `search_text_before` TEXT NULL, 
  `search_text_after` TEXT NULL, 
  `css_selector` VARCHAR(512) NULL, 
  `html_marker` VARCHAR(255) NULL, 
  `insertion_mode` VARCHAR(50) NULL, 
  `confidence` VARCHAR(50) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`illustration_id`) REFERENCES `illustrations`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`chapter_id`) REFERENCES `chapters`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================== 
-- MÓDULO DE PLATAFORMAS, PUBLICACIONES Y METADATOS KDP 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `platforms` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `marketplaces` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `platform_id` BIGINT UNSIGNED NOT NULL, 
  `code` VARCHAR(50) NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `currency` CHAR(3) NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `publications` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `manuscript_version_id` BIGINT UNSIGNED NOT NULL, 
  `platform_id` BIGINT UNSIGNED NOT NULL, 
  `marketplace_id` BIGINT UNSIGNED NULL, 
  `format` VARCHAR(50) NOT NULL, 
  `external_identifier` VARCHAR(255) NULL, 
  `public_url` VARCHAR(512) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `price` DECIMAL(10,2) NULL, 
  `currency` CHAR(3) NULL, 
  `territories` TEXT NULL, 
  `isbn` VARCHAR(20) NULL, 
  `asin` VARCHAR(20) NULL, 
  `published_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  UNIQUE KEY `publications_asin_marketplace_unique` (`asin`, `marketplace_id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `kdp_metadata` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `publication_id` BIGINT UNSIGNED NOT NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `subtitle` VARCHAR(255) NULL, 
  `author` VARCHAR(255) NOT NULL, 
  `contributors` TEXT NULL, 
  `series_name` VARCHAR(255) NULL, 
  `series_number` INT NULL, 
  `description` TEXT NULL, 
  `keywords` VARCHAR(255) NULL, 
  `categories` TEXT NULL, 
  `age_range` VARCHAR(50) NULL, 
  `rights` TEXT NULL, 
  `ai_declaration` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`publication_id`) REFERENCES `publications`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE KDP SELECT Y PROMOCIONES 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `kdp_select_periods` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `publication_id` BIGINT UNSIGNED NOT NULL, 
  `start_date` DATE NOT NULL, 
  `end_date` DATE NOT NULL, 
  `auto_renewal` BOOLEAN NOT NULL DEFAULT FALSE, 
  `free_promo_days_allowed` INT NOT NULL DEFAULT 5, 
  `free_promo_days_used` INT NOT NULL DEFAULT 0, 
  `free_promo_days_remaining` INT NOT NULL DEFAULT 5, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'active', 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`publication_id`) REFERENCES `publications`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `book_promotions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `publication_id` BIGINT UNSIGNED NOT NULL, 
  `marketplace_id` BIGINT UNSIGNED NULL, 
  `promotion_type` VARCHAR(50) NOT NULL, 
  `promotion_name` VARCHAR(255) NULL, 
  `start_date` DATE NOT NULL, 
  `end_date` DATE NOT NULL, 
  `normal_price` DECIMAL(10,2) NULL, 
  `promotional_price` DECIMAL(10,2) NULL, 
  `kdp_select_period_id` BIGINT UNSIGNED NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'planned', 
  `objective` TEXT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`publication_id`) REFERENCES `publications`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`kdp_select_period_id`) REFERENCES `kdp_select_periods`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `promotion_daily_results` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `book_promotion_id` BIGINT UNSIGNED NOT NULL, 
  `date` DATE NOT NULL, 
  `paid_units` INT NOT NULL DEFAULT 0, 
  `free_units_promo` INT NOT NULL DEFAULT 0, 
  `free_units_price_match` INT NOT NULL DEFAULT 0, 
  `kenp_pages_read` INT NOT NULL DEFAULT 0, 
  `gross_royalties` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `net_royalties` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `currency` CHAR(3) NULL, 
  `ranking_position` INT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`book_promotion_id`) REFERENCES `book_promotions`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `promotion_costs` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `book_promotion_id` BIGINT UNSIGNED NOT NULL, 
  `cost_type` VARCHAR(100) NOT NULL, 
  `description` TEXT NULL, 
  `amount` DECIMAL(10,2) NOT NULL, 
  `currency` CHAR(3) NOT NULL, 
  `date` DATE NOT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`book_promotion_id`) REFERENCES `book_promotions`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE REGALÍAS, VENTAS Y PAGOS 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `royalty_entries` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `publication_id` BIGINT UNSIGNED NOT NULL, 
  `year` INT NOT NULL, 
  `month` INT NOT NULL, 
  `paid_units` INT NOT NULL DEFAULT 0, 
  `free_units` INT NOT NULL DEFAULT 0, 
  `kenp_pages` INT NOT NULL DEFAULT 0, 
  `royalty_ebook` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `royalty_paperback` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `royalty_hardcover` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `royalty_kenp` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `total_royalty` DECIMAL(10,2) NOT NULL DEFAULT 0, 
  `currency` CHAR(3) NULL, 
  `source_file` VARCHAR(512) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  UNIQUE KEY `royalty_unique` (`publication_id`, `year`, `month`), 
  FOREIGN KEY (`publication_id`) REFERENCES `publications`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `royalty_payments` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `platform_id` BIGINT UNSIGNED NOT NULL, 
  `marketplace_id` BIGINT UNSIGNED NULL, 
  `period_start` DATE NULL, 
  `period_end` DATE NULL, 
  `expected_amount` DECIMAL(10,2) NOT NULL, 
  `received_amount` DECIMAL(10,2) NULL, 
  `withheld_tax` DECIMAL(10,2) NULL, 
  `currency` CHAR(3) NOT NULL, 
  `expected_date` DATE NULL, 
  `received_date` DATE NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `receipt_file` VARCHAR(512) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `payment_thresholds` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `platform_id` BIGINT UNSIGNED NOT NULL, 
  `marketplace_id` BIGINT UNSIGNED NULL, 
  `currency` CHAR(3) NOT NULL, 
  `threshold_amount` DECIMAL(10,2) NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE PREMIOS LITERARIOS Y CONVOCATORIAS 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `awards` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `organizer` VARCHAR(255) NULL, 
  `country` VARCHAR(100) NULL, 
  `city` VARCHAR(100) NULL, 
  `genre` VARCHAR(255) NULL, 
  `language_code` CHAR(2) NULL, 
  `prize_amount` VARCHAR(255) NULL, 
  `url` VARCHAR(512) NULL, 
  `opening_date` DATE NULL, 
  `deadline` DATE NULL, 
  `expected_resolution_date` DATE NULL, 
  `actual_resolution_date` DATE NULL, 
  `requires_unpublished` BOOLEAN NOT NULL DEFAULT FALSE, 
  `forbids_self_publishing` BOOLEAN NOT NULL DEFAULT FALSE, 
  `forbids_simultaneous_submissions` BOOLEAN NOT NULL DEFAULT FALSE, 
  `requires_anonymity` BOOLEAN NOT NULL DEFAULT FALSE, 
  `allows_pseudonym` BOOLEAN NOT NULL DEFAULT FALSE, 
  `min_words` INT NULL, 
  `max_words` INT NULL, 
  `terms` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `award_submissions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `manuscript_version_id` BIGINT UNSIGNED NOT NULL, 
  `award_id` BIGINT UNSIGNED NOT NULL, 
  `submission_date` DATE NOT NULL, 
  `submitted_title` VARCHAR(255) NULL, 
  `pseudonym_used` VARCHAR(255) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'preparing', 
  `result` VARCHAR(50) NULL, 
  `submitted_file` VARCHAR(512) NULL, 
  `proof_file` VARCHAR(512) NULL, 
  `block_publication` BOOLEAN NOT NULL DEFAULT TRUE, 
  `block_until_date` DATE NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`award_id`) REFERENCES `awards`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE EVENTOS Y PRESENTACIONES 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `book_events` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `event_type` VARCHAR(100) NOT NULL, 
  `event_date` DATE NOT NULL, 
  `start_time` TIME NULL, 
  `end_time` TIME NULL, 
  `location_name` VARCHAR(255) NULL, 
  `address` VARCHAR(512) NULL, 
  `city` VARCHAR(100) NULL, 
  `province` VARCHAR(100) NULL, 
  `country` VARCHAR(100) NULL, 
  `organizer` VARCHAR(255) NULL, 
  `contact_person` VARCHAR(255) NULL, 
  `phone` VARCHAR(50) NULL, 
  `email` VARCHAR(255) NULL, 
  `expected_attendance` INT NULL, 
  `actual_attendance` INT NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'idea', 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `event_books` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `event_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `edition_id` BIGINT UNSIGNED NULL, 
  `work_language_id` BIGINT UNSIGNED NULL, 
  `copies_brought` INT NOT NULL DEFAULT 0, 
  `copies_sold` INT NOT NULL DEFAULT 0, 
  `copies_gifted` INT NOT NULL DEFAULT 0, 
  `copies_returned` INT NOT NULL DEFAULT 0, 
  `unit_sale_price` DECIMAL(10,2) NULL, 
  `income_amount` DECIMAL(10,2) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`event_id`) REFERENCES `book_events`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`edition_id`) REFERENCES `editions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE DISTRIBUCIÓN FÍSICA Y STOCK 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `physical_print_runs` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `edition_id` BIGINT UNSIGNED NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `format` VARCHAR(50) NOT NULL, 
  `print_date` DATE NOT NULL, 
  `printer_name` VARCHAR(255) NULL, 
  `copies_printed` INT NOT NULL, 
  `unit_cost` DECIMAL(10,4) NULL, 
  `total_cost` DECIMAL(10,2) NULL, 
  `recommended_retail_price` DECIMAL(10,2) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`edition_id`) REFERENCES `editions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `distribution_points` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `type` VARCHAR(100) NOT NULL, 
  `address` VARCHAR(512) NULL, 
  `city` VARCHAR(100) NULL, 
  `province` VARCHAR(100) NULL, 
  `country` VARCHAR(100) NULL, 
  `phone` VARCHAR(50) NULL, 
  `email` VARCHAR(255) NULL, 
  `website` VARCHAR(512) NULL, 
  `contact_person` VARCHAR(255) NULL, 
  `accepts_consignment` BOOLEAN NOT NULL DEFAULT FALSE, 
  `accepts_direct_purchase` BOOLEAN NOT NULL DEFAULT FALSE, 
  `accepts_events` BOOLEAN NOT NULL DEFAULT FALSE, 
  `default_commission_percentage` DECIMAL(5,2) NULL, 
  `usual_payment_terms` VARCHAR(255) NULL, 
  `relationship_status` VARCHAR(50) NULL, 
  `rating` INT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `stock_locations` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `type` VARCHAR(100) NOT NULL, 
  `distribution_point_id` BIGINT UNSIGNED NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`distribution_point_id`) REFERENCES `distribution_points`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `stock_movements` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `edition_id` BIGINT UNSIGNED NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `print_run_id` BIGINT UNSIGNED NULL, 
  `from_location_id` BIGINT UNSIGNED NULL, 
  `to_location_id` BIGINT UNSIGNED NULL, 
  `movement_type` VARCHAR(100) NOT NULL, 
  `movement_date` DATE NOT NULL, 
  `quantity` INT NOT NULL, 
  `unit_cost` DECIMAL(10,4) NULL, 
  `unit_sale_price` DECIMAL(10,2) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`edition_id`) REFERENCES `editions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`print_run_id`) REFERENCES `physical_print_runs`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`from_location_id`) REFERENCES `stock_locations`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`to_location_id`) REFERENCES `stock_locations`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `book_deliveries` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `edition_id` BIGINT UNSIGNED NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `distribution_point_id` BIGINT UNSIGNED NOT NULL, 
  `delivery_date` DATE NOT NULL, 
  `quantity_delivered` INT NOT NULL, 
  `retail_price` DECIMAL(10,2) NOT NULL, 
  `author_price` DECIMAL(10,2) NOT NULL, 
  `commission_percentage` DECIMAL(5,2) NULL, 
  `agreement_type` VARCHAR(50) NOT NULL, 
  `expected_review_date` DATE NULL, 
  `receipt_file_path` VARCHAR(512) NULL, 
  `received_by` VARCHAR(255) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'active', 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`edition_id`) REFERENCES `editions`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`distribution_point_id`) REFERENCES `distribution_points`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `distribution_visits` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `distribution_point_id` BIGINT UNSIGNED NOT NULL, 
  `visit_date` DATE NOT NULL, 
  `contact_person` VARCHAR(255) NULL, 
  `general_notes` TEXT NULL, 
  `next_visit_date` DATE NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`distribution_point_id`) REFERENCES `distribution_points`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `delivery_reviews` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `book_delivery_id` BIGINT UNSIGNED NOT NULL, 
  `distribution_visit_id` BIGINT UNSIGNED NOT NULL, 
  `copies_remaining_before` INT NOT NULL, 
  `copies_sold` INT NOT NULL, 
  `copies_returned` INT NOT NULL, 
  `copies_restocked` INT NOT NULL, 
  `copies_remaining_after` INT NOT NULL, 
  `amount_to_collect` DECIMAL(10,2) NULL, 
  `amount_collected` DECIMAL(10,2) NULL, 
  `amount_pending` DECIMAL(10,2) NULL, 
  `payment_method` VARCHAR(100) NULL, 
  `review_status` VARCHAR(50) NOT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`book_delivery_id`) REFERENCES `book_deliveries`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`distribution_visit_id`) REFERENCES `distribution_visits`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE MATERIALES PROMOCIONALES Y A+ CONTENT 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `promotional_assets` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NULL, 
  `platform_id` BIGINT UNSIGNED NULL, 
  `marketplace_id` BIGINT UNSIGNED NULL, 
  `asset_type` VARCHAR(100) NOT NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `file_path` VARCHAR(512) NOT NULL, 
  `thumbnail_path` VARCHAR(512) NULL, 
  `file_format` VARCHAR(20) NULL, 
  `width` INT NULL, 
  `height` INT NULL, 
  `file_size` INT NULL, 
  `resolution` INT NULL, 
  `color_space` VARCHAR(50) NULL, 
  `alt_text` VARCHAR(255) NULL, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `prompt_id` BIGINT UNSIGNED NULL, 
  `rights_status` VARCHAR(100) NULL, 
  `license` VARCHAR(255) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'idea', 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`prompt_id`) REFERENCES `prompts`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `asset_versions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `promotional_asset_id` BIGINT UNSIGNED NOT NULL, 
  `version_number` INT NOT NULL, 
  `file_path` VARCHAR(512) NOT NULL, 
  `change_summary` TEXT NULL, 
  `created_by` BIGINT UNSIGNED NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`promotional_asset_id`) REFERENCES `promotional_assets`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `aplus_projects` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `work_language_id` BIGINT UNSIGNED NOT NULL, 
  `publication_id` BIGINT UNSIGNED NOT NULL, 
  `platform_id` BIGINT UNSIGNED NOT NULL, 
  `marketplace_id` BIGINT UNSIGNED NOT NULL, 
  `asin` VARCHAR(20) NOT NULL, 
  `language_code` CHAR(2) NOT NULL, 
  `title` VARCHAR(255) NULL, 
  `commercial_goal` TEXT NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft', 
  `submitted_at` TIMESTAMP NULL, 
  `approved_at` TIMESTAMP NULL, 
  `published_at` TIMESTAMP NULL, 
  `rejected_at` TIMESTAMP NULL, 
  `rejection_reason` TEXT NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`publication_id`) REFERENCES `publications`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`marketplace_id`) REFERENCES `marketplaces`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `aplus_modules` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `aplus_project_id` BIGINT UNSIGNED NOT NULL, 
  `module_type` VARCHAR(100) NOT NULL, 
  `module_order` INT NOT NULL, 
  `headline` VARCHAR(255) NULL, 
  `body_text` TEXT NULL, 
  `image_asset_id` BIGINT UNSIGNED NULL, 
  `secondary_image_asset_id` BIGINT UNSIGNED NULL, 
  `alt_text` VARCHAR(255) NULL, 
  `comparison_asins` TEXT NULL, 
  `status` VARCHAR(50) NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`aplus_project_id`) REFERENCES `aplus_projects`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`image_asset_id`) REFERENCES `promotional_assets`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`secondary_image_asset_id`) REFERENCES `promotional_assets`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE IMPORTACIONES Y MIGRACIONES 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `import_batches` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `import_type` VARCHAR(100) NOT NULL, 
  `source_system` VARCHAR(100) NULL, 
  `original_file_path` VARCHAR(512) NOT NULL, 
  `original_file_name` VARCHAR(255) NOT NULL, 
  `file_hash` VARCHAR(64) NOT NULL, 
  `detected_format` VARCHAR(50) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `started_at` TIMESTAMP NULL, 
  `finished_at` TIMESTAMP NULL, 
  `processed_by_ai` BOOLEAN NOT NULL DEFAULT FALSE, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  UNIQUE KEY `import_batches_file_hash_unique` (`file_hash`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `import_mappings` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `import_batch_id` BIGINT UNSIGNED NOT NULL, 
  `external_column_name` VARCHAR(255) NOT NULL, 
  `internal_entity` VARCHAR(100) NULL, 
  `internal_field` VARCHAR(100) NULL, 
  `confidence` DECIMAL(5,2) NULL, 
  `mapped_by_ai` BOOLEAN NOT NULL DEFAULT FALSE, 
  `confirmed_by_user` BOOLEAN NOT NULL DEFAULT FALSE, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `import_rows` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `import_batch_id` BIGINT UNSIGNED NOT NULL, 
  `row_number` INT NOT NULL, 
  `raw_data_json` JSON NOT NULL, 
  `normalized_data_json` JSON NULL, 
  `validation_status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `error_message` TEXT NULL, 
  `linked_work_id` BIGINT UNSIGNED NULL, 
  `linked_publication_id` BIGINT UNSIGNED NULL, 
  `linked_royalty_entry_id` BIGINT UNSIGNED NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`linked_work_id`) REFERENCES `works`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`linked_publication_id`) REFERENCES `publications`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`linked_royalty_entry_id`) REFERENCES `royalty_entries`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `import_errors` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `import_batch_id` BIGINT UNSIGNED NOT NULL, 
  `severity` VARCHAR(50) NOT NULL, 
  `error_type` VARCHAR(100) NOT NULL, 
  `message` TEXT NOT NULL, 
  `row_number` INT NULL, 
  `field_name` VARCHAR(255) NULL, 
  `suggested_solution` TEXT NULL, 
  `resolved` BOOLEAN NOT NULL DEFAULT FALSE, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE CALIBRE, OCR Y TRADUCCIONES EXTERNAS 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `calibre_imports` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `import_batch_id` BIGINT UNSIGNED NOT NULL, 
  `calibre_book_id` VARCHAR(255) NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `author` VARCHAR(255) NULL, 
  `series` VARCHAR(255) NULL, 
  `series_index` INT NULL, 
  `language_code` CHAR(2) NULL, 
  `tags` TEXT NULL, 
  `opf_path` VARCHAR(512) NULL, 
  `cover_path` VARCHAR(512) NULL, 
  `available_formats_json` JSON NULL, 
  `matched_work_id` BIGINT UNSIGNED NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`matched_work_id`) REFERENCES `works`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `ocr_jobs` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `source_id` BIGINT UNSIGNED NULL, 
  `import_batch_id` BIGINT UNSIGNED NULL, 
  `input_file_path` VARCHAR(512) NOT NULL, 
  `ocr_engine` VARCHAR(100) NOT NULL DEFAULT 'tesseract', 
  `language_code` CHAR(2) NOT NULL, 
  `output_txt_path` VARCHAR(512) NULL, 
  `output_hocr_path` VARCHAR(512) NULL, 
  `output_tsv_path` VARCHAR(512) NULL, 
  `output_pdf_path` VARCHAR(512) NULL, 
  `confidence_score` DECIMAL(5,2) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `started_at` TIMESTAMP NULL, 
  `finished_at` TIMESTAMP NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`source_id`) REFERENCES `sources`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `ocr_text_versions` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `ocr_job_id` BIGINT UNSIGNED NOT NULL, 
  `version_type` VARCHAR(50) NOT NULL, 
  `text_content` LONGTEXT NOT NULL, 
  `processed_by_ai` BOOLEAN NOT NULL DEFAULT FALSE, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `human_reviewed` BOOLEAN NOT NULL DEFAULT FALSE, 
  `reviewed_by` BIGINT UNSIGNED NULL, 
  `reviewed_at` TIMESTAMP NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`ocr_job_id`) REFERENCES `ocr_jobs`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`reviewed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `translation_jobs` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `source_work_language_id` BIGINT UNSIGNED NOT NULL, 
  `target_language_code` CHAR(2) NOT NULL, 
  `source_manuscript_version_id` BIGINT UNSIGNED NOT NULL, 
  `tool_type` VARCHAR(100) NOT NULL, 
  `tool_name` VARCHAR(255) NULL, 
  `ai_tool_id` BIGINT UNSIGNED NULL, 
  `calibre_used` BOOLEAN NOT NULL DEFAULT FALSE, 
  `calibre_plugin_name` VARCHAR(255) NULL, 
  `input_file_path` VARCHAR(512) NULL, 
  `output_file_path` VARCHAR(512) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `human_review_status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `started_at` TIMESTAMP NULL, 
  `finished_at` TIMESTAMP NULL, 
  `notes` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`source_work_language_id`) REFERENCES `work_languages`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`source_manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`ai_tool_id`) REFERENCES `ai_tools`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- MÓDULO DE TAREAS, COMENTARIOS, CHECKLISTS Y ACTIVIDAD 
-- ====================================================================== 
CREATE TABLE IF NOT EXISTS `tasks` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `assigned_to` BIGINT UNSIGNED NULL, 
  `title` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `task_type` VARCHAR(100) NULL, 
  `priority` VARCHAR(50) NULL, 
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending', 
  `due_date` DATE NULL, 
  `completed_at` TIMESTAMP NULL, 
  `created_by` BIGINT UNSIGNED NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL, 
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `comments` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NOT NULL, 
  `work_id` BIGINT UNSIGNED NULL, 
  `manuscript_version_id` BIGINT UNSIGNED NULL, 
  `chapter_id` BIGINT UNSIGNED NULL, 
  `task_id` BIGINT UNSIGNED NULL, 
  `comment` TEXT NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`manuscript_version_id`) REFERENCES `manuscript_versions`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`chapter_id`) REFERENCES `chapters`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `checklists` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `work_id` BIGINT UNSIGNED NOT NULL, 
  `name` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`work_id`) REFERENCES `works`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `checklist_items` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `checklist_id` BIGINT UNSIGNED NOT NULL, 
  `item` VARCHAR(255) NOT NULL, 
  `is_checked` BOOLEAN NOT NULL DEFAULT FALSE, 
  `checked_by` BIGINT UNSIGNED NULL, 
  `checked_at` TIMESTAMP NULL, 
  `order` INT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  FOREIGN KEY (`checklist_id`) REFERENCES `checklists`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`checked_by`) REFERENCES `users`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `tags` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(255) NOT NULL, 
  `created_at` TIMESTAMP NULL, 
  `updated_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  UNIQUE KEY `tags_name_unique` (`name`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `taggables` ( 
  `tag_id` BIGINT UNSIGNED NOT NULL, 
  `taggable_type` VARCHAR(255) NOT NULL, 
  `taggable_id` BIGINT UNSIGNED NOT NULL, 
  PRIMARY KEY (`tag_id`, `taggable_id`, `taggable_type`), 
  FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE IF NOT EXISTS `activity_logs` ( 
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
  `user_id` BIGINT UNSIGNED NULL, 
  `action` VARCHAR(255) NOT NULL, 
  `description` TEXT NULL, 
  `loggable_type` VARCHAR(255) NULL, 
  `loggable_id` BIGINT UNSIGNED NULL, 
  `properties` JSON NULL, 
  `created_at` TIMESTAMP NULL, 
  PRIMARY KEY (`id`), 
  INDEX `activity_logs_user_id_index` (`user_id`), 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- ====================================================================== 
-- ÍNDICES ADICIONALES PARA RENDIMIENTO 
-- ====================================================================== 
CREATE INDEX idx_works_user_status ON works(user_id, status); 
CREATE INDEX idx_manuscript_versions_work_language ON manuscript_versions(work_id, work_language_id); 
CREATE INDEX idx_illustrations_work ON illustrations(work_id); 
CREATE INDEX idx_publications_work_language ON publications(work_id, work_language_id); 
CREATE INDEX idx_royalty_entries_publication ON royalty_entries(publication_id); 
CREATE INDEX idx_book_promotions_dates ON book_promotions(start_date, end_date); 
CREATE INDEX idx_stock_movements_date ON stock_movements(movement_date); 
CREATE INDEX idx_import_batches_user ON import_batches(user_id); 
CREATE INDEX idx_ocr_jobs_status ON ocr_jobs(status); 
CREATE INDEX idx_tasks_work_status ON tasks(work_id, status); 

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================== 
-- FIN DEL SCRIPT DDL 
-- ======================================================================
