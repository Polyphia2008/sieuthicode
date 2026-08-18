<?php
/**
 * POST /model/chat/reopen
 * Thành viên mở lại hội thoại hỗ trợ của chính mình sau khi admin đã đóng.
 * (Theo thiết kế: thread 1-1 vĩnh viễn, user được phép yêu cầu mở lại.)
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
chat_require_csrf();

$adminId = (int) ($_POST['admin_id'] ?? 0);
$conversation = chat_get_or_create_conversation($account['id'], $adminId);
if ($conversation['status'] === 'open') {
    chat_json('success', 'Hội thoại đang mở', ['conversation_status' => 'open']);
}

$db->update('chat_conversations', ['status' => 'open'], '`id` = ' . (int) $conversation['id']);

// Ghi một tin nhắn hệ thống để admin biết user đã yêu cầu mở lại.
chat_insert_message(
    array_merge($conversation, ['status' => 'open']),
    $account['id'],
    'user',
    '[Hệ thống] Khách hàng đã mở lại hội thoại hỗ trợ.',
    null,
    null
);

chat_json('success', 'Đã mở lại hội thoại, bạn có thể tiếp tục nhắn tin', ['conversation_status' => 'open']);
