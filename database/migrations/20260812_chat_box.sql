-- =============================================================
-- Chat Box (hỗ trợ khách hàng) — idempotent migration
-- Project: sieuthicode — 2026-08-12
-- An toàn khi chạy lại nhiều lần (IF NOT EXISTS / IF NOT EXISTS trên index).
-- Không chứa credentials hoặc dữ liệu demo.
-- =============================================================

CREATE TABLE IF NOT EXISTS `chat_conversations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL COMMENT 'ID thành viên sở hữu hội thoại (users.id)',
  `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
  `last_message_id` INT UNSIGNED DEFAULT NULL,
  `last_message_at` DATETIME DEFAULT NULL,
  `unread_user` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Số tin admin gửi mà user chưa đọc',
  `unread_admin` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Số tin user gửi mà admin chưa đọc',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_chat_conversations_user` (`user_id`),
  KEY `idx_chat_conversations_status` (`status`),
  KEY `idx_chat_conversations_last_msg` (`last_message_at`),
  KEY `idx_chat_conversations_unread_admin` (`unread_admin`),
  KEY `idx_chat_conversations_unread_user` (`unread_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` INT UNSIGNED NOT NULL,
  `sender_id` INT NOT NULL COMMENT 'users.id của người gửi (0 = hệ thống)',
  `sender_role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `message` TEXT DEFAULT NULL,
  `attachment` VARCHAR(255) DEFAULT NULL COMMENT 'Đường dẫn tương đối tới file trong upload/chat/',
  `attachment_type` VARCHAR(20) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chat_messages_conversation` (`conversation_id`),
  KEY `idx_chat_messages_sender` (`sender_id`),
  KEY `idx_chat_messages_created` (`created_at`),
  KEY `idx_chat_messages_conv_read` (`conversation_id`,`is_read`),
  CONSTRAINT `fk_chat_messages_conversation`
    FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
