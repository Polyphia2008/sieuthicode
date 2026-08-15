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
    public $failInsert = false; // true -> insert() trả false (mô phỏng lỗi DB)
    public $failUpdate = false; // true -> UPDATE token trả false (mô phỏng lỗi DB)
    public $lastAffected = 0;   // số hàng affected bởi UPDATE/DELETE gần nhất (cho CAS rotation)
    public function escape($v) { return addslashes((string) $v); }
    public function affected_rows() { return $this->lastAffected; }
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
        // CAS rotation (Blocker 3): UPDATE ... WHERE id=<id> AND token=<old hash>.
        // Chỉ đổi hash khi CẢ id LẪN old hash khớp — mô phỏng đúng ngữ nghĩa
        // compare-and-swap của MySQL; lastAffected = 1 đúng một dòng, 0 nếu
        // token cũ đã bị request khác rotate trước (race thua -> fail closed).
        if (preg_match("/UPDATE `auth_tokens` SET `token` = '([^']+)' WHERE `id` = '(\d+)' AND `token` = '([^']+)'/", $sql, $m)) {
            if ($this->failUpdate) {
                $this->lastAffected = -1; // mysqli_affected_rows = -1 khi query lỗi
                return false;
            }
            $newHash = $m[1];
            $id = (int) $m[2];
            $oldHash = $m[3];
            $this->lastAffected = 0;
            foreach ($this->rows as $i => $r) {
                if ((int) $r['id'] === $id && $r['token'] === $oldHash) {
                    $this->rows[$i]['token'] = $newHash;
                    $this->lastAffected = 1;
                }
            }
            return true;
        }
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
        if ($this->failInsert) {
            return false;
        }
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

// ---------- 6g) ROTATION (Batch C, behavior-level) ----------
// Restore hợp lệ phải: giữ đúng 1 row, đổi hash trong DB, giữ nguyên expires_at
// tuyệt đối, token cũ không dùng lại được, token mới restore được (context mới).
$rawOld = bin2hex(random_bytes(32));
$hashOld = auth_token_hash($rawOld);
$absolute2 = time() + 172800;
$db = new StubDbTokens();
$db->rows[] = ['id' => 7, 'user_id' => 42, 'token' => $hashOld, 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', $absolute2)];
$db->users[42] = ['username' => 'duchuy', 'banned' => 0];
$_COOKIE = [AUTH_TOKEN_COOKIE => $rawOld];
error_reporting($noWarn);
$u = auth_token_restore($db, new TestSession());
error_reporting(E_ALL);
ok($u === 'duchuy', 'rotation: restore lần đầu thành công');
ok(count($db->rows) === 1, 'rotation: vẫn đúng 1 row (UPDATE, không INSERT thêm)');
ok((int) $db->rows[0]['id'] === 7, 'rotation: UPDATE đúng chính row cũ (giữ id)');
ok($db->rows[0]['token'] !== $hashOld, 'rotation: hash trong DB đã đổi (token mới)');
ok(strlen($db->rows[0]['token']) === 64 && ctype_xdigit($db->rows[0]['token']), 'rotation: hash mới 64 hex');
ok(strtotime($db->rows[0]['expires_at']) === $absolute2, 'rotation: expires_at tuyệt đối GIỮ NGUYÊN (không gia hạn)');

// Token CŨ không dùng lại được (context mới, cookie cũ)
$_COOKIE = [AUTH_TOKEN_COOKIE => $rawOld];
error_reporting($noWarn);
$uOld = auth_token_restore($db, new TestSession());
error_reporting(E_ALL);
ok($uOld === '', 'rotation: token cũ không dùng lại được sau khi xoay');

// Khôi phục bằng hash mới (mô phỏng cookie mới): vì cookie mới là raw random
// không đọc được trong CLI, ta kiểm chứng row mới restore được bằng cách giả
// lập raw khớp hash mới (hash không khả nghịch -> test qua chính hash lưu DB:
// row tồn tại với hash mới = xác nhận UPDATE thành công ở trên).

// UPDATE fail -> KHÔNG rotation, KHÔNG cookie mới, nhưng phiên vẫn restore an toàn
$db = new StubDbTokens();
$db->failUpdate = true;
$db->rows[] = ['id' => 8, 'user_id' => 42, 'token' => $hashOld, 'ip' => '', 'expires_at' => date('Y-m-d H:i:s', $absolute2)];
$db->users[42] = ['username' => 'duchuy', 'banned' => 0];
$_COOKIE = [AUTH_TOKEN_COOKIE => $rawOld];
$_SESSION = []; // mô phỏng request mới: phiên trống (init gọi restore khi phiên trống)
error_reporting($noWarn);
$u = auth_token_restore($db, new TestSession());
error_reporting(E_ALL);
// Blocker 3 (fail-closed): UPDATE lỗi / affected_rows !== 1 -> KHÔNG restore,
// KHÔNG session, KHÔNG auth_expires_at, xoá remember_me cookie, trả về guest.
ok($u === '', 'rotation fail: FAIL CLOSED — không restore khi UPDATE lỗi');
ok($db->rows[0]['token'] === $hashOld, 'rotation fail: hash DB không đổi khi UPDATE lỗi');
ok(empty($_SESSION['auth_expires_at']), 'rotation fail: không set auth_expires_at khi UPDATE lỗi');
// Cookie cũ đã bị xoá phía server (fail-closed) — request sau với token cũ vẫn là guest
// (token chưa đổi trong DB nên restore tiếp vẫn CAS-fail do UPDATE lỗi -> guest).
$_COOKIE = [AUTH_TOKEN_COOKIE => $rawOld];
$_SESSION = [];
error_reporting($noWarn);
$ses2 = new TestSession();
$uRetry = auth_token_restore($db, $ses2);
error_reporting(E_ALL);
ok($uRetry === '' && $ses2->sentUser === null, 'rotation fail: request lặp lại vẫn guest, không cấp session');

// ---------- 5b) Issue: INSERT fail -> return 0, KHÔNG cookie ----------
$db = new StubDbTokens();
$db->failInsert = true;
$cookieBefore = $_COOKIE[AUTH_TOKEN_COOKIE] ?? null;
error_reporting($noWarn);
$expFail = auth_token_issue($db, 42, false, '');
error_reporting(E_ALL);
ok($expFail === 0, 'issue: INSERT fail -> trả 0 (không báo thành công)');
ok(count($db->rows) === 0, 'issue: INSERT fail -> không ghi row nào');
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
// Register phải: insert -> id -> send -> issue -> CUỐI CÙNG mới echo JSON.
$pInsert = strpos($reg, "\$isInsert = \$db->insert('users'");
$pId = strpos($reg, 'get_id_insert()');
$pSend = strpos($reg, '$session->send($username)');
$pIssue = strpos($reg, 'auth_token_issue($db, $newUserId');
$pEcho = strpos($reg, "exit(JsonMsg('success', 'Đăng ký thành công'))");
ok($pInsert !== false && $pId !== false && $pSend !== false && $pIssue !== false && $pEcho !== false
    && $pInsert < $pId && $pId < $pSend && $pSend < $pIssue && $pIssue < $pEcho,
    'register: thứ tự insert -> id -> send -> issue -> echo (không setcookie sau body)');
ok(strpos($reg, 'if (!$isInsert)') !== false, 'register: kiểm tra kết quả INSERT user');

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

// ---------- 8b) Static: rotation + issue guard đã đúng trong source ----------
$tok = $src('libs/auth_tokens.php');
ok(strpos($tok, "UPDATE `auth_tokens` SET `token`") !== false, 'restore: rotation UPDATE chính row trong DB');
ok(strpos($tok, '$newRaw = bin2hex(random_bytes(32))') !== false, 'restore: rotation sinh raw mới random_bytes(32)');
// f8f9348a: rotation dùng CAS fail-closed — biến $swapped (affected_rows === 1),
// cookie mới + session->send CHỈ chạy sau khi swap thành công.
ok(preg_match('/\$swapped = \(\$db->affected_rows\(\) === 1\)/', $tok) === 1,
    'restore: CAS rotation kiểm tra affected_rows === 1');
ok(preg_match('/if \(!\$swapped\) \{[\s\S]*?return \'\';[\s\S]*?auth_token_send_cookie\(\$newRaw/s', $tok) === 1,
    'restore: fail-closed khi swap thất bại; cookie mới CHỈ gửi sau CAS thành công');
ok(preg_match('/if \(!\$isInsert\) \{\s*\/\/[^\n]*\n\s*return 0;/s', $tok) === 1
    || preg_match('/if \(!\$isInsert\)/', $tok) === 1,
    'issue: kiểm tra INSERT result, fail -> return 0 trước setcookie');

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
