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

if (!function_exists('count_all_superadmins')) {
    /**
     * Đếm TẤT CẢ tài khoản superadmin (kể cả đang bị ban). Chỉ còn để tương
     * thích ngược — invariant an toàn dùng count_superadmins() (active only).
     * @param DB $db
     */
    function count_all_superadmins($db)
    {
        $row = $db->get_row("SELECT COUNT(*) AS c FROM `users` WHERE `level` = 'superadmin'");
        return $row ? (int) $row['c'] : 0;
    }
}

if (!function_exists('is_last_superadmin')) {
    /**
     * Kiểm tra $account có phải superadmin HOẠT ĐỘNG cuối cùng của hệ thống.
     *
     * Invariant: sau mọi thao tác delete/demote/ban, hệ thống phải còn ít
     * nhất MỘT tài khoản `level = 'superadmin' AND banned = 0`. Một superadmin
     * đang bị ban KHÔNG được tính là "còn lại" — vì vậy nếu chỉ còn 1 super
     * active (+ bất kỳ số banned nào) thì super active đó vẫn được bảo vệ
     * tuyệt đối, tránh trường hợp hệ thống rơi về 0 superadmin hoạt động.
     */
    function is_last_superadmin($db, $account)
    {
        if (!is_array($account) || ($account['level'] ?? '') !== 'superadmin') {
            return false;
        }
        // Chỉ superadmin ĐANG HOẠT ĐỘNG mới có thể là "active cuối cùng".
        if ((int) ($account['banned'] ?? 0) !== 0) {
            return false;
        }
        return count_superadmins($db) <= 1;
    }
}

if (!function_exists('superadmin_guard_begin')) {
    /**
     * Bắt đầu transaction và khoá TOÀN BỘ active superadmin (FOR UPDATE).
     *
     * TOCTOU fix: chỉ khoá hàng target (SELECT ... WHERE id = N FOR UPDATE)
     * KHÔNG serialize hai request thao tác trên hai superadmin khác nhau A/B —
     * mỗi request khoá một hàng riêng, cả hai cùng đếm thấy 2 và cùng thành
     * công => hệ thống rơi về 0 active superadmin.
     *
     * Hàm này khoá MỌI hàng active superadmin theo thứ tự ổn định (ORDER BY
     * id) nên mọi thao tác demote/ban/delete superadmin đều tranh chấp trên
     * CÙNG tập khoá => serialize hoàn toàn. Request thứ hai block tới khi
     * request đầu COMMIT/ROLLBACK, rồi đọc lại count đã thay đổi => bị chặn.
     *
     * get_list() (classes/db.php) buffer TOÀN BỘ result (fetch hết + free) nên
     * mọi row lock InnoDB thực sự được giữ tới khi COMMIT/ROLLBACK.
     *
     * Trả về mảng id (int[]) của các active superadmin đang bị khoá.
     * @param DB $db
     */
    function superadmin_guard_begin($db)
    {
        $db->query('START TRANSACTION');
        $rows = $db->get_list(
            "SELECT `id` FROM `users` WHERE `level` = 'superadmin' AND `banned` = 0 ORDER BY `id` FOR UPDATE"
        );
        $ids = [];
        foreach ($rows as $r) {
            $ids[] = (int) $r['id'];
        }
        return $ids;
    }
}

if (!function_exists('superadmin_guard_rollback')) {
    /**
     * Hoàn tác transaction đã mở bởi superadmin_guard_begin(). Gọi ROLLBACK
     * trên mọi error path sau START TRANSACTION.
     * @param DB $db
     */
    function superadmin_guard_rollback($db)
    {
        $db->query('ROLLBACK');
    }
}

if (!function_exists('superadmin_guard_commit')) {
    /**
     * Commit transaction đã mở bởi superadmin_guard_begin() sau khi thao tác
     * UPDATE/DELETE đã thành công.
     * @param DB $db
     */
    function superadmin_guard_commit($db)
    {
        $db->query('COMMIT');
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Kiểm tra CSRF token của form/AJAX. Token được generate_csrf_token()
     * (classes/functions.php) lưu trong $_SESSION['csrf_token'].
     *
     * Phân biệt mã lỗi (theo audit):
     *  - CSRF thiếu/sai          -> HTTP 419 (Page Expired / Authentication Timeout)
     *  - Không đủ quyền (role)   -> HTTP 403 (xem require_admin/require_superadmin)
     *  - Chưa đăng nhập          -> giữ hành vi hiện có của từng endpoint
     *
     * @param bool $json true -> trả JSON 419; false -> trả HTML 419
     */
    function verify_csrf_token($json = false)
    {
        $posted = (string) ($_POST['csrf_token'] ?? '');
        $session = (string) ($_SESSION['csrf_token'] ?? '');
        $ok = $posted !== '' && $session !== '' && hash_equals($session, $posted);
        if (!$ok) {
            // Apache 2.4 + mod_php convert http_response_code(419) -> 500 vì
            // 419 không nằm trong bảng status line của httpd. Gửi status line
            // đầy đủ qua header() để client nhận đúng mã 419.
            header('HTTP/1.1 419 Authentication Timeout', true, 419);
            if ($json) {
                exit(JsonMsg('error', 'Invalid CSRF Protection Token'));
            }
            exit('<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">'
                . '<meta name="viewport" content="width=device-width, initial-scale=1">'
                . '<title>419</title></head><body style="font-family:system-ui,Arial,sans-serif;'
                . 'display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0">'
                . '<div style="text-align:center"><h1>419</h1>'
                . '<p>Invalid CSRF Protection Token.</p></div></body></html>');
        }
    }
}
