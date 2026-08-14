-- Migration: role superadmin (2026-08-14)
--
-- Idempotent: UPDATE ... WHERE chỉ tác động khi điều kiện khớp; chạy lại nhiều
-- lần không lỗi, không đổi thêm dữ liệu. KHÔNG chứa credential nào.
--
-- Quy tắc:
--  - Chỉ promote khi hệ thống CHƯA có superadmin nào (kể cả đang bị ban).
--  - Chỉ promote tài khoản admin hợp lệ có id nhỏ nhất (banned = 0, username
--    và password thật — loại trừ bootstrap placeholder password '!').
--  - Tuyệt đối không promote tài khoản level 'member'/'ctv'.

UPDATE `users`
SET `level` = 'superadmin'
WHERE `id` = (
    SELECT `id` FROM (
        SELECT MIN(`id`) AS `id` FROM `users`
        WHERE `level` = 'admin' AND `banned` = 0
          AND `username` <> '' AND `password` <> '' AND `password` <> '!'
    ) AS `candidate`
)
AND NOT EXISTS (
    SELECT 1 FROM (
        SELECT `id` FROM `users` WHERE `level` = 'superadmin' LIMIT 1
    ) AS `existing`
);

-- Migration: persistent login 2/7 ngày (Batch 3, 2026-08-14)
-- Bổ sung index tra cứu cho bảng `auth_tokens` (token lưu SHA-256 hash 64 hex).
-- Idempotent (MariaDB 10.6): kiểm tra information_schema.STATISTICS trước khi
-- ALTER; chạy lại nhiều lần không lỗi, không đổi dữ liệu, không credential.

SET @stc_idx := (
    SELECT COUNT(1) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'auth_tokens' AND INDEX_NAME = 'idx_token'
);
SET @stc_sql := IF(@stc_idx = 0,
    'ALTER TABLE `auth_tokens` ADD INDEX `idx_token` (`token`)',
    'SELECT 1'
);
PREPARE stc_stmt FROM @stc_sql;
EXECUTE stc_stmt;
DEALLOCATE PREPARE stc_stmt;

SET @stc_idx := (
    SELECT COUNT(1) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'auth_tokens' AND INDEX_NAME = 'idx_user_id'
);
SET @stc_sql := IF(@stc_idx = 0,
    'ALTER TABLE `auth_tokens` ADD INDEX `idx_user_id` (`user_id`)',
    'SELECT 1'
);
PREPARE stc_stmt FROM @stc_sql;
EXECUTE stc_stmt;
DEALLOCATE PREPARE stc_stmt;

SET @stc_idx := (
    SELECT COUNT(1) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'auth_tokens' AND INDEX_NAME = 'idx_expires_at'
);
SET @stc_sql := IF(@stc_idx = 0,
    'ALTER TABLE `auth_tokens` ADD INDEX `idx_expires_at` (`expires_at`)',
    'SELECT 1'
);
PREPARE stc_stmt FROM @stc_sql;
EXECUTE stc_stmt;
DEALLOCATE PREPARE stc_stmt;

-- Dọn token đã hết hạn tồn tại trước migration (idempotent, an toàn chạy lại).
DELETE FROM `auth_tokens` WHERE `expires_at` < NOW();
