<?php
/**
 * helpers phân quyền trung tâm — role hierarchy: superadmin > admin > member.
 *
 *  - is_admin_account()      : true cho cả 'admin' lẫn 'superadmin'
 *  - is_superadmin_account() : chỉ 'superadmin'
 *  - require_admin()         : chặn truy cập khu vực quản trị (cpanel)
 *  - require_superadmin()    : chặn các chức năng nhạy cảm (cấu hình nạp
 *                              tiền, đổi role, sửa tài khoản superadmin)
 *
 * Không chứa credential. Mọi hàm chỉ đọc $data_user / $db đã được
 * libs/init.php khởi tạo.
 */

if (!function_exists('is_admin_account')) {
    /**
     * Tài khoản có quyền vào khu quản trị: level 'admin' hoặc 'superadmin'.
     * @param array|null $account hàng users (có cột level); null dùng $data_user toàn cục
     */
    function is_admin_account($account = null)
    {
        if ($account === null && isset($GLOBALS['data_user']) && is_array($GLOBALS['data_user'])) {
            $account = $GLOBALS['data_user'];
        }
        $level = is_array($account) ? (string) ($account['level'] ?? 'member') : 'member';
        return $level === 'admin' || $level === 'superadmin';
    }
}

if (!function_exists('is_superadmin_account')) {
    /**
     * Chỉ đúng với tài khoản level 'superadmin'.
     * @param array|null $account hàng users; null dùng $data_user toàn cục
     */
    function is_superadmin_account($account = null)
    {
        if ($account === null && isset($GLOBALS['data_user']) && is_array($GLOBALS['data_user'])) {
            $account = $GLOBALS['data_user'];
        }
        return is_array($account) && (($account['level'] ?? 'member') === 'superadmin');
    }
}

if (!function_exists('require_admin')) {
    /**
     * Gate khu quản trị. Với trang HTML: redirect về '/'. Với endpoint JSON
     * (AJAX): trả lỗi JSON 403. Gọi SAU khi init.php đã nạp $user/$data_user.
     */
    function require_admin($json = false)
    {
        $user = isset($GLOBALS['user']) ? (string) $GLOBALS['user'] : '';
        if ($user === '' || !is_admin_account()) {
            if ($json) {
                http_response_code(403);
                exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
            }
            new Redirect('/');
            exit();
        }
    }
}

if (!function_exists('require_superadmin')) {
    /**
     * Gate chức năng chỉ superadmin được dùng. Trang HTML -> 403; JSON -> JSON 403.
     */
    function require_superadmin($json = false)
    {
        $user = isset($GLOBALS['user']) ? (string) $GLOBALS['user'] : '';
        if ($user === '' || !is_superadmin_account()) {
            if ($json) {
                http_response_code(403);
                exit(JsonMsg('error', 'Chức năng này chỉ dành cho Superadmin'));
            }
            http_response_code(403);
            exit('<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">'
                . '<meta name="viewport" content="width=device-width, initial-scale=1">'
                . '<title>403</title></head><body style="font-family:system-ui,Arial,sans-serif;'
                . 'display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0">'
                . '<div style="text-align:center"><h1>403</h1>'
                . '<p>Chức năng này chỉ dành cho Superadmin.</p></div></body></html>');
        }
    }
}

if (!function_exists('count_superadmins')) {
    /**
     * Đếm số tài khoản superadmin còn hoạt động (chưa bị ban).
     * @param DB $db
     */
    function count_superadmins($db)
    {
        $row = $db->get_row("SELECT COUNT(*) AS c FROM `users` WHERE `level` = 'superadmin' AND `banned` = 0");
        return $row ? (int) $row['c'] : 0;
    }
}

if (!function_exists('is_last_superadmin')) {
    /**
     * Kiểm tra $account có phải superadmin cuối cùng còn hoạt động không —
     * dùng để chặn demote/ban/delete làm hệ thống mất quyền quản trị.
     */
    function is_last_superadmin($db, $account)
    {
        if (!is_array($account) || ($account['level'] ?? '') !== 'superadmin') {
            return false;
        }
        if ((int) ($account['banned'] ?? 0) === 1) {
            return false; // đã bị ban sẵn thì không nằm trong nhóm "còn hoạt động"
        }
        return count_superadmins($db) <= 1;
    }
}
