<?php
/**
 * Bootstrap riêng cho trình cài đặt.
 *
 * KHÔNG require libs/init.php ở đây vì init.php sẽ exit khi chưa có database,
 * khiến form cài đặt không bao giờ hiển thị. Installer chỉ dùng libs/install.php.
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/libs/install.php';

installer_session_start();
installer_security_headers();

/**
 * Nếu website đã cài đặt thì khoá installer:
 *  - GET /install  -> chuyển về trang chủ (website hoạt động bình thường).
 *  - POST api      -> trả 403 JSON.
 * Không cho phép bypass bằng query string hay cookie.
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
