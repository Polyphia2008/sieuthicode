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
