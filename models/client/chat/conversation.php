<?php
/**
 * GET /model/chat/conversation
 * Trả thông tin hội thoại hỗ trợ của thành viên đang đăng nhập kèm unread
 * count để cập nhật badge header. Endpoint này CHỈ ĐỌC — không tạo
 * conversation rỗng (tránh sinh hàng loạt hội thoại trống khi user lướt trang).
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
$summary = $db->get_row(
    "SELECT COUNT(*) AS `total`, COALESCE(SUM(`unread_user`),0) AS `unread`"
    . " FROM `chat_conversations` WHERE `user_id` = '" . (int) $account['id'] . "'"
);

chat_json('success', 'OK', [
    'conversation' => null,
    'total_conversations' => (int) ($summary['total'] ?? 0),
    'unread' => (int) ($summary['unread'] ?? 0),
]);
