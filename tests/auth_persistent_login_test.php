<?php
/**
 * Unit test Batch 3 — Persistent login 2/7 ngày.
 * Chạy: php tests/auth_persistent_login_test.php
 * Không cần DB server: dùng stub cho DB/Session; phần flow dùng static analysis.
 */

error_reporting(E_ALL);
define('APP_ROOT', dirname(__DIR__));

$RESULTS = ['pass' => 0, 'fail' => 0];
function ok($cond, $label)
{
    global $RESULTS;
    if ($cond) {
        $RESULTS['pass']++;
        echo "PASS: $label\n";
    } else {
        $RESULTS['fail']++;
        echo "FAIL: $label\n";
    }
}

require_once APP_ROOT . '/classes/session.php';
require_once APP_ROOT . '/libs/auth_tokens.php';

// ---------- Stubs ----------
class TestSession extends Session
{
    public $sentUser = null;
    public function send($user) { $this->sentUser = (string) $user; }
}

class StubDbTokens
{
    public $rows = [];  // auth_tokens rows
    public $users = []; // id => ['username'=>..., 'banned'=>...]
    public function escape($v) { return addslashes((string) $v); }
    public function get_row($sql)
    {
        if (strpos($sql, 'auth_tokens') !== false
            && preg_match("/`token` = '([^']+)'/", $sql, $m)) {
            foreach ($this->rows as $r) {
                if ($r['token'] === $m[1]) {
                    return $r;
                }
            }
            return null;
        }
        if (strpos($sql, '`users`') !== false && preg_match("/`id` = '(\d+)'/", $sql, $m)) {
            return $this->users[(int) $m[1]] ?? null;
        }
        return null;
    }
    public function query($sql)
    {
        if (preg_match("/DELETE FROM `auth_tokens` WHERE `id` = '(\d+)'/", $sql, $m)) {
            $id = (int) $m[1];
            $this->rows = array_values(array_filter($this->rows, function ($r) use ($id) {
                return (int) $r['id'] !== $id;
            }));
        }
        if (preg_match("/DELETE FROM `auth_tokens` WHERE `token` = '([^']+)'/", $sql, $m)) {
            $h = $m[1];
            $this->rows = array_values(array_filter($this->rows, function ($r) use ($h) {
                return $r['token'] !== $h;
            }));
        }
        if (preg_match("/DELETE FROM `auth_tokens` WHERE `user_id` = '(\d+)'/", $sql, $m)) {
            $uid = (int) $m[1];
            $this->rows = array_values(array_filter($this->rows, function ($r) use ($uid) {
                return (int) $r['user_id'] !== $uid;
            }));
        }
        return true;
    }
    public function insert($table, $data)
    {
        $data['id'] = count($this->rows) + 1;
        $this->rows[] = $data;
        return true;
    }
}

// ---------- 1) Constants ----------
ok(defined('AUTH_TOKEN_COOKIE') && AUTH_TOKEN_COOKIE === 'remember_me', 'cookie name = remember_me');
ok(AUTH_TOKEN_TTL_DEFAULT === 172800, 'TTL mặc định = 172800s (2 ngày)');
ok(AUTH_TOKEN_TTL_REMEMBER === 604800, 'TTL remember = 604800s (7 ngày)');
ok(AUTH_SESSION_MAX_TTL >= 604800, 'session gc_maxlifetime tối thiểu >= 604800s');

// ---------- 2) remember-boolean truth table ----------
ok(auth_remember_requested('on') === true, "remember 'on' => true");
ok(auth_remember_requested('1') === true, "remember '1' => true");
ok(auth_remember_requested(1) === true, 'remember 1 => true');
ok(auth_remember_requested(true) === true, 'remember true => true');
ok(auth_remember_requested('true') === true, "remember 'true' => true");
ok(auth_remember_requested('yes') === true, "remember 'yes' => true");
ok(auth_remember_requested('ON') === true, "remember 'ON' (hoa) => true");
ok(auth_remember_requested('') === false, "remember '' => false");
ok(auth_remember_requested('0') === false, "remember '0' => false");
ok(auth_remember_requested(0) === false, 'remember 0 => false');
ok(auth_remember_requested(false) === false, 'remember false => false');
ok(auth_remember_requested(null) === false, 'remember null => false');
ok(auth_remember_requested('off') === false, "remember 'off' => false");
ok(auth_remember_requested('2') === false, "remember '2' (lạ) => false");

// ---------- 3) TTL mapping ----------
ok(auth_token_ttl(false) === 172800, 'ttl(false) = 2 ngày');
ok(auth_token_ttl(true) === 604800, 'ttl(true) = 7 ngày');
ok(auth_token_ttl('on') === 604800, "ttl('on') = 7 ngày");
ok(auth_token_ttl('') === 172800, "ttl('') = 2 ngày");

// ---------- 4) Hash ----------
$h = auth_token_hash('abc');
ok($h === hash('sha256', 'abc'), 'hash = SHA-256(raw)');
ok(strlen($h) === 64 && ctype_xdigit($h), 'hash dài 64 hex (vừa cột varchar(64))');
ok(auth_token_hash('abc') === auth_token_hash('abc'), 'hash ổn định (deterministic)');
ok(auth_token_hash('abc') !== auth_token_hash('abd'), 'hash phân biệt input khác nhau');

// ---------- 5) Issue: DB chỉ lưu hash, expires_at tuyệt đối ----------
$noWarn = E_ALL & ~E_WARNING; // setcookie trong CLI cảnh báo headers — bỏ qua trong test
error_reporting($noWarn);
$_COOKIE = [];
$db = new StubDbTokens();
$before = time();
$exp = auth_token_issue($db, 42, false, '203.0.113.9');
error_reporting(E_ALL);
ok($exp >= $before + 172800 && $exp <= time() + 172800, 'issue default: expires_at ~ now+2 ngày (tuyệt đối)');
ok(count($db->rows) === 1, 'issue: đã ghi 1 row auth_tokens');
$row = $db->rows[0];
ok((int) $row['user_id'] === 42, 'issue: đúng user_id');
ok(strlen($row['token']) === 64 && ctype_xdigit($row['token']), 'issue: DB lưu hash 64 hex');
ok($row['token'] !== ($_COOKIE[AUTH_TOKEN_COOKIE] ?? 'no-cookie'), 'issue: DB token khác raw cookie (chỉ lưu hash)');
ok(strtotime($row['expires_at']) === $exp, 'issue: expires_at datetime khớp timestamp tuyệt đối');
ok($row['ip'] === '203.0.113.9', 'issue: lưu IP để audit');

error_reporting($noWarn);
$db2 = new StubDbTokens();
$exp2 = auth_token_issue($db2, 7, true, str_repeat('9.', 40)); // IP dài -> cắt 64 ký tự
error_reporting(E_ALL);
ok($exp2 >= time() + 604800 - 2 && $exp2 <= time() + 604800, 'issue remember: expires_at ~ now+7 ngày');
ok(strlen($db2->rows[0]['ip']) <= 64, 'issue: IP cắt tối đa 64 ký tự');
ok(auth_token_issue(new StubDbTokens(), 0, false) === 0, 'issue: user_id <= 0 -> từ chối');

// ---------- 6) Restore flow ----------
// 6a. Cookie rỗng -> ''
$_COOKIE = [];
$db = new StubDbTokens();
$ses = new TestSession();
ok(auth_token_restore($db, $ses) === '', 'restore: không cookie -> không restore');

// 6b. Cookie sai định dạng -> bỏ qua, không query
error_reporting($noWarn);
$_COOKIE = [AUTH_TOKEN_COOKIE => 'short-token'];
ok(auth_token_restore($db, $ses) === '', 'restore: token sai định dạng -> từ chối');
error_reporting(E_ALL);

// 6c. Token hợp lệ + user active -> restore, auth_expires_at = expires_at TUYỆT ĐỐI
$raw = bin2hex(random_bytes(32));
$hash = auth_token_hash($raw);
$absolute = time() + 400000; // mốc tuyệt đối bất kỳ trong tương lai
$db = new StubDbTokens();
$db->rows[] = ['id' => 1, 'user_id' => 42, 'token' => $hash, 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', $absolute)];
$db->users[42] = ['username' => 'duchuy', 'banned' => 0];
$_COOKIE = [AUTH_TOKEN_COOKIE => $raw];
$ses = new TestSession();
error_reporting($noWarn);
$u = auth_token_restore($db, $ses);
error_reporting(E_ALL);
ok($u === 'duchuy', 'restore: token hợp lệ -> trả về username');
ok($ses->sentUser === 'duchuy', 'restore: gọi session->send (regenerate id)');
ok((int) ($_SESSION['auth_expires_at'] ?? 0) === $absolute, 'restore: auth_expires_at = expires_at TUYỆT ĐỐI (rotation không gia hạn)');
ok(count($db->rows) === 1, 'restore hợp lệ: token không bị xoá');

// 6d. Token hết hạn -> xoá DB + từ chối
$db = new StubDbTokens();
$db->rows[] = ['id' => 2, 'user_id' => 42, 'token' => $hash, 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() - 10)];
$db->users[42] = ['username' => 'duchuy', 'banned' => 0];
$_COOKIE = [AUTH_TOKEN_COOKIE => $raw];
$ses = new TestSession();
error_reporting($noWarn);
$u = auth_token_restore($db, $ses);
error_reporting(E_ALL);
ok($u === '', 'restore: token hết hạn -> từ chối');
ok(count($db->rows) === 0, 'restore: token hết hạn bị xoá khỏi DB');

// 6e. User banned -> revoke + không bao giờ restore
$db = new StubDbTokens();
$db->rows[] = ['id' => 3, 'user_id' => 9, 'token' => $hash, 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 1000)];
$db->users[9] = ['username' => 'banneduser', 'banned' => 1];
$_COOKIE = [AUTH_TOKEN_COOKIE => $raw];
$ses = new TestSession();
error_reporting($noWarn);
$u = auth_token_restore($db, $ses);
error_reporting(E_ALL);
ok($u === '', 'restore: user banned -> KHÔNG restore');
ok(count($db->rows) === 0, 'restore: token của user banned bị thu hồi');

// 6f. Token không tồn tại trong DB -> từ chối
$db = new StubDbTokens();
$db->users[42] = ['username' => 'duchuy', 'banned' => 0];
$_COOKIE = [AUTH_TOKEN_COOKIE => $raw];
error_reporting($noWarn);
ok(auth_token_restore($db, new TestSession()) === '', 'restore: token lạ (không có trong DB) -> từ chối');
error_reporting(E_ALL);

// ---------- 7) Revoke ----------
$db = new StubDbTokens();
$db->rows[] = ['id' => 1, 'user_id' => 5, 'token' => auth_token_hash('a' . str_repeat('1', 63)), 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 100)];
$db->rows[] = ['id' => 2, 'user_id' => 5, 'token' => auth_token_hash('b' . str_repeat('2', 63)), 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 100)];
$db->rows[] = ['id' => 3, 'user_id' => 6, 'token' => auth_token_hash('c' . str_repeat('3', 63)), 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 100)];
auth_token_revoke_all($db, 5);
ok(count($db->rows) === 1 && (int) $db->rows[0]['user_id'] === 6, 'revoke_all: xoá hết token của user, giữ user khác');

$rawA = 'a' . str_repeat('1', 63);
$db = new StubDbTokens();
$db->rows[] = ['id' => 1, 'user_id' => 5, 'token' => auth_token_hash($rawA), 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 100)];
$db->rows[] = ['id' => 2, 'user_id' => 5, 'token' => auth_token_hash('d' . str_repeat('4', 63)), 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', time() + 100)];
$_COOKIE = [AUTH_TOKEN_COOKIE => $rawA];
error_reporting($noWarn);
auth_token_revoke_current($db);
error_reporting(E_ALL);
ok(count($db->rows) === 1 && $db->rows[0]['id'] === 2, 'revoke_current: chỉ xoá token hiện tại theo hash');

// ---------- 8) Static analysis: các flow đã gắn đúng helper ----------
$src = function ($f) { return file_get_contents(APP_ROOT . '/' . $f); };

$login = $src('models/client/login.php');
ok(strpos($login, "auth_remember_requested(\$_POST['remember-user']") !== false, 'login: đọc checkbox remember-user');
ok(strpos($login, "pending_2fa_remember") !== false, 'login: nhánh 2FA lưu lựa chọn 2/7 vào pending session');
ok(strpos($login, 'auth_token_issue($db, (int) $getUser[\'id\'], $remember') !== false, 'login: cấp persistent token sau khi đăng nhập thành công');
ok(strpos($login, "\$_SESSION['auth_expires_at'] = \$authExpiresAt") !== false, 'login: auth_expires_at = expires token (tuyệt đối)');

$auth = $src('models/client/authenticator.php');
$posSend = strpos($auth, '$session->send($getUser[\'username\'])');
$posIssue = strpos($auth, 'auth_token_issue');
ok($posSend !== false && $posIssue !== false && $posIssue > $posSend, '2FA: token chỉ cấp SAU verify thành công');
ok(strpos($auth, "pending_2fa_remember") !== false && strpos($auth, 'unset($_SESSION[\'pending_2fa_remember\'])') !== false, '2FA: đọc + dọn pending remember choice');

$google = $src('models/client/google.php');
ok(strpos($google, 'auth_token_issue($db, (int) $account[\'id\'], false') !== false, 'google OAuth: mặc định 2 ngày');

$reg = $src('models/client/register.php');
ok(strpos($reg, 'auth_token_issue($db, $newUserId, false') !== false, 'register auto-login: mặc định 2 ngày');

$logout = $src('views/auth/logout.php');
ok(strpos($logout, 'auth_token_revoke_current($db)') !== false, 'logout: thu hồi token hiện tại (theo hash)');
ok(strpos($logout, 'WHERE `token` = ') === false, 'logout: không còn xoá theo raw token');

$upw = $src('models/client/updatepassword.php');
ok(strpos($upw, 'auth_token_revoke_all($db, (int) $data_user[\'id\'])') !== false, 'đổi mật khẩu: thu hồi TẤT CẢ token của user');
ok(strpos($upw, "DELETE FROM `auth_tokens` WHERE `token` = ") === false, 'đổi mật khẩu: không còn xoá theo raw token');

$fg = $src('models/client/forgotpasword.php');
ok(strpos($fg, 'auth_token_revoke_all($db, (int) $getUser[\'id\'])') !== false, 'reset mật khẩu: thu hồi TẤT CẢ token của user');

$init = $src('libs/init.php');
ok(strpos($init, "require_once APP_ROOT . '/libs/auth_tokens.php'") !== false, 'init: nạp auth_tokens helper');
ok(strpos($init, 'auth_token_restore($db, $session)') !== false, 'init: restore từ remember cookie khi phiên trống');
ok(strpos($init, "\$_SESSION['auth_expires_at']") !== false, 'init: kiểm tra thời hạn phiên server-side');
ok(preg_match('/banned.*=== 1/s', $init) === 1, 'init: user bị khoá -> huỷ phiên ngay');

$sess = $src('classes/session.php');
ok(strpos($sess, "ini_set('session.gc_maxlifetime', (string) AUTH_SESSION_MAX_TTL)") !== false, 'session: gc_maxlifetime >= 604800');
ok(strpos($sess, "\$_SESSION['auth_expires_at'] = time() + AUTH_TOKEN_TTL_DEFAULT") !== false, 'session->send: gắn auth_expires_at mặc định 2 ngày');
ok(strpos($sess, 'session_regenerate_id(true)') !== false, 'session->send: regenerate id giữ nguyên');

// ---------- 9) Migration idempotent ----------
$mig = $src('database/migrations/20260814_superadmin_sessions.sql');
ok(stripos($mig, "information_schema.STATISTICS") !== false, 'migration: kiểm tra index qua information_schema');
ok(substr_count($mig, 'idx_token') >= 2 && substr_count($mig, 'idx_user_id') >= 2 && substr_count($mig, 'idx_expires_at') >= 2, 'migration: đủ 3 index token/user_id/expires_at (check + create)');
ok(substr_count($mig, 'PREPARE stc_stmt FROM') === 3 && substr_count($mig, 'EXECUTE stc_stmt;') === 3 && substr_count($mig, 'DEALLOCATE PREPARE stc_stmt;') === 3, 'migration: 3 khối PREPARE/EXECUTE/DEALLOCATE');
ok(stripos($mig, 'CREATE TABLE') === false, 'migration: không CREATE TABLE (idempotent)');
ok(stripos($mig, "DELETE FROM `auth_tokens` WHERE `expires_at` < NOW()") !== false, 'migration: dọn token hết hạn (an toàn chạy lại)');
ok(stripos($mig, "SET `level` = 'superadmin'") !== false, 'migration: giữ nguyên phần superadmin (Batch 2)');
ok(!preg_match('/password\s*=\s*\'[^\']*\'/i', $mig), 'migration: không credential/demo password');

// ---------- 10) Không còn nơi nào so sánh token với raw cookie ----------
foreach (['views/auth/logout.php', 'models/client/updatepassword.php'] as $f) {
    $c = $src($f);
    ok(strpos($c, 'Anti_xss($_COOKIE') === false && strpos($c, '(string) $_COOKIE') === false, "$f: không đọc raw cookie để query DB");
}

// ---------- Summary ----------
$_SESSION = [];
echo "----------------------------------------\n";
echo 'TOTAL: ' . ($RESULTS['pass'] + $RESULTS['fail']) . ' | PASS: ' . $RESULTS['pass'] . ' | FAIL: ' . $RESULTS['fail'] . "\n";
exit($RESULTS['fail'] === 0 ? 0 : 1);
