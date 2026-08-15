<?php
/**
 * Unit test cho helpers phân quyền (libs/auth.php) — chạy không cần server/DB.
 *
 *   php tests/auth_superadmin_test.php
 */

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/libs/auth.php';

$pass = 0;
$fail = 0;
function ok($cond, $name)
{
    global $pass, $fail;
    if ($cond) {
        $pass++;
        echo "[PASS] $name\n";
    } else {
        $fail++;
        echo "[FAIL] $name\n";
    }
}

/** Stub DB tối thiểu cho count_superadmins(): trả COUNT(*) cố định. */
class StubDb
{
    public $superCount = 0;
    public function get_row($sql)
    {
        return ['c' => $this->superCount];
    }
}

/* ---- is_admin_account ---- */
ok(is_admin_account(['level' => 'admin']) === true, 'is_admin_account: admin -> true');
ok(is_admin_account(['level' => 'superadmin']) === true, 'is_admin_account: superadmin -> true');
ok(is_admin_account(['level' => 'member']) === false, 'is_admin_account: member -> false');
ok(is_admin_account(['level' => 'ctv']) === false, 'is_admin_account: ctv -> false');
ok(is_admin_account(null) === false, 'is_admin_account: null (no $data_user) -> false');
ok(is_admin_account([]) === false, 'is_admin_account: empty row -> false');

/* ---- is_superadmin_account ---- */
ok(is_superadmin_account(['level' => 'superadmin']) === true, 'is_superadmin_account: superadmin -> true');
ok(is_superadmin_account(['level' => 'admin']) === false, 'is_superadmin_account: admin -> false');
ok(is_superadmin_account(['level' => 'member']) === false, 'is_superadmin_account: member -> false');
ok(is_superadmin_account(null) === false, 'is_superadmin_account: null -> false');

/* ---- $GLOBALS['data_user'] fallback ---- */
$GLOBALS['data_user'] = ['level' => 'superadmin'];
ok(is_admin_account() === true, 'is_admin_account: dùng $data_user global (superadmin)');
ok(is_superadmin_account() === true, 'is_superadmin_account: dùng $data_user global (superadmin)');
$GLOBALS['data_user'] = ['level' => 'admin'];
ok(is_superadmin_account() === false, 'is_superadmin_account: $data_user admin -> false');
unset($GLOBALS['data_user']);

/* ---- count_superadmins / count_all_superadmins / is_last_superadmin ---- */
$db = new StubDb();
$db->superCount = 2;
ok(count_superadmins($db) === 2, 'count_superadmins: trả về đúng số lượng');
ok(count_all_superadmins($db) === 2, 'count_all_superadmins: đếm cả banned');
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === false,
    'is_last_superadmin: 2 superadmin -> false');
$db->superCount = 1;
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === true,
    'is_last_superadmin: 1 superadmin hoạt động -> true');
ok(is_last_superadmin($db, ['level' => 'admin', 'banned' => 0]) === false,
    'is_last_superadmin: admin -> false');
// Nghiệp vụ mới (audit Blocker 4): invariant là ACTIVE superadmin cuối cùng.
// Một superadmin đang bị ban KHÔNG phải "active cuối cùng" -> không bị chặn
// bởi helper này (việc ban/unban nó không làm hệ thống mất active superadmin).
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 1]) === false,
    'is_last_superadmin: superadmin đang bị ban không phải ACTIVE cuối -> false');
$db->superCount = 1;
// 1 banned super + 1 active super: active super vẫn được bảo vệ tuyệt đối.
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === true,
    'is_last_superadmin: 1 active (+ banned khác) -> true');
$db->superCount = 0;
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === true,
    'is_last_superadmin: count<=1 -> true (bảo vệ fail-closed)');

/* ---- Migration idempotent (phân tích tĩnh) ---- */
$mig = file_get_contents(APP_ROOT . '/database/migrations/20260814_superadmin_sessions.sql');
ok($mig !== false, 'migration file tồn tại');
ok(preg_match('/UPDATE\s+`users`\s+SET\s+`level`\s*=\s*\'superadmin\'/i', $mig) === 1,
    'migration: có UPDATE promote superadmin');
ok(stripos($mig, "'admin'") !== false && stripos($mig, 'MIN(`id`)') !== false,
    'migration: chỉ promote admin id nhỏ nhất');
ok(stripos($mig, 'NOT EXISTS') !== false, 'migration: NOT EXISTS chặn khi đã có superadmin active');
ok(preg_match("/level`\s*=\s*'superadmin'\s+AND\s+`banned`\s*=\s*0/i", $mig) === 1,
    'migration: NOT EXISTS chỉ tính superadmin ACTIVE (banned=0) — banned superadmin không chặn promote');
ok(substr_count($mig, "banned` = 0") >= 2, 'migration: cả candidate lẫn existing đều lọc banned=0');
ok(!preg_match('/password\s*=\s*\'[^\']*\'/i', $mig), 'migration: không set password/demo credential');
ok(stripos($mig, 'CREATE TABLE') === false, 'migration: không tạo bảng (an toàn chạy lại)');

/* ---- Base SQL bootstrap phải là superadmin ---- */
$base = file_get_contents(APP_ROOT . '/shoprobloxv4 (2).sql');
ok(strpos($base, "(1, 'admin', '!', 'superadmin'") !== false,
    'base SQL: bootstrap admin có level superadmin');
ok(strpos($base, "(1, 'admin', '!', 'admin'") === false,
    'base SQL: không còn bootstrap level admin');

/* ---- verify_csrf_token tồn tại và các endpoint nhạy cảm đã gọi nó ---- */
ok(function_exists('verify_csrf_token'), 'libs/auth.php: có verify_csrf_token');
$csrfTargets = [
    // Sau f632f979: mỗi view có 1 gate CSRF tập trung chạy TRƯỚC header.php
    // (gate bao phủ mọi POST action của view: SaveSettings/ThemNganHang, ...).
    'cpanel/views/recharge/config.php' => 1,   // gate SaveSettings|ThemNganHang
    'cpanel/views/recharge/card-config.php' => 1,
    'cpanel/views/recharge/edit.php' => 1,
    'cpanel/views/users/edit.php' => 5,        // CongTien/TruTien/CongItem/TruItem/SaveUser
    'models/admin/delete.php' => 1,
    'models/admin/update.php' => 1,
];
foreach ($csrfTargets as $file => $minCalls) {
    $src = file_get_contents(APP_ROOT . '/' . $file);
    ok($src !== false && substr_count($src, 'verify_csrf_token') >= $minCalls,
        "CSRF: $file gọi verify_csrf_token >= $minCalls lần");
}

/* ---- Endpoint nhạy cảm phải trả HTTP 403, không chỉ JSON error ---- */
foreach (['models/admin/delete.php', 'models/admin/update.php'] as $file) {
    $src = file_get_contents(APP_ROOT . '/' . $file);
    ok(strpos($src, 'http_response_code(403)') !== false,
        "403: $file trả HTTP 403 khi từ chối quyền");
}

/* ---- CSRF phải trả HTTP 419 (audit Blocker 2), KHÔNG 403 ---- */
$authSrc = file_get_contents(APP_ROOT . '/libs/auth.php');
// f632f979: dùng header('HTTP/1.1 419 ...', true, 419) thay http_response_code(419)
// vì Apache 2.4 + mod_php convert mã 419 lạ thành 500 (không có trong bảng status).
ok(preg_match("/function\\s+verify_csrf_token[\\s\\S]*?HTTP\\/1\\.1 419 Authentication Timeout',\\s*true,\\s*419/", $authSrc) === 1,
    'CSRF: verify_csrf_token trả HTTP 419 khi token thiếu/sai');
// Trong thân verify_csrf_token không còn trả 403 (403 chỉ dành cho từ chối quyền).
ok(preg_match('/function\s+verify_csrf_token\(\)[\s\S]*?http_response_code\(403\)/', $authSrc) !== 1,
    'CSRF: verify_csrf_token không còn trả 403');

/* ---- require_superadmin trước header trên trang nhạy cảm ---- */
foreach (['cpanel/views/recharge/config.php', 'cpanel/views/recharge/card-config.php', 'cpanel/views/recharge/edit.php'] as $file) {
    $src = file_get_contents(APP_ROOT . '/' . $file);
    $posGate = strpos($src, 'require_superadmin(false)');
    $posHeader = strpos($src, "cpanel/views/header.php");
    ok($posGate !== false && $posHeader !== false && $posGate < $posHeader,
        "Order: $file gọi require_superadmin TRƯỚC cpanel header");
}

/* ---- Admin thường không thấy role select (readonly) ---- */
$srcUserEdit = file_get_contents(APP_ROOT . '/cpanel/views/users/edit.php');
ok(strpos($srcUserEdit, '\'readonly\'') !== false || strpos($srcUserEdit, 'readonly>') !== false,
    'users/edit.php: role readonly cho admin thường');
ok(strpos($srcUserEdit, '$__actorIsSuperadmin') !== false,
    'users/edit.php: phân nhánh render theo actor superadmin');

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
