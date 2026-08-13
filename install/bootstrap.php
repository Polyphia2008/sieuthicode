<?php
/**
 * Bootstrap riêng cho trình cài đặt.
 *
 * KHÔNG require libs/init.php ở đây vì init.php sẽ exit khi chưa có database,
 * khiến form cài đặt không bao giờ hiển thị. Installer chỉ dùng libs/install.php.
 *
 * File này chỉ được require nội bộ. Truy cập trực tiếp qua web bị chặn (403).
 */

// Fail-closed: chỉ cho phép include từ install/index.php hoặc install/api.php.
$__self = basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
if ($__self !== 'index.php' && $__self !== 'api.php') {
    header('HTTP/1.1 403 Forbidden', true, 403);
    exit('Forbidden');
}
unset($__self);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/libs/install.php';

installer_session_start();
installer_security_headers();

/**
 * Nếu website đã cài đặt (hoặc đã cấu hình nhưng DB tạm offline) thì khoá installer:
 *  - GET /install  -> chuyển về trang chủ (website hoạt động bình thường).
 *  - POST api      -> trả 403 JSON.
 * Không cho phép bypass bằng query string hay cookie. Trạng thái 'offline' vẫn
 * khoá installer: database tạm mất kết nối không được phép mở lại trình cài đặt
 * để người lạ trỏ website sang database khác.
 */
function installer_guard_not_installed($isApi)
{
    if (isApplicationInstalled()) {
        if ($isApi) {
            installer_json(false, 'Website đã được cài đặt. Trình cài đặt đã bị khoá.', [], 403);
        }
        header('Location: /', true, 302);
        exit;
    }
}
