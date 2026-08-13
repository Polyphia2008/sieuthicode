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
 * Bảo vệ trình cài đặt theo state machine:
 *  - 'installed'/'offline' : khoá installer (GET -> 302 về '/', API -> 403 JSON).
 *    Không cho phép bypass bằng query string hay cookie. Trạng thái 'offline'
 *    vẫn khoá installer: database tạm mất kết nối không được phép mở lại trình
 *    cài đặt để người lạ trỏ website sang database khác.
 *  - 'installing'          : một tiến trình cài đặt khác đang giữ mutex. KHÔNG
 *    render thêm form installer: GET -> 503 "Đang cài đặt", API -> 409 JSON.
 *  - 'journal_invalid'     : journal hỏng / sai version / sai fingerprint. Fail
 *    closed: không DROP gì, GET -> 503 hướng dẫn kiểm tra, API -> 409 JSON.
 *  - 'uninstalled'/'interrupted' : installer mở bình thường (interrupted cho
 *    phép người dùng nhập lại DB và phục hồi qua fingerprint ở bước install).
 */
function installer_guard_not_installed($isApi)
{
    $state = installer_state();

    if ($state === 'installed' || $state === 'offline') {
        if ($isApi) {
            installer_json(false, 'Website đã được cài đặt. Trình cài đặt đã bị khoá.', [], 403);
        }
        header('Location: /', true, 302);
        exit;
    }

    if ($state === 'installing') {
        if ($isApi) {
            installer_json(false, 'Một tiến trình cài đặt khác đang chạy. Vui lòng chờ.', [], 409);
        }
        installer_status_header(503);
        header('Content-Type: text/html; charset=utf-8');
        header('Retry-After: 30');
        exit('<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>Đang cài đặt</title></head><body '
            . 'style="font-family:system-ui,Arial,sans-serif;display:flex;align-items:center;'
            . 'justify-content:center;min-height:100vh;margin:0;background:#0f1420;color:#e6e9f2">'
            . '<div style="max-width:480px;padding:24px;text-align:center">'
            . '<h1 style="font-size:20px">Đang cài đặt</h1>'
            . '<p style="color:#8b93ad;font-size:14px;line-height:1.6">Một tiến trình cài đặt khác đang chạy. '
            . 'Vui lòng chờ hoàn tất rồi tải lại trang.</p></div></body></html>');
    }

    if ($state === 'journal_invalid') {
        if ($isApi) {
            installer_json(
                false,
                'Phát hiện journal cài đặt dở nhưng không thể xác minh an toàn (file hỏng, sai phiên bản '
                . 'hoặc thuộc máy chủ database khác). Không tự động dọn dẹp. Quản trị viên hãy kiểm tra '
                . 'database rồi xoá thủ công storage/install.journal.json trước khi cài lại.',
                [],
                409
            );
        }
        installer_status_header(503);
        header('Content-Type: text/html; charset=utf-8');
        exit('<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>Cần kiểm tra cài đặt</title></head><body '
            . 'style="font-family:system-ui,Arial,sans-serif;display:flex;align-items:center;'
            . 'justify-content:center;min-height:100vh;margin:0;background:#0f1420;color:#e6e9f2">'
            . '<div style="max-width:480px;padding:24px;text-align:center">'
            . '<h1 style="font-size:20px">Cần kiểm tra cài đặt</h1>'
            . '<p style="color:#8b93ad;font-size:14px;line-height:1.6">Phát hiện journal cài đặt dở nhưng không '
            . 'thể xác minh an toàn (file hỏng, sai phiên bản hoặc thuộc máy chủ database khác). '
            . 'Trình cài đặt tạm khoá để bảo vệ dữ liệu. Quản trị viên hãy kiểm tra database rồi xoá thủ công '
            . 'storage/install.journal.json trước khi tiếp tục.</p></div></body></html>');
    }
    // 'uninstalled' hoặc 'interrupted': cho phép installer hoạt động.
}
