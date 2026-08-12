<?php
/**
 * POST /model/admin/chat/status
 * Admin đóng hoặc mở lại một hội thoại.
 * Body: conversation_id, status = open|closed
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_admin();
chat_require_csrf();

$conversation = chat_get_conversation_for((int) ($_POST['conversation_id'] ?? 0), $account);
$newStatus = strtolower(trim((string) ($_POST['status'] ?? '')));
if (!in_array($newStatus, ['open', 'closed'], true)) {
    chat_json('error', 'Trạng thái không hợp lệ (open/closed)', null, 422);
}

if ($conversation['status'] !== $newStatus) {
    $db->update('chat_conversations', ['status' => $newStatus], '`id` = ' . (int) $conversation['id']);
    $systemNote = $newStatus === 'closed'
        ? '[Hệ thống] Hội thoại đã được quản trị viên đóng.'
        : '[Hệ thống] Hội thoại đã được quản trị viên mở lại.';
    chat_insert_message($conversation, $account['id'], 'admin', $systemNote, null, null);
}

chat_json('success', $newStatus === 'closed' ? 'Đã đóng hội thoại' : 'Đã mở lại hội thoại', [
    'conversation_status' => $newStatus,
]);
