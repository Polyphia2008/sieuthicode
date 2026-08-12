<?php
/**
 * POST /model/admin/chat/read
 * Admin đánh dấu đã đọc tin nhắn của user trong một hội thoại
 * (reset unread_admin của hội thoại đó về 0).
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_admin();
chat_require_csrf();

$conversation = chat_get_conversation_for((int) ($_POST['conversation_id'] ?? 0), $account);
$conversationId = (int) $conversation['id'];

$db->query('UPDATE `chat_messages` SET `is_read` = 1 WHERE `conversation_id` = ' . $conversationId
    . ' AND `sender_role` = \'user\' AND `is_read` = 0');
$db->query('UPDATE `chat_conversations` SET `unread_admin` = 0 WHERE `id` = ' . $conversationId);

chat_json('success', 'OK', ['unread_admin' => 0]);
