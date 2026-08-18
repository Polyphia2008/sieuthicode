<?php
/** GET /model/chat/contacts?q= — admin/superadmin contacts for members. */
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}
$account = chat_require_login();
$q = trim((string) ($_GET['q'] ?? ''));
$where = "u.`level` IN ('admin','superadmin') AND u.`banned` = 0";
if ($q !== '') {
    $where .= " AND (u.`username` LIKE '%" . $db->escape($q) . "%'"
        . " OR u.`name` LIKE '%" . $db->escape($q) . "%')";
}
$rows = $db->get_list(
    "SELECT u.`id`,u.`username`,u.`name`,u.`level`,p.`last_seen`,"
    . " c.`id` AS conversation_id,c.`status`,c.`unread_user`,c.`last_message_at`,"
    . " m.`message` AS last_message,m.`sender_role` AS last_sender_role,m.`attachment_type`"
    . " FROM `users` u LEFT JOIN `chat_presence` p ON p.`user_id`=u.`id`"
    . " LEFT JOIN `chat_conversations` c ON c.`user_id`='" . (int) $account['id'] . "' AND c.`admin_id`=u.`id`"
    . " LEFT JOIN `chat_messages` m ON m.`id`=c.`last_message_id`"
    . " WHERE " . $where
    . " ORDER BY (c.`last_message_at` IS NOT NULL) DESC,c.`last_message_at` DESC,"
    . " (u.`level`='superadmin') DESC,u.`username` ASC LIMIT 100"
);
$contacts = [];
foreach ($rows as $row) {
    $preview = trim((string) ($row['last_message'] ?? ''));
    if ($preview === '' && !empty($row['attachment_type'])) {
        $preview = '[Hình ảnh]';
    }
    if ($preview === '') {
        $preview = 'Bắt đầu cuộc trò chuyện';
    } elseif ((string) ($row['last_sender_role'] ?? '') === 'admin') {
        $preview = 'Support: ' . $preview;
    }
    if (function_exists('mb_strlen') && mb_strlen($preview, 'UTF-8') > 75) {
        $preview = mb_substr($preview, 0, 75, 'UTF-8') . '…';
    }
    $contacts[] = [
        'id' => (int) $row['id'],
        'username' => (string) $row['username'],
        'display_name' => trim((string) ($row['name'] ?? '')) ?: (string) $row['username'],
        'level' => (string) $row['level'],
        'online' => chat_is_online($row['last_seen'] ?? null),
        'conversation_id' => (int) ($row['conversation_id'] ?? 0),
        'status' => (string) ($row['status'] ?? 'open'),
        'unread' => (int) ($row['unread_user'] ?? 0),
        'preview' => $preview,
        'last_message_at' => $row['last_message_at'],
        'time_label' => !empty($row['last_message_at'])
            ? date('H:i', strtotime((string) $row['last_message_at'])) : '',
    ];
}
chat_json('success', 'OK', ['contacts' => $contacts]);
