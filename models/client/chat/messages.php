<?php
/**
 * GET /model/chat/messages?after_id={id}
 * Lấy tin nhắn trong hội thoại của thành viên (polling).
 *  - Không truyền after_id: trả tối đa 100 tin mới nhất (thứ tự tăng dần).
 *  - Có after_id: chỉ trả tin có id > after_id (tối đa 200).
 * GET thuần tuý: không thay đổi dữ liệu (đánh dấu đã đọc dùng /model/chat/read).
 * CHỈ ĐỌC: không có conversation → trả messages=[] (không tạo conversation rỗng).
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
        'messages' => [],
        'conversation' => null,
    ]);
}

$conversationId = (int) $conversation['id'];
$afterId = isset($_GET['after_id']) ? (int) $_GET['after_id'] : 0;
$messages = [];

if ($afterId > 0) {
    $rows = $db->get_list('SELECT * FROM `chat_messages` WHERE `conversation_id` = ' . $conversationId
        . ' AND `id` > ' . $afterId . ' ORDER BY `id` ASC LIMIT 200');
    foreach ($rows as $row) {
        $messages[] = chat_format_message($row);
    }
} else {
    // Lấy 100 tin mới nhất rồi đảo lại thành thứ tự thời gian tăng dần.
    $rows = $db->get_list('SELECT * FROM (SELECT * FROM `chat_messages` WHERE `conversation_id` = ' . $conversationId
        . ' ORDER BY `id` DESC LIMIT 100) AS recent ORDER BY `id` ASC');
    foreach ($rows as $row) {
        $messages[] = chat_format_message($row);
    }
}

$readRow = $db->get_row(
    "SELECT MAX(`id`) AS `last_read_id` FROM `chat_messages` WHERE `conversation_id` = '"
    . $conversationId . "' AND `sender_role` = 'user' AND `is_read` = 1"
);

chat_json('success', 'OK', [
    'messages' => $messages,
    'conversation' => [
        'id' => $conversationId,
        'status' => $conversation['status'],
        'unread' => (int) $conversation['unread_user'],
        'last_read_user_message_id' => (int) ($readRow['last_read_id'] ?? 0),
    ],
]);
