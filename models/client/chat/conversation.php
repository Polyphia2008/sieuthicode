<?php
/**
 * GET /model/chat/conversation
 * Trả thông tin hội thoại hỗ trợ của thành viên đang đăng nhập
 * (tạo mới nếu chưa có) kèm unread count để cập nhật badge header.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
$conversation = chat_get_or_create_conversation($account['id']);

chat_json('success', 'OK', [
    'conversation' => [
        'id' => (int) $conversation['id'],
        'status' => $conversation['status'],
        'last_message_at' => $conversation['last_message_at'],
        'unread' => (int) $conversation['unread_user'],
    ],
]);
