<?php
/**
 * POST /model/chat/delete
 * Thành viên xóa vĩnh viễn hội thoại và toàn bộ tin nhắn của chính mình.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$account = chat_require_login();
chat_require_csrf();
$conversation = chat_get_conversation_by_user((int) $account['id']);
if (!$conversation) {
    chat_json('success', 'Lịch sử trò chuyện đã trống', ['deleted' => 0]);
}

$conversationId = (int) $conversation['id'];
$attachments = $db->get_list(
    "SELECT `attachment` FROM `chat_messages` WHERE `conversation_id` = '" . $conversationId
    . "' AND `attachment` IS NOT NULL AND `attachment` <> ''"
);

$db->query('START TRANSACTION');
$messagesDeleted = $db->query(
    "DELETE FROM `chat_messages` WHERE `conversation_id` = '" . $conversationId . "'"
);
$deleted = $db->remove(
    'chat_conversations',
    "`id` = '" . $conversationId . "' AND `user_id` = '" . (int) $account['id'] . "'"
);
if ($messagesDeleted === false || !$deleted || $db->affected_rows() !== 1) {
    $db->query('ROLLBACK');
    chat_json('error', 'Không thể xóa lịch sử trò chuyện', null, 500);
}
if ($db->query('COMMIT') === false) {
    $db->query('ROLLBACK');
    chat_json('error', 'Không thể hoàn tất xóa lịch sử trò chuyện', null, 500);
}

// Remove physical attachment files only after the database transaction is committed.
$chatDir = realpath(APP_ROOT . '/upload/chat');
if ($chatDir !== false) {
    foreach ($attachments as $attachmentRow) {
        $relative = (string) ($attachmentRow['attachment'] ?? '');
        if (strpos($relative, 'upload/chat/') !== 0) {
            continue;
        }
        $candidate = APP_ROOT . '/' . $relative;
        $candidateDir = realpath(dirname($candidate));
        if ($candidateDir === $chatDir && is_file($candidate)) {
            @unlink($candidate);
        }
    }
}

chat_json('success', 'Đã xóa lịch sử trò chuyện', ['deleted' => 1]);
