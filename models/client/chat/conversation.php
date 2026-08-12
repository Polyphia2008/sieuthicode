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
$conversation = chat_get_conversation_by_user($account['id']);

if (!$conversation) {
    chat_json('success', 'OK', [
        'conversation' => null,
        'unread' => 0,
    ]);
}

chat_json('success', 'OK', [
    'conversation' => [
        'id' => (int) $conversation['id'],
        'status' => $conversation['status'],
        'last_message_at' => $conversation['last_message_at'],
        'unread' => (int) $conversation['unread_user'],
    ],
    'unread' => (int) $conversation['unread_user'],
]);
