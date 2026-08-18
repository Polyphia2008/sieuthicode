<?php
/**
 * POST /model/chat/read
 * Đánh dấu tất cả tin nhắn từ admin trong hội thoại của thành viên là đã đọc
 * (reset unread_user về 0 để badge header cập nhật).
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
chat_require_csrf();

$adminId = (int) ($_POST['admin_id'] ?? 0);
$conversation = chat_get_conversation_by_user((int) $account['id'], $adminId);
if (!$conversation) {
    chat_json('success', 'OK', ['unread' => 0]);
}

$conversationId = (int) $conversation['id'];
$db->query('UPDATE `chat_messages` SET `is_read` = 1 WHERE `conversation_id` = ' . $conversationId
    . ' AND `sender_role` = \'admin\' AND `is_read` = 0');
$db->query('UPDATE `chat_conversations` SET `unread_user` = 0 WHERE `id` = ' . $conversationId);

chat_json('success', 'OK', ['unread' => 0]);
