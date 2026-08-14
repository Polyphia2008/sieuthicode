<?php
/**
 * Persistent-login ("remember me") helper — Batch 3.
 *
 * Mô hình bảo mật:
 *  - Cookie `remember_me` chỉ chứa token NGẪU NHIÊN (random_bytes >= 32, hex 64 ký tự).
 *  - Bảng `auth_tokens` chỉ lưu SHA-256 hash (hex 64 ký tự) của token — không bao giờ lưu raw.
 *  - Cookie: HttpOnly, Path=/, SameSite=Lax, Secure khi HTTPS (kể cả X-Forwarded-Proto).
 *  - Thời hạn tuyệt đối: 172800s (2 ngày) mặc định, 604800s (7 ngày) khi người dùng
 *    chọn "Lưu đăng nhập". Rotation KHÔNG được gia hạn vượt quá expires_at tuyệt đối ban đầu.
 *  - Tài khoản bị khoá (banned = 1) không bao giờ được restore phiên.
 *  - Không bind cứng IP — chỉ ghi lại IP để audit.
 */

if (!defined('AUTH_TOKEN_COOKIE')) {
    define('AUTH_TOKEN_COOKIE', 'remember_me');
}
if (!defined('AUTH_TOKEN_TTL_DEFAULT')) {
    define('AUTH_TOKEN_TTL_DEFAULT', 172800); // 2 ngày
}
if (!defined('AUTH_TOKEN_TTL_REMEMBER')) {
    define('AUTH_TOKEN_TTL_REMEMBER', 604800); // 7 ngày
}
if (!defined('AUTH_SESSION_MAX_TTL')) {
    define('AUTH_SESSION_MAX_TTL', 604800); // gc_maxlifetime tối thiểu 7 ngày
}

/**
 * HTTPS detection (kể cả sau reverse proxy qua X-Forwarded-Proto).
 */
if (!function_exists('auth_token_is_https')) {
    function auth_token_is_https()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            return true;
        }
        $proto = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));
        return $proto === 'https';
    }
}

/**
 * Chuẩn hoá lựa chọn "nhớ đăng nhập" thành bool nghiêm ngặt.
 * Chấp nhận mọi biến thể truthy phổ biến của form/JS ('on', '1', 1, true, 'true', 'yes').
 */
if (!function_exists('auth_remember_requested')) {
    function auth_remember_requested($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value)) {
            return $value !== 0;
        }
        $value = strtolower(trim((string) $value));
        return in_array($value, ['1', 'on', 'true', 'yes'], true);
    }
}

/**
 * TTL (giây) theo lựa chọn remember — chỉ 2 giá trị hợp lệ: 2 hoặc 7 ngày.
 */
if (!function_exists('auth_token_ttl')) {
    function auth_token_ttl($remember)
    {
        return auth_remember_requested($remember) ? AUTH_TOKEN_TTL_REMEMBER : AUTH_TOKEN_TTL_DEFAULT;
    }
}

/**
 * Hash SHA-256 của raw token — đây là giá trị duy nhất được lưu vào DB.
 */
if (!function_exists('auth_token_hash')) {
    function auth_token_hash($raw)
    {
        return hash('sha256', (string) $raw);
    }
}

/**
 * Gửi cookie remember_me (raw token) với đầy đủ cờ bảo mật + thời hạn tuyệt đối.
 */
if (!function_exists('auth_token_send_cookie')) {
    function auth_token_send_cookie($raw, $expiresAt)
    {
        setcookie(AUTH_TOKEN_COOKIE, (string) $raw, [
            'expires' => (int) $expiresAt,
            'path' => '/',
            'secure' => auth_token_is_https(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

/**
 * Xoá cookie remember_me trên trình duyệt.
 */
if (!function_exists('auth_token_clear_cookie')) {
    function auth_token_clear_cookie()
    {
        setcookie(AUTH_TOKEN_COOKIE, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => auth_token_is_https(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

/**
 * Cấp persistent token mới cho user.
 * Trả về expires_at (unix ts) hoặc 0 nếu thất bại. Raw token nằm trong cookie (không trả về).
 * expires_at là TUYỆT ĐỐI kể từ lúc cấp — không gia hạn qua rotation.
 */
if (!function_exists('auth_token_issue')) {
    function auth_token_issue($db, $userId, $remember = false, $ip = '')
    {
        $userId = (int) $userId;
        if ($userId <= 0) {
            return 0;
        }
        $ttl = auth_token_ttl($remember);
        $expiresAt = time() + $ttl;
        $raw = bin2hex(random_bytes(32)); // 64 hex chars — chỉ nằm trong cookie
        $db->insert('auth_tokens', [
            'user_id' => $userId,
            'token' => auth_token_hash($raw),
            'ip' => substr((string) $ip, 0, 64), // IP (IPv4/IPv6) là ASCII -> substr đủ
            'expires_at' => date('Y-m-d H:i:s', $expiresAt),
        ]);
        auth_token_send_cookie($raw, $expiresAt);
        return $expiresAt;
    }
}

/**
 * Restore phiên đăng nhập từ cookie remember_me.
 * Trả về username (string) nếu hợp lệ, '' nếu không.
 * - Cookie rỗng/sai định dạng -> '' (không query DB).
 * - Token hết hạn -> xoá khỏi DB + cookie -> ''.
 * - User banned -> revoke token đó + '' (banned không bao giờ restore).
 * - Hợp lệ: $session->send() (session_regenerate_id) + set auth_expires_at TUYỆT ĐỐI
 *   theo expires_at của token (rotation không gia hạn).
 */
if (!function_exists('auth_token_restore')) {
    function auth_token_restore($db, Session $session)
    {
        $raw = (string) ($_COOKIE[AUTH_TOKEN_COOKIE] ?? '');
        // Raw token phải là hex 64 ký tự (32 bytes) — mọi giá trị khác đều bỏ qua.
        if (!preg_match('/^[a-f0-9]{64}$/i', $raw)) {
            if ($raw !== '') {
                auth_token_clear_cookie();
            }
            return '';
        }
        $hash = auth_token_hash($raw);
        $row = $db->get_row(
            "SELECT * FROM `auth_tokens` WHERE `token` = '" . $db->escape($hash) . "' LIMIT 1"
        );
        if (!$row) {
            auth_token_clear_cookie();
            return '';
        }
        $expiresAt = strtotime((string) $row['expires_at']);
        if (!$expiresAt || $expiresAt <= time()) {
            $db->query("DELETE FROM `auth_tokens` WHERE `id` = '" . (int) $row['id'] . "'");
            auth_token_clear_cookie();
            return '';
        }
        $account = $db->get_row(
            "SELECT `username`, `banned` FROM `users` WHERE `id` = '" . (int) $row['user_id'] . "' LIMIT 1"
        );
        if (!$account) {
            $db->query("DELETE FROM `auth_tokens` WHERE `id` = '" . (int) $row['id'] . "'");
            auth_token_clear_cookie();
            return '';
        }
        if ((int) $account['banned'] === 1) {
            // Banned users never restore: revoke token luôn.
            auth_token_revoke_by_hash($db, $hash);
            auth_token_clear_cookie();
            return '';
        }
        $session->send((string) $account['username']);
        $_SESSION['auth_expires_at'] = $expiresAt; // thời hạn tuyệt đối — không gia hạn
        return (string) $account['username'];
    }
}

/**
 * Thu hồi một token theo SHA-256 hash.
 */
if (!function_exists('auth_token_revoke_by_hash')) {
    function auth_token_revoke_by_hash($db, $hash)
    {
        $db->query("DELETE FROM `auth_tokens` WHERE `token` = '" . $db->escape((string) $hash) . "'");
    }
}

/**
 * Thu hồi token hiện tại (đọc từ cookie) + xoá cookie. Dùng cho logout.
 */
if (!function_exists('auth_token_revoke_current')) {
    function auth_token_revoke_current($db)
    {
        $raw = (string) ($_COOKIE[AUTH_TOKEN_COOKIE] ?? '');
        if (preg_match('/^[a-f0-9]{64}$/i', $raw)) {
            auth_token_revoke_by_hash($db, auth_token_hash($raw));
        }
        auth_token_clear_cookie();
    }
}

/**
 * Thu hồi TẤT CẢ token của một user. Dùng khi đổi/reset mật khẩu.
 */
if (!function_exists('auth_token_revoke_all')) {
    function auth_token_revoke_all($db, $userId)
    {
        $db->query("DELETE FROM `auth_tokens` WHERE `user_id` = '" . (int) $userId . "'");
    }
}
