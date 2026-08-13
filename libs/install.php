<?php
/**
 * Trình cài đặt Siêu Thị Code — thư viện lõi.
 *
 * Không chứa credential, không in mật khẩu, không hotlink tài nguyên ngoài.
 * Mọi hàm nhận credential dạng giá trị (không ghép SQL).
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

/** Đường dẫn file marker cài đặt hoàn tất (runtime, nằm trong .gitignore). */
function installer_lock_path()
{
    return APP_ROOT . '/storage/installed.lock';
}

/** Đường dẫn file cấu hình DB runtime (runtime, nằm trong .gitignore). */
function installer_config_path()
{
    return APP_ROOT . '/config.local.php';
}

/** Đường dẫn file lock chống cài đặt đồng thời. */
function installer_mutex_path()
{
    return APP_ROOT . '/storage/.installing.lock';
}

/**
 * Kiểm tra website đã được cài đặt hay chưa.
 *
 * Quy tắc:
 *  - Nếu đã có installed.lock => đã cài. Tuyệt đối không mở lại installer
 *    kể cả khi DB tạm mất kết nối.
 *  - Nếu chưa có lock nhưng đã có cấu hình DB (config.local.php hoặc env) và
 *    kết nối được và có schema hợp lệ (users + options) => website cũ đã cài,
 *    không redirect sang installer, không import lại, không đổi dữ liệu.
 *  - Ngược lại => chưa cài.
 */
function isApplicationInstalled()
{
    if (is_file(installer_lock_path())) {
        return true;
    }

    // Tương thích website cũ đã cài nhưng chưa có lock.
    $cfg = installer_existing_db_config();
    if ($cfg === null) {
        return false;
    }
    $mysqli = installer_try_connect($cfg, $err);
    if (!$mysqli) {
        // Cấu hình tồn tại nhưng không kết nối được: coi như CHƯA có schema hợp lệ,
        // nhưng vẫn không được tự ý mở lại installer nếu lock đã có (đã xử lý ở trên).
        return false;
    }
    $hasSchema = installer_has_base_schema($mysqli);
    if ($hasSchema) {
        // Tạo marker an toàn (không bắt buộc, không làm hỏng website nếu thất bại).
        @installer_write_lock($mysqli);
        mysqli_close($mysqli);
        return true;
    }
    mysqli_close($mysqli);
    return false;
}

/**
 * Lấy cấu hình DB hiện có (nếu có): ưu tiên config.local.php, sau đó biến môi trường.
 * Trả về null nếu không có cấu hình nào được thiết lập rõ ràng.
 */
function installer_existing_db_config()
{
    $cfgFile = installer_config_path();
    if (is_file($cfgFile)) {
        $data = @include $cfgFile;
        if (is_array($data) && isset($data['db']) && is_array($data['db'])) {
            $db = $data['db'];
            if (!empty($db['database'])) {
                return [
                    'host' => (string) ($db['host'] ?? 'localhost'),
                    'port' => (int) ($db['port'] ?? 3306),
                    'username' => (string) ($db['username'] ?? ''),
                    'password' => (string) ($db['password'] ?? ''),
                    'database' => (string) ($db['database'] ?? ''),
                ];
            }
        }
    }

    $envDb = getenv('DB_DATABASE');
    if ($envDb !== false && $envDb !== '') {
        return [
            'host' => (string) (getenv('DB_HOST') ?: 'localhost'),
            'port' => (int) (getenv('DB_PORT') ?: 3306),
            'username' => (string) (getenv('DB_USERNAME') ?: ''),
            'password' => (string) (getenv('DB_PASSWORD') ?: ''),
            'database' => (string) $envDb,
        ];
    }
    return null;
}

/**
 * Thử kết nối MySQL bằng mysqli với timeout hợp lý.
 * Trả về mysqli khi thành công (đã select db + utf8mb4), false khi thất bại.
 * Không đưa password / host / user vào thông báo lỗi.
 */
function installer_try_connect(array $cfg, &$err = '')
{
    $err = '';
    if (!extension_loaded('mysqli')) {
        $err = 'Hosting chưa bật PHP extension mysqli. Hãy bật mysqli trong cPanel.';
        return false;
    }
    mysqli_report(MYSQLI_REPORT_OFF);
    $host = (string) ($cfg['host'] ?? 'localhost');
    $port = (int) ($cfg['port'] ?? 3306);
    $user = (string) ($cfg['username'] ?? '');
    $pass = (string) ($cfg['password'] ?? '');
    $db = (string) ($cfg['database'] ?? '');

    // Timeout hợp lý để không treo trình cài đặt.
    $mysqli = mysqli_init();
    if (!$mysqli) {
        $err = 'Không thể khởi tạo kết nối MySQL.';
        return false;
    }
    mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    $ok = @mysqli_real_connect($mysqli, $host, $user, $pass, null, $port);
    if (!$ok) {
        $err = 'Không thể kết nối tới máy chủ MySQL. Hãy kiểm tra host, port, username và password.';
        return false;
    }
    if ($db === '' || !installer_valid_db_name($db)) {
        mysqli_close($mysqli);
        $err = 'Tên database không hợp lệ.';
        return false;
    }
    if (!@mysqli_select_db($mysqli, $db)) {
        mysqli_close($mysqli);
        $err = 'Không chọn được database. Hãy chắc rằng database tồn tại và user đã được cấp quyền.';
        return false;
    }
    @mysqli_set_charset($mysqli, 'utf8mb4');
    return $mysqli;
}

/** Tên database hợp lệ — tương thích tên cPanel có dấu gạch dưới / chữ / số / gạch ngang. */
function installer_valid_db_name($name)
{
    return is_string($name) && $name !== '' && strlen($name) <= 64
        && preg_match('/^[A-Za-z0-9_$-]+$/', $name) === 1;
}

/** Kiểm tra schema nền (users + options) — dấu hiệu website cũ đã cài. */
function installer_has_base_schema($mysqli)
{
    return installer_table_exists($mysqli, 'users') && installer_table_exists($mysqli, 'options');
}

/** Kiểm tra một bảng có tồn tại trong database hiện tại không. */
function installer_table_exists($mysqli, $table)
{
    $table = preg_replace('/[^A-Za-z0-9_]/', '', (string) $table);
    $res = @mysqli_query($mysqli, "SHOW TABLES LIKE '" . mysqli_real_escape_string($mysqli, $table) . "'");
    $exists = $res && mysqli_num_rows($res) > 0;
    if ($res) {
        mysqli_free_result($res);
    }
    return $exists;
}

/** Lấy danh sách tên bảng hiện có trong database đã chọn. */
function installer_list_tables($mysqli)
{
    $tables = [];
    $res = @mysqli_query($mysqli, 'SHOW TABLES');
    if ($res) {
        while ($row = mysqli_fetch_row($res)) {
            $tables[] = (string) $row[0];
        }
        mysqli_free_result($res);
    }
    return $tables;
}

/**
 * Kiểm tra database user có đủ quyền tạo bảng / insert / alter.
 * Dùng một bảng thử tạm thời rồi xoá ngay (tên có prefix riêng để không đụng dữ liệu).
 */
function installer_check_privileges($mysqli, &$err = '')
{
    $err = '';
    $tmp = 'stc_install_probe_' . substr(bin2hex(random_bytes(6)), 0, 12);
    $ok = @mysqli_query($mysqli, "CREATE TABLE `$tmp` (`id` INT NOT NULL PRIMARY KEY, `v` VARCHAR(10) DEFAULT NULL) ENGINE=InnoDB");
    if (!$ok) {
        $err = 'Database user chưa có quyền CREATE TABLE. Hãy cấp ALL PRIVILEGES trong cPanel.';
        return false;
    }
    $ok = $ok && @mysqli_query($mysqli, "INSERT INTO `$tmp` (`id`,`v`) VALUES (1,'a')");
    $ok = $ok && @mysqli_query($mysqli, "ALTER TABLE `$tmp` ADD COLUMN `v2` VARCHAR(10) DEFAULT NULL");
    @mysqli_query($mysqli, "DROP TABLE IF EXISTS `$tmp`");
    if (!$ok) {
        $err = 'Database user chưa đủ quyền (cần CREATE/INSERT/ALTER). Hãy cấp ALL PRIVILEGES trong cPanel.';
        return false;
    }
    return true;
}

/**
 * Bộ tách câu lệnh SQL an toàn cho file phpMyAdmin.
 * Xử lý: comment (--, #, block), SET, START TRANSACTION/COMMIT, chuỗi có dấu
 * chấm phẩy hoặc escape, nhiều câu lệnh. KHÔNG dùng explode(';').
 *
 * Trả về mảng các câu lệnh (đã trim, bỏ rỗng và comment-only).
 */
function installer_split_sql($sql)
{
    $statements = [];
    $buf = '';
    $len = strlen($sql);
    $i = 0;
    $inSingle = false;
    $inDouble = false;
    $inBacktick = false;
    $inLineComment = false;
    $inBlockComment = false;

    while ($i < $len) {
        $ch = $sql[$i];
        $next = $i + 1 < $len ? $sql[$i + 1] : '';

        if ($inLineComment) {
            if ($ch === "\n") {
                $inLineComment = false;
                $buf .= $ch;
            }
            $i++;
            continue;
        }
        if ($inBlockComment) {
            if ($ch === '*' && $next === '/') {
                $inBlockComment = false;
                $i += 2;
                continue;
            }
            $i++;
            continue;
        }
        if ($inSingle) {
            $buf .= $ch;
            if ($ch === '\\') {
                if ($next !== '') {
                    $buf .= $next;
                    $i += 2;
                    continue;
                }
            } elseif ($ch === "'") {
                // '' là escape của nháy đơn trong SQL.
                if ($next === "'") {
                    $buf .= $next;
                    $i += 2;
                    continue;
                }
                $inSingle = false;
            }
            $i++;
            continue;
        }
        if ($inDouble) {
            $buf .= $ch;
            if ($ch === '\\') {
                if ($next !== '') {
                    $buf .= $next;
                    $i += 2;
                    continue;
                }
            } elseif ($ch === '"') {
                $inDouble = false;
            }
            $i++;
            continue;
        }
        if ($inBacktick) {
            $buf .= $ch;
            if ($ch === '`') {
                $inBacktick = false;
            }
            $i++;
            continue;
        }

        // Không ở trong chuỗi/comment.
        if ($ch === '-' && $next === '-' && ($i + 2 >= $len || $sql[$i + 2] === ' ' || $sql[$i + 2] === "\t")) {
            $inLineComment = true;
            $i += 2;
            continue;
        }
        if ($ch === '#') {
            $inLineComment = true;
            $i++;
            continue;
        }
        if ($ch === '/' && $next === '*') {
            $inBlockComment = true;
            $i += 2;
            continue;
        }
        if ($ch === "'") {
            $inSingle = true;
            $buf .= $ch;
            $i++;
            continue;
        }
        if ($ch === '"') {
            $inDouble = true;
            $buf .= $ch;
            $i++;
            continue;
        }
        if ($ch === '`') {
            $inBacktick = true;
            $buf .= $ch;
            $i++;
            continue;
        }
        if ($ch === ';') {
            $stmt = trim($buf);
            if ($stmt !== '') {
                $statements[] = $stmt;
            }
            $buf = '';
            $i++;
            continue;
        }
        $buf .= $ch;
        $i++;
    }
    $tail = trim($buf);
    if ($tail !== '') {
        $statements[] = $tail;
    }
    return $statements;
}

/**
 * Import một file SQL (nhiều câu lệnh) vào kết nối đã chọn database.
 * Trả về ['ok'=>bool,'created'=>[tên bảng mới tạo], 'error'=>string].
 *
 * Lưu ý: MySQL DDL tự động commit, không thể dựa vào transaction để rollback.
 * Hàm này ghi nhận các bảng do chính lần import này tạo ra để caller có thể
 * cleanup chọn lọc khi thất bại (chỉ bảng mới, tuyệt đối không đụng bảng cũ).
 */
function installer_import_sql($mysqli, $file, array &$createdTables, &$err = '')
{
    $err = '';
    if (!is_file($file) || !is_readable($file)) {
        $err = 'Không đọc được file SQL: ' . basename($file);
        return false;
    }
    $before = installer_list_tables($mysqli);
    $sql = file_get_contents($file);
    if ($sql === false) {
        $err = 'Không đọc được file SQL: ' . basename($file);
        return false;
    }
    // Bỏ BOM nếu có.
    $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);
    $statements = installer_split_sql($sql);
    foreach ($statements as $stmt) {
        if ($stmt === '') {
            continue;
        }
        if (!@mysqli_query($mysqli, $stmt)) {
            $err = 'Lỗi khi import ' . basename($file) . ': ' . mysqli_error($mysqli);
            // Thu thập bảng mới được tạo trong lần import này (kể cả khi lỗi giữa chừng).
            $after = installer_list_tables($mysqli);
            foreach (array_diff($after, $before) as $t) {
                $createdTables[$t] = true;
            }
            return false;
        }
        // Dọn kết quả (một số câu SELECT/SHOW có result set).
        while (mysqli_more_results($mysqli)) {
            mysqli_next_result($mysqli);
            $rs = mysqli_store_result($mysqli);
            if ($rs instanceof mysqli_result) {
                mysqli_free_result($rs);
            }
        }
    }
    $after = installer_list_tables($mysqli);
    foreach (array_diff($after, $before) as $t) {
        $createdTables[$t] = true;
    }
    return true;
}

/**
 * Cleanup chọn lọc: chỉ DROP các bảng do chính lần cài đặt này tạo ra.
 * Tuyệt đối không đụng bảng đã tồn tại trước khi installer chạy.
 */
function installer_rollback_created($mysqli, array $createdTables)
{
    if (empty($createdTables)) {
        return;
    }
    @mysqli_query($mysqli, 'SET FOREIGN_KEY_CHECKS=0');
    foreach (array_keys($createdTables) as $t) {
        $safe = preg_replace('/[^A-Za-z0-9_]/', '', $t);
        if ($safe !== '') {
            @mysqli_query($mysqli, "DROP TABLE IF EXISTS `$safe`");
        }
    }
    @mysqli_query($mysqli, 'SET FOREIGN_KEY_CHECKS=1');
}

/** Sinh CSRF token cho installer session. */
function installer_csrf_token()
{
    if (empty($_SESSION['stc_install_csrf'])) {
        $_SESSION['stc_install_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['stc_install_csrf'];
}

/** So sánh CSRF an toàn bằng hash_equals. */
function installer_csrf_ok($token)
{
    return !empty($_SESSION['stc_install_csrf'])
        && is_string($token)
        && hash_equals((string) $_SESSION['stc_install_csrf'], (string) $token);
}

/**
 * Rate limit đơn giản theo session cho các thao tác nhạy cảm (test DB / install).
 * Trả về true nếu còn được phép, false nếu vượt giới hạn.
 */
function installer_rate_ok($action, $maxAttempts, $windowSeconds)
{
    $now = time();
    $key = 'stc_install_rl_' . $action;
    if (empty($_SESSION[$key]) || !is_array($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }
    // Loại bỏ các lần thử quá cửa sổ.
    $_SESSION[$key] = array_values(array_filter($_SESSION[$key], function ($t) use ($now, $windowSeconds) {
        return ($now - (int) $t) < $windowSeconds;
    }));
    if (count($_SESSION[$key]) >= $maxAttempts) {
        return false;
    }
    $_SESSION[$key][] = $now;
    return true;
}

/**
 * Ghi config.local.php an toàn (var_export + temp file + LOCK_EX + atomic rename).
 */
function installer_write_config(array $cfg, &$err = '')
{
    $err = '';
    $data = [
        'db' => [
            'host' => (string) $cfg['host'],
            'port' => (int) $cfg['port'],
            'username' => (string) $cfg['username'],
            'password' => (string) $cfg['password'],
            'database' => (string) $cfg['database'],
        ],
    ];
    $content = "<?php\n/**\n * Cấu hình database — được tạo tự động bởi trình cài đặt.\n * KHÔNG commit file này. File này chứa thông tin nhạy cảm.\n */\n\nreturn " . var_export($data, true) . ";\n";

    $target = installer_config_path();
    $tmp = $target . '.tmp.' . bin2hex(random_bytes(6));
    if (@file_put_contents($tmp, $content, LOCK_EX) === false) {
        $err = 'Không ghi được file cấu hình. Hãy kiểm tra quyền ghi thư mục gốc của website.';
        return false;
    }
    @chmod($tmp, 0600);
    if (!@rename($tmp, $target)) {
        @unlink($tmp);
        $err = 'Không thể ghi cấu hình (atomic rename thất bại).';
        return false;
    }
    @chmod($target, 0600);
    return true;
}

/** Ghi installed.lock (chỉ metadata, không chứa secret). */
function installer_write_lock($mysqli = null)
{
    $dir = dirname(installer_lock_path());
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $payload = [
        'installed' => true,
        'version' => defined('APP_VERSION') ? APP_VERSION : '1.0.0',
        'installed_at' => date('c'),
    ];
    $tmp = installer_lock_path() . '.tmp.' . bin2hex(random_bytes(6));
    if (@file_put_contents($tmp, json_encode($payload, JSON_PRETTY_PRINT), LOCK_EX) === false) {
        @unlink($tmp);
        return false;
    }
    @chmod($tmp, 0644);
    if (!@rename($tmp, installer_lock_path())) {
        @unlink($tmp);
        return false;
    }
    return true;
}

/** Trả JSON response cho API installer và dừng. Không bao giờ echo credential. */
function installer_json($ok, $message, $extra = [], $httpCode = 200)
{
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('X-Content-Type-Options: nosniff');
    $payload = array_merge(['status' => $ok ? 'success' : 'error', 'msg' => $message], $extra);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Escape an toàn khi render HTML. */
function installer_e($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Security headers cho trang installer. */
function installer_security_headers()
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header("Content-Security-Policy: frame-ancestors 'none'");
    header('Referrer-Policy: no-referrer');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
}

/** Khởi động session an toàn cho installer (độc lập với session app). */
function installer_session_start()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
    session_name('stc_installer');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => $secure,
    ]);
    @session_start();
}
