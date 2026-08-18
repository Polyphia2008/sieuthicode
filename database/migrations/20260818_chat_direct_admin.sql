-- Direct member-to-admin chat + presence (idempotent upgrade)

CREATE TABLE IF NOT EXISTS `chat_presence` (
  `user_id` INT NOT NULL,
  `last_seen` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `idx_chat_presence_last_seen` (`last_seen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @has_admin_id := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chat_conversations' AND COLUMN_NAME = 'admin_id'
);
SET @sql := IF(@has_admin_id = 0,
  'ALTER TABLE `chat_conversations` ADD COLUMN `admin_id` INT NOT NULL DEFAULT 0 AFTER `user_id`',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Assign legacy support conversations to the first active admin/superadmin.
UPDATE `chat_conversations` c
SET c.`admin_id` = COALESCE((
  SELECT x.`id` FROM (
    SELECT `id` FROM `users`
    WHERE `level` IN ('admin','superadmin') AND `banned` = 0
    ORDER BY (`level` = 'superadmin') DESC, `id` ASC LIMIT 1
  ) x
), 0)
WHERE c.`admin_id` = 0;

SET @has_old_unique := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chat_conversations'
    AND INDEX_NAME = 'uq_chat_conversations_user'
);
SET @sql := IF(@has_old_unique > 0,
  'ALTER TABLE `chat_conversations` DROP INDEX `uq_chat_conversations_user`',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @has_direct_unique := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chat_conversations'
    AND INDEX_NAME = 'uq_chat_conversations_user_admin'
);
SET @sql := IF(@has_direct_unique = 0,
  'ALTER TABLE `chat_conversations` ADD UNIQUE KEY `uq_chat_conversations_user_admin` (`user_id`,`admin_id`)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @has_admin_index := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chat_conversations'
    AND INDEX_NAME = 'idx_chat_conversations_admin'
);
SET @sql := IF(@has_admin_index = 0,
  'ALTER TABLE `chat_conversations` ADD KEY `idx_chat_conversations_admin` (`admin_id`,`last_message_at`)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
