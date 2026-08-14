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

/* ---- count_superadmins / is_last_superadmin ---- */
$db = new StubDb();
$db->superCount = 2;
ok(count_superadmins($db) === 2, 'count_superadmins: trả về đúng số lượng');
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === false,
    'is_last_superadmin: 2 superadmin hoạt động -> false');
$db->superCount = 1;
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 0]) === true,
    'is_last_superadmin: 1 superadmin hoạt động -> true');
ok(is_last_superadmin($db, ['level' => 'admin', 'banned' => 0]) === false,
    'is_last_superadmin: admin -> false');
ok(is_last_superadmin($db, ['level' => 'superadmin', 'banned' => 1]) === false,
    'is_last_superadmin: superadmin đã bị ban -> false');
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
ok(stripos($mig, 'NOT EXISTS') !== false, 'migration: NOT EXISTS chặn khi đã có superadmin');
ok(stripos($mig, "banned` = 0") !== false, 'migration: chỉ promote admin chưa bị ban');
ok(!preg_match('/password\s*=\s*\'[^\']*\'/i', $mig), 'migration: không set password/demo credential');
ok(stripos($mig, 'CREATE TABLE') === false, 'migration: không tạo bảng (an toàn chạy lại)');

/* ---- Base SQL bootstrap phải là superadmin ---- */
$base = file_get_contents(APP_ROOT . '/shoprobloxv4 (2).sql');
ok(strpos($base, "(1, 'admin', '!', 'superadmin'") !== false,
    'base SQL: bootstrap admin có level superadmin');
ok(strpos($base, "(1, 'admin', '!', 'admin'") === false,
    'base SQL: không còn bootstrap level admin');

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
