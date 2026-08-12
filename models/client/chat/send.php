<?php
/**
 * POST /model/chat/send
 * Thành viên gửi tin nhắn (text và/hoặc ảnh đính kèm) vào hội thoại của chính mình.
 * Bắt buộc: đăng nhập + CSRF + rate limit. sender_role luôn là 'user' (server-side),
 * không tin user_id/conversation_id/sender_role từ client.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
chat_require_csrf();
chat_rate_limit_send($account['id'], 'user');

$conversation = chat_get_or_create_conversation($account['id']);
if ($conversation['status'] === 'closed') {
    chat_json('error', 'Hội thoại đã được đóng. Vui lòng bấm "Mở lại hội thoại" để tiếp tục nhắn tin.', [
        'conversation_status' => 'closed',
    ], 423);
}

$attachment = chat_handle_attachment('attachment');
$message = chat_validate_message($_POST['message'] ?? '', $attachment !== null);

$messageId = chat_insert_message(
    $conversation,
    $account['id'],
    'user',
    $message,
    $attachment['path'] ?? null,
    $attachment['type'] ?? null
);

$row = $db->get_row('SELECT * FROM `chat_messages` WHERE `id` = ' . (int) $messageId . ' LIMIT 1');
chat_json('success', 'Đã gửi tin nhắn', [
    'message' => $row ? chat_format_message($row) : null,
]);
