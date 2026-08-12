<?php
/**
 * GET /model/admin/chat/messages?conversation_id=&after_id=
 * Admin xem tin nhắn của một hội thoại bất kỳ (polling giống phía user).
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_admin();
$conversation = chat_get_conversation_for((int) ($_GET['conversation_id'] ?? 0), $account);
$conversationId = (int) $conversation['id'];

$afterId = isset($_GET['after_id']) ? (int) $_GET['after_id'] : 0;
$messages = [];

if ($afterId > 0) {
    $rows = $db->get_list('SELECT * FROM `chat_messages` WHERE `conversation_id` = ' . $conversationId
        . ' AND `id` > ' . $afterId . ' ORDER BY `id` ASC LIMIT 200');
} else {
    $rows = $db->get_list('SELECT * FROM (SELECT * FROM `chat_messages` WHERE `conversation_id` = ' . $conversationId
        . ' ORDER BY `id` DESC LIMIT 100) AS recent ORDER BY `id` ASC');
}
foreach ($rows as $row) {
    $messages[] = chat_format_message($row);
}

$owner = $db->get_row('SELECT `id`, `username`, `name`, `email` FROM `users` WHERE `id` = ' . (int) $conversation['user_id'] . ' LIMIT 1');

chat_json('success', 'OK', [
    'messages' => $messages,
    'conversation' => [
        'id' => $conversationId,
        'status' => $conversation['status'],
        'unread_admin' => (int) $conversation['unread_admin'],
    ],
    'owner' => [
        'id' => (int) ($owner['id'] ?? 0),
        'username' => (string) ($owner['username'] ?? ''),
        'name' => (string) ($owner['name'] ?? ''),
        'email' => (string) ($owner['email'] ?? ''),
    ],
]);
