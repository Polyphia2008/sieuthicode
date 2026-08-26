SET @has_bonus := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='traffic_tasks' AND COLUMN_NAME='bonus_reward');
SET @sql := IF(@has_bonus=0,'ALTER TABLE `traffic_tasks` ADD COLUMN `bonus_reward` INT NOT NULL DEFAULT 0 AFTER `reward`, ADD COLUMN `bonus_start` TIME NULL AFTER `bonus_reward`, ADD COLUMN `bonus_end` TIME NULL AFTER `bonus_start`','SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
