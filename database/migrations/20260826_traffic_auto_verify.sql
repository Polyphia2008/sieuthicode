-- Automatic shortlink completion verification (idempotent)
SET @has_max := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='traffic_tasks' AND COLUMN_NAME='max_per_day');
SET @sql := IF(@has_max=0,'ALTER TABLE `traffic_tasks` ADD COLUMN `max_per_day` INT NOT NULL DEFAULT 1 AFTER `reward`','SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
ALTER TABLE `traffic_tasks` MODIFY `provider` ENUM('auto','link4m','layma','link2m') NOT NULL DEFAULT 'auto';
ALTER TABLE `traffic_tasks` MODIFY `destination_url` TEXT NULL;
CREATE TABLE IF NOT EXISTS `traffic_attempts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` INT UNSIGNED NOT NULL,
  `user_id` INT NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `session_hash` CHAR(64) NOT NULL,
  `device_hash` CHAR(64) NOT NULL,
  `ip_hash` CHAR(64) NOT NULL,
  `provider` VARCHAR(20) DEFAULT NULL,
  `short_url` TEXT DEFAULT NULL,
  `status` ENUM('issued','completed','failed','expired') NOT NULL DEFAULT 'issued',
  `reward` INT NOT NULL DEFAULT 0,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_traffic_attempt_token` (`token_hash`),
  KEY `idx_traffic_attempt_daily` (`task_id`,`user_id`,`created_at`,`status`),
  KEY `idx_traffic_attempt_expire` (`status`,`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
