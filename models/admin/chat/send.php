<?php
/**
 * POST /model/admin/chat/send
 * Admin trả lời một hội thoại. Bắt buộc: level=admin + CSRF + rate limit.
 * sender_role luôn 'admin' phía server — không tin input từ client.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_admin();
chat_require_csrf();
chat_rate_limit_send($account['id'], 'admin');

$conversation = chat_get_conversation_for((int) ($_POST['conversation_id'] ?? 0), $account);
if ($conversation['status'] === 'closed') {
    chat_json('error', 'Hội thoại đã đóng. Hãy mở lại hội thoại trước khi trả lời.', [
        'conversation_status' => 'closed',
    ], 423);
}

$attachment = chat_handle_attachment('attachment');
$message = chat_validate_message($_POST['message'] ?? '', $attachment !== null);

$messageId = chat_insert_message(
    $conversation,
    $account['id'],
    'admin',
    $message,
    $attachment['path'] ?? null,
    $attachment['type'] ?? null
);

$row = $db->get_row('SELECT * FROM `chat_messages` WHERE `id` = ' . (int) $messageId . ' LIMIT 1');
chat_json('success', 'Đã gửi phản hồi', [
    'message' => $row ? chat_format_message($row) : null,
]);
