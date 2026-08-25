CREATE TABLE IF NOT EXISTS `traffic_providers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `provider` ENUM('link4m','layma','link2m') NOT NULL,
  `api_token` TEXT NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), UNIQUE KEY `uq_traffic_provider` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `traffic_tasks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `keyword` VARCHAR(255) DEFAULT NULL,
  `destination_url` TEXT NOT NULL,
  `backup_url` TEXT DEFAULT NULL,
  `provider` ENUM('link4m','layma','link2m') NOT NULL,
  `short_url` TEXT DEFAULT NULL,
  `reward` INT NOT NULL DEFAULT 0,
  `wait_seconds` INT NOT NULL DEFAULT 60,
  `instructions` TEXT DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), KEY `idx_traffic_tasks_status` (`status`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `traffic_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` INT UNSIGNED NOT NULL,
  `user_id` INT NOT NULL,
  `proof` TEXT DEFAULT NULL,
  `status` ENUM('started','pending','approved','rejected') NOT NULL DEFAULT 'started',
  `reward` INT NOT NULL DEFAULT 0,
  `admin_note` TEXT DEFAULT NULL,
  `started_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` DATETIME DEFAULT NULL,
  `reviewed_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `uq_traffic_task_user` (`task_id`,`user_id`),
  KEY `idx_traffic_submissions_status` (`status`,`submitted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
