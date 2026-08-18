<?php
/**
 * GET /model/admin/chat/conversations?status=&q=&page=
 * Danh sách hội thoại hỗ trợ cho admin: username, preview tin cuối,
 * thời gian, unread, trạng thái + tìm kiếm username + phân trang.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_json('error', 'Phương thức không được hỗ trợ', null, 405);
}

$adminAccount = chat_require_admin();

$status = isset($_GET['status']) ? strtolower(trim((string) $_GET['status'])) : '';
if (!in_array($status, ['open', 'closed'], true)) {
    $status = '';
}
$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
if (function_exists('mb_strlen') && mb_strlen($q, 'UTF-8') > 100) {
    $q = mb_substr($q, 0, 100, 'UTF-8');
}
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$where = ' WHERE 1=1';
if (!is_superadmin_account($adminAccount)) {
    $where .= " AND c.`admin_id` = '" . (int) $adminAccount['id'] . "'";
}
if ($status !== '') {
    $where .= ' AND c.`status` = \'' . $status . '\'';
}
if ($q !== '') {
    $where .= ' AND u.`username` LIKE \'%' . $db->escape($q) . '%\'';
}

$baseJoin = ' FROM `chat_conversations` c LEFT JOIN `users` u ON u.`id` = c.`user_id`';
$total = (int) ($db->get_row('SELECT COUNT(c.id) AS total' . $baseJoin . $where)['total'] ?? 0);

$rows = $db->get_list(
    'SELECT c.*, u.`username` AS username, m.`message` AS last_message, m.`sender_role` AS last_sender_role, m.`attachment_type` AS last_attachment_type'
    . $baseJoin
    . ' LEFT JOIN `chat_messages` m ON m.`id` = c.`last_message_id`'
    . $where
    . ' ORDER BY (c.`unread_admin` > 0) DESC, c.`last_message_at` DESC, c.`id` DESC'
    . ' LIMIT ' . $perPage . ' OFFSET ' . $offset
);

$items = [];
foreach ($rows as $row) {
    $preview = (string) ($row['last_message'] ?? '');
    if ($preview === '' && !empty($row['last_attachment_type'])) {
        $preview = '[Hình ảnh]';
    }
    if (function_exists('mb_strlen') && mb_strlen($preview, 'UTF-8') > 60) {
        $preview = mb_substr($preview, 0, 60, 'UTF-8') . '…';
    }
    $items[] = [
        'id' => (int) $row['id'],
        'user_id' => (int) $row['user_id'],
        'username' => (string) ($row['username'] ?? ('#' . (int) $row['user_id'])),
        'status' => $row['status'],
        'unread_admin' => (int) $row['unread_admin'],
        'last_message' => $preview,
        'last_sender_role' => $row['last_sender_role'],
        'last_message_at' => $row['last_message_at'],
        'last_message_label' => $row['last_message_at'] ? date('H:i d/m/Y', strtotime((string) $row['last_message_at'])) : '',
    ];
}

$unreadWhere = !is_superadmin_account($adminAccount)
    ? " WHERE `admin_id` = '" . (int) $adminAccount['id'] . "'"
    : '';
$totalUnread = (int) ($db->get_row(
    'SELECT COALESCE(SUM(`unread_admin`), 0) AS total FROM `chat_conversations`' . $unreadWhere
)['total'] ?? 0);

chat_json('success', 'OK', [
    'conversations' => $items,
    'pagination' => [
        'page' => $page,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => (int) ceil($total / $perPage),
    ],
    'total_unread' => $totalUnread,
]);
