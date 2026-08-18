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
if (!defined('CHAT_SEND_COOLDOWN_SECONDS')) {
    define('CHAT_SEND_COOLDOWN_SECONDS', 2); // khoảng nghỉ tối thiểu giữa 2 tin
}
if (!defined('CHAT_UPLOAD_MAX_BYTES')) {
    define('CHAT_UPLOAD_MAX_BYTES', 5 * 1024 * 1024); // 5MB
}

/**
 * Trả JSON nhất quán rồi dừng request.
 */
function chat_json($status, $msg, $data = null, $httpCode = 200)
{
    $httpCode = (int) $httpCode;
    // Some SAPIs (notably Apache/mod_php) downgrade an unknown status code set
    // via http_response_code() alone to 500 because it has no reason phrase.
    // Emit a full HTTP status line with a reason phrase so custom codes such as
    // 419/423/429 are preserved on the wire instead of turning into 500.
    $reasons = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        408 => 'Request Timeout',
        419 => 'Authentication Timeout',
        422 => 'Unprocessable Content',
        423 => 'Locked',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
    ];
    $reason = $reasons[$httpCode] ?? 'OK';
    header(sprintf('HTTP/1.1 %d %s', $httpCode, $reason), true, $httpCode);
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
    chat_presence_touch((int) $data_user['id']);
    return $data_user;
}

/**
 * Bắt buộc quyền admin cho API.
 */
function chat_require_admin()
{
    $account = chat_require_login();
    if (!is_admin_account($account)) {
        chat_json('error', 'Bạn không có quyền truy cập chức năng này', null, 403);
    }
    return $account;
}

function chat_presence_touch($userId)
{
    global $db;
    $userId = (int) $userId;
    if ($userId <= 0) {
        return;
    }
    $now = date('Y-m-d H:i:s');
    $db->query(
        "INSERT INTO `chat_presence` (`user_id`,`last_seen`) VALUES ('" . $userId . "','" . $now . "')"
        . " ON DUPLICATE KEY UPDATE `last_seen` = VALUES(`last_seen`)"
    );
}

function chat_is_online($lastSeen, $seconds = 120)
{
    $timestamp = strtotime((string) $lastSeen);
    return $timestamp > 0 && time() - $timestamp <= (int) $seconds;
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
 * Chỉ đọc hội thoại của một thành viên — KHÔNG tạo mới.
 * Dùng cho các endpoint GET/polling để tránh tạo conversation rỗng.
 */
function chat_get_conversation_by_user($userId, $adminId = 0)
{
    global $db;
    $userId = (int) $userId;
    $adminId = (int) $adminId;
    $whereAdmin = $adminId > 0 ? " AND `admin_id` = '" . $adminId . "'" : '';
    return $db->get_row(
        "SELECT * FROM `chat_conversations` WHERE `user_id` = '" . $userId . "'"
        . $whereAdmin . ' ORDER BY `last_message_at` DESC, `id` DESC LIMIT 1'
    );
}

/**
 * Lấy (hoặc tạo mới) hội thoại của một thành viên.
 * Một thành viên chỉ có 1 hội thoại hỗ trợ (UNIQUE user_id).
 * Chịu được race condition: nếu 2 request tạo đồng thời, request thua sẽ
 * bắt duplicate key rồi đọc lại conversation hiện có thay vì trả 500.
 */
function chat_get_or_create_conversation($userId, $adminId)
{
    global $db;
    $userId = (int) $userId;
    $adminId = (int) $adminId;
    if ($adminId <= 0) {
        chat_json('error', 'Vui lòng chọn quản trị viên để trò chuyện', null, 422);
    }
    $admin = $db->get_row(
        "SELECT `id` FROM `users` WHERE `id` = '" . $adminId . "'"
        . " AND `level` IN ('admin','superadmin') AND `banned` = 0 LIMIT 1"
    );
    if (!$admin) {
        chat_json('error', 'Quản trị viên không tồn tại hoặc đã ngừng hoạt động', null, 404);
    }
    $conversation = chat_get_conversation_by_user($userId, $adminId);
    if ($conversation) {
        return $conversation;
    }
    $db->insert('chat_conversations', [
        'user_id' => $userId,
        'admin_id' => $adminId,
        'status' => 'open',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    $conversation = chat_get_conversation_by_user($userId, $adminId);
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
    $isAdmin = is_admin_account($account);
    if (!$isAdmin && (int) $conversation['user_id'] !== (int) $account['id']) {
        chat_json('error', 'Bạn không có quyền truy cập hội thoại này', null, 403);
    }
    if ($isAdmin && !is_superadmin_account($account)
        && (int) ($conversation['admin_id'] ?? 0) !== (int) $account['id']) {
        chat_json('error', 'Hội thoại này được gửi tới quản trị viên khác', null, 403);
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
 * Bắt buộc khoảng nghỉ tối thiểu giữa hai tin nhắn của cùng người gửi.
 * Kiểm tra DB nên không thể bypass chỉ bằng cách mở tab/session mới.
 */
function chat_enforce_send_cooldown($conversationId, $senderId, $senderRole)
{
    global $db;
    $conversationId = (int) $conversationId;
    $senderId = (int) $senderId;
    $role = $senderRole === 'admin' ? 'admin' : 'user';
    $latest = $db->get_row(
        "SELECT `created_at` FROM `chat_messages` WHERE `conversation_id` = '" . $conversationId . "'"
        . " AND `sender_id` = '" . $senderId . "' AND `sender_role` = '" . $role . "'"
        . " ORDER BY `id` DESC LIMIT 1"
    );
    if (!$latest || empty($latest['created_at'])) {
        return;
    }
    $elapsed = time() - (int) strtotime((string) $latest['created_at']);
    if ($elapsed < CHAT_SEND_COOLDOWN_SECONDS) {
        $retryAfter = max(1, CHAT_SEND_COOLDOWN_SECONDS - $elapsed);
        chat_json(
            'error',
            'Vui lòng chờ ' . $retryAfter . ' giây trước khi gửi tin tiếp theo',
            ['retry_after' => $retryAfter],
            429
        );
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
 * Lưu ý: path này KHÔNG được expose public — client chỉ nhận URL qua
 * endpoint bảo vệ /model/chat/attachment?message_id=...
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
    $hasAttachment = !empty($row['attachment']);
    return [
        'id' => (int) $row['id'],
        'conversation_id' => (int) $row['conversation_id'],
        'sender_role' => $row['sender_role'],
        'message' => (string) ($row['message'] ?? ''),
        // Không trả filesystem path — client tải qua endpoint bảo vệ theo message_id.
        'attachment' => $hasAttachment ? '/model/chat/attachment?message_id=' . (int) $row['id'] : null,
        'attachment_type' => $row['attachment_type'],
        'is_read' => (int) ($row['is_read'] ?? 0) === 1,
        'created_at' => $row['created_at'],
        'time_label' => date('H:i', strtotime((string) $row['created_at'])),
        'date_label' => date('d/m/Y', strtotime((string) $row['created_at'])),
    ];
}
