<?php
/**
 * Chat Box (hỗ trợ khách hàng) — core helpers.
 *
 * Độc lập, không tích hợp dịch vụ bên ngoài. Dùng chung cho
 * endpoint thành viên (models/client/chat/*) và quản trị (models/admin/chat/*).
 */

if (!defined('CHAT_MAX_MESSAGE_LENGTH')) {
    define('CHAT_MAX_MESSAGE_LENGTH', 3000);
}
if (!defined('CHAT_RATE_LIMIT_MAX')) {
    define('CHAT_RATE_LIMIT_MAX', 15); // số tin nhắn tối đa
}
if (!defined('CHAT_RATE_LIMIT_WINDOW')) {
    define('CHAT_RATE_LIMIT_WINDOW', 60); // mỗi 60 giây
}
if (!defined('CHAT_UPLOAD_MAX_BYTES')) {
    define('CHAT_UPLOAD_MAX_BYTES', 5 * 1024 * 1024); // 5MB
}

/**
 * Trả JSON nhất quán rồi dừng request.
 */
function chat_json($status, $msg, $data = null, $httpCode = 200)
{
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['status' => $status, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Bắt buộc đăng nhập cho API. Trả về mảng user đang đăng nhập.
 */
function chat_require_login()
{
    global $user, $data_user;
    if (!$user || empty($data_user) || empty($data_user['id'])) {
        chat_json('error', 'Vui lòng đăng nhập để sử dụng chat hỗ trợ', null, 401);
    }
    return $data_user;
}

/**
 * Bắt buộc quyền admin cho API.
 */
function chat_require_admin()
{
    $account = chat_require_login();
    if (($account['level'] ?? 'member') !== 'admin') {
        chat_json('error', 'Bạn không có quyền truy cập chức năng này', null, 403);
    }
    return $account;
}

/**
 * Kiểm tra CSRF token cho mọi request POST (hash_equals chống timing).
 */
function chat_require_csrf()
{
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postedToken = $_POST['csrf_token'] ?? '';
    if ($sessionToken === '' || $postedToken === '' || !hash_equals((string) $sessionToken, (string) $postedToken)) {
        chat_json('error', 'Phiên làm việc không hợp lệ, vui lòng tải lại trang', null, 419);
    }
}

/**
 * Lấy (hoặc tạo mới) hội thoại của một thành viên.
 * Một thành viên chỉ có 1 hội thoại hỗ trợ (UNIQUE user_id).
 */
function chat_get_or_create_conversation($userId)
{
    global $db;
    $userId = (int) $userId;
    $conversation = $db->get_row('SELECT * FROM `chat_conversations` WHERE `user_id` = ' . $userId . ' LIMIT 1');
    if ($conversation) {
        return $conversation;
    }
    $db->insert('chat_conversations', [
        'user_id' => $userId,
        'status' => 'open',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    $conversation = $db->get_row('SELECT * FROM `chat_conversations` WHERE `user_id` = ' . $userId . ' LIMIT 1');
    if (!$conversation) {
        chat_json('error', 'Không thể khởi tạo hội thoại, vui lòng thử lại', null, 500);
    }
    return $conversation;
}

/**
 * Lấy hội thoại theo id kèm kiểm tra quyền: admin xem tất cả,
 * thành viên chỉ xem hội thoại của chính mình.
 */
function chat_get_conversation_for($conversationId, $account)
{
    global $db;
    $conversationId = (int) $conversationId;
    if ($conversationId <= 0) {
        chat_json('error', 'Hội thoại không hợp lệ', null, 400);
    }
    $conversation = $db->get_row('SELECT * FROM `chat_conversations` WHERE `id` = ' . $conversationId . ' LIMIT 1');
    if (!$conversation) {
        chat_json('error', 'Hội thoại không tồn tại', null, 404);
    }
    $isAdmin = ($account['level'] ?? 'member') === 'admin';
    if (!$isAdmin && (int) $conversation['user_id'] !== (int) $account['id']) {
        chat_json('error', 'Bạn không có quyền truy cập hội thoại này', null, 403);
    }
    return $conversation;
}

/**
 * Rate limit gửi tin theo user + endpoint (lưu session, phù hợp shared hosting).
 */
function chat_rate_limit_send($userId, $scope = 'user')
{
    $key = 'chat_rate_' . $scope;
    $now = time();
    $bucket = $_SESSION[$key] ?? ['start' => $now, 'count' => 0, 'uid' => (int) $userId];
    if ((int) ($bucket['uid'] ?? 0) !== (int) $userId || $now - (int) $bucket['start'] >= CHAT_RATE_LIMIT_WINDOW) {
        $bucket = ['start' => $now, 'count' => 0, 'uid' => (int) $userId];
    }
    $bucket['count']++;
    $_SESSION[$key] = $bucket;
    if ($bucket['count'] > CHAT_RATE_LIMIT_MAX) {
        chat_json('error', 'Bạn gửi tin nhắn quá nhanh, vui lòng thử lại sau ít phút', null, 429);
    }
}

/**
 * Validate nội dung tin nhắn. Không dùng Anti_xss (sẽ phá ký tự hợp lệ);
 * chống XSS bằng escape ở tầng render (textContent phía client).
 * Trả về nội dung đã trim hoặc chuỗi rỗng nếu không hợp lệ kèm lỗi.
 */
function chat_validate_message($raw, $hasAttachment)
{
    $message = trim((string) $raw);
    $message = str_replace("\0", '', $message);
    if ($message === '' && !$hasAttachment) {
        chat_json('error', 'Vui lòng nhập nội dung tin nhắn', null, 422);
    }
    if (function_exists('mb_strlen')) {
        $length = mb_strlen($message, 'UTF-8');
    } else {
        $length = strlen($message);
    }
    if ($length > CHAT_MAX_MESSAGE_LENGTH) {
        chat_json('error', 'Tin nhắn quá dài (tối đa ' . CHAT_MAX_MESSAGE_LENGTH . ' ký tự)', null, 422);
    }
    return $message;
}

/**
 * Upload ảnh đính kèm cho chat: kiểm tra extension strict, is_uploaded_file,
 * finfo + getimagesize, MIME whitelist, tên file ngẫu nhiên.
 * Trả về ['path' => 'upload/chat/xxx.png', 'type' => 'image'] hoặc null nếu không có file.
 */
function chat_handle_attachment($field)
{
    if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) {
        return null;
    }
    $file = $_FILES[$field];
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($error !== UPLOAD_ERR_OK) {
        chat_json('error', 'Tải ảnh thất bại (mã lỗi ' . $error . ')', null, 422);
    }
    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > CHAT_UPLOAD_MAX_BYTES) {
        chat_json('error', 'Ảnh đính kèm tối đa ' . (int) (CHAT_UPLOAD_MAX_BYTES / 1048576) . 'MB', null, 422);
    }

    $original = (string) ($file['name'] ?? '');
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $allowedExt = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
    if (!isset($allowedExt[$ext])) {
        chat_json('error', 'Chỉ hỗ trợ ảnh JPG, JPEG, PNG, GIF, WEBP', null, 422);
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        chat_json('error', 'File tải lên không hợp lệ', null, 422);
    }

    // Kiểm tra nội dung thật của file (chặn PHP đổi đuôi).
    $imageInfo = @getimagesize($tmp);
    if ($imageInfo === false) {
        chat_json('error', 'File không phải hình ảnh hợp lệ', null, 422);
    }
    $finfoMime = '';
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $finfoMime = (string) finfo_file($finfo, $tmp);
            finfo_close($finfo);
        }
    }
    $mime = (string) ($imageInfo['mime'] ?? '');
    $allowedMime = array_values($allowedExt);
    if (!in_array($mime, $allowedMime, true)) {
        chat_json('error', 'Định dạng ảnh không được hỗ trợ', null, 422);
    }
    if ($finfoMime !== '' && !in_array($finfoMime, $allowedMime, true)) {
        chat_json('error', 'Nội dung file không khớp định dạng ảnh', null, 422);
    }
    if ($mime !== $allowedExt[$ext]) {
        chat_json('error', 'Phần mở rộng không khớp nội dung ảnh', null, 422);
    }

    $dir = APP_ROOT . '/upload/chat';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        chat_json('error', 'Không thể tạo thư mục lưu ảnh', null, 500);
    }
    $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($tmp, $dir . '/' . $fileName)) {
        chat_json('error', 'Không thể lưu ảnh, vui lòng thử lại', null, 500);
    }
    @chmod($dir . '/' . $fileName, 0644);
    return ['path' => 'upload/chat/' . $fileName, 'type' => 'image'];
}

/**
 * Ghi một tin nhắn vào hội thoại + cập nhật bộ đếm unread/last_message.
 * $senderRole: 'user' | 'admin'
 */
function chat_insert_message($conversation, $senderId, $senderRole, $message, $attachment = null, $attachmentType = null)
{
    global $db;
    $conversationId = (int) $conversation['id'];
    $now = date('Y-m-d H:i:s');
    $ok = $db->insert('chat_messages', [
        'conversation_id' => $conversationId,
        'sender_id' => (int) $senderId,
        'sender_role' => $senderRole === 'admin' ? 'admin' : 'user',
        'message' => $message,
        'attachment' => $attachment,
        'attachment_type' => $attachmentType,
        'is_read' => 0,
        'created_at' => $now,
    ]);
    if (!$ok) {
        chat_json('error', 'Không thể gửi tin nhắn, vui lòng thử lại', null, 500);
    }
    $messageIdRow = $db->get_row('SELECT LAST_INSERT_ID() AS id');
    $messageId = (int) ($messageIdRow['id'] ?? 0);

    if ($senderRole === 'admin') {
        $db->query('UPDATE `chat_conversations` SET `last_message_id` = ' . $messageId
            . ', `last_message_at` = \'' . $now . '\', `unread_user` = `unread_user` + 1, `updated_at` = \'' . $now
            . '\' WHERE `id` = ' . $conversationId);
    } else {
        $db->query('UPDATE `chat_conversations` SET `last_message_id` = ' . $messageId
            . ', `last_message_at` = \'' . $now . '\', `unread_admin` = `unread_admin` + 1, `updated_at` = \'' . $now
            . '\' WHERE `id` = ' . $conversationId);
    }
    return $messageId;
}

/**
 * Chuẩn hoá 1 bản ghi tin nhắn để trả về client.
 * message trả raw text — client render bằng textContent (chống stored XSS).
 */
function chat_format_message($row)
{
    return [
        'id' => (int) $row['id'],
        'conversation_id' => (int) $row['conversation_id'],
        'sender_role' => $row['sender_role'],
        'message' => (string) ($row['message'] ?? ''),
        'attachment' => $row['attachment'] ? '/' . ltrim((string) $row['attachment'], '/') : null,
        'attachment_type' => $row['attachment_type'],
        'created_at' => $row['created_at'],
        'time_label' => date('H:i', strtotime((string) $row['created_at'])),
        'date_label' => date('d/m/Y', strtotime((string) $row['created_at'])),
    ];
}
