<?php
/**
 * GET /model/chat/attachment?message_id={id}
 * Stream ảnh đính kèm của một tin nhắn sau khi kiểm tra quyền:
 *  - Bắt buộc đăng nhập.
 *  - Member chỉ xem attachment thuộc conversation của chính mình.
 *  - Admin xem tất cả.
 * Không nhận path từ request; path luôn lấy từ DB và kiểm tra realpath
 * nằm trong thư mục storage chat (chống path traversal).
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';

function chat_attachment_fail($code, $msg)
{
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');
    exit($msg);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    chat_attachment_fail(405, 'Method Not Allowed');
}

// 1. Bắt buộc đăng nhập (không dùng chat_json để tránh trả JSON cho <img>).
global $user, $data_user;
if (!$user || empty($data_user) || empty($data_user['id'])) {
    chat_attachment_fail(401, 'Unauthorized');
}

$messageId = (int) ($_GET['message_id'] ?? 0);
if ($messageId <= 0) {
    chat_attachment_fail(400, 'Bad Request');
}

// 2. Lấy message + conversation từ DB.
$message = $db->get_row('SELECT * FROM `chat_messages` WHERE `id` = ' . $messageId . ' LIMIT 1');
if (!$message || empty($message['attachment'])) {
    chat_attachment_fail(404, 'Not Found');
}
$conversation = $db->get_row('SELECT * FROM `chat_conversations` WHERE `id` = ' . (int) $message['conversation_id'] . ' LIMIT 1');
if (!$conversation) {
    chat_attachment_fail(404, 'Not Found');
}

// 3. Quyền: admin/superadmin xem tất cả; member chỉ xem conversation của mình.
$isAdmin = is_admin_account($data_user);
if (!$isAdmin && (int) $conversation['user_id'] !== (int) $data_user['id']) {
    chat_attachment_fail(403, 'Forbidden');
}

// 4. Resolve path từ DB + chống path traversal bằng realpath.
$relative = (string) $message['attachment']; // dạng: upload/chat/<random>.<ext>
$basename = basename($relative);
// Chỉ chấp nhận tên file hex + extension ảnh hợp lệ do hệ thống tự sinh.
if (!preg_match('/^[a-f0-9]{32}\.(jpg|jpeg|png|gif|webp)$/', $basename)) {
    chat_attachment_fail(404, 'Not Found');
}
$storageDir = realpath(APP_ROOT . '/upload/chat');
$filePath = realpath(APP_ROOT . '/upload/chat/' . $basename);
if ($storageDir === false || $filePath === false || strpos($filePath, $storageDir) !== 0 || !is_file($filePath)) {
    chat_attachment_fail(404, 'Not Found');
}

// 5. MIME thực tế của file.
$mime = '';
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $mime = (string) finfo_file($finfo, $filePath);
        finfo_close($finfo);
    }
}
$allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mime, $allowedMime, true)) {
    chat_attachment_fail(404, 'Not Found');
}

// 6. Stream file an toàn.
$size = filesize($filePath);
header('Content-Type: ' . $mime);
header('Content-Length: ' . $size);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=3600');
header('Content-Disposition: inline; filename="chat-image.' . pathinfo($basename, PATHINFO_EXTENSION) . '"');

// Dọn output buffer để không làm hỏng binary.
while (ob_get_level() > 0) {
    ob_end_clean();
}
readfile($filePath);
exit;
