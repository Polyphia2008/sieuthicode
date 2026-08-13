<?php
/**
 * API backend của trình cài đặt (chỉ POST, có CSRF + rate limit + mutex lock).
 *
 * Hành động:
 *  - test_db     : kiểm tra kết nối + quyền, lưu cấu hình DB vào session (bước 1).
 *  - save_admin  : validate tài khoản admin, lưu vào session (bước 2).
 *  - install     : cài đặt đầy đủ (bước 3).
 *
 * Không bao giờ trả password trong response. Không log password.
 * CSRF thất bại trả HTTP 419 (Authentication Timeout) bằng status line đầy đủ.
 */

require_once __DIR__ . '/bootstrap.php';

installer_guard_not_installed(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    installer_json(false, 'Phương thức không được hỗ trợ.', [], 405);
}

// CSRF bắt buộc cho mọi hành động thay đổi trạng thái. Trả đúng HTTP 419.
$token = $_POST['csrf_token'] ?? '';
if (!installer_csrf_ok($token)) {
    installer_json(false, 'Phiên làm việc không hợp lệ (CSRF). Vui lòng tải lại trang.', [], 419);
}

$action = (string) ($_POST['action'] ?? '');

switch ($action) {
    case 'test_db':
        installer_handle_test_db();
        break;
    case 'save_admin':
        installer_handle_save_admin();
        break;
    case 'install':
        installer_handle_install();
        break;
    default:
        installer_json(false, 'Hành động không hợp lệ.', [], 400);
}

/* ------------------------------------------------------------------------ */

function installer_handle_test_db()
{
    if (!installer_rate_ok('test_db', 10, 300)) {
        installer_json(false, 'Bạn đã thử quá nhiều lần. Vui lòng chờ vài phút rồi thử lại.', [], 429);
    }

    $cfg = installer_read_db_input();
    if (is_string($cfg)) {
        installer_json(false, $cfg, [], 422);
    }

    $mysqli = installer_try_connect($cfg, $err);
    if (!$mysqli) {
        installer_json(false, $err, [], 422);
    }

    // Chỉ cho phép cài vào database trống (hoặc chỉ có bảng do lần cài dở trước đó tạo).
    $tables = installer_list_tables($mysqli);
    $foreign = installer_foreign_tables($tables);
    if (!empty($foreign)) {
        mysqli_close($mysqli);
        installer_json(
            false,
            'Database này đã có dữ liệu. Để bảo vệ dữ liệu hiện có, trình cài đặt chỉ cài mới vào database trống. '
            . 'Nếu đây là website cũ, hãy giữ nguyên cấu hình hiện tại thay vì cài lại.',
            [],
            409
        );
    }

    if (!installer_check_privileges($mysqli, $err)) {
        mysqli_close($mysqli);
        installer_json(false, $err, [], 422);
    }
    mysqli_close($mysqli);

    // Lưu cấu hình DB vào session (không ghi ra file cho tới bước install).
    $_SESSION['stc_install_db'] = [
        'host' => $cfg['host'],
        'port' => $cfg['port'],
        'username' => $cfg['username'],
        'password' => $cfg['password'],
        'database' => $cfg['database'],
    ];
    installer_json(true, 'Kết nối cơ sở dữ liệu thành công.');
}

function installer_handle_save_admin()
{
    if (!installer_rate_ok('save_admin', 15, 300)) {
        installer_json(false, 'Bạn đã thử quá nhiều lần. Vui lòng chờ rồi thử lại.', [], 429);
    }

    $name = trim((string) ($_POST['admin_name'] ?? ''));
    $username = trim((string) ($_POST['admin_username'] ?? ''));
    $email = trim((string) ($_POST['admin_email'] ?? ''));
    $password = (string) ($_POST['admin_password'] ?? '');
    $confirm = (string) ($_POST['admin_password_confirm'] ?? '');

    // Không tin role từ client — installer luôn tạo level = admin.
    if ($name === '' || mb_strlen($name) > 100) {
        installer_json(false, 'Vui lòng nhập họ và tên hợp lệ.', [], 422);
    }
    if (!preg_match('/^[A-Za-z0-9_.-]{3,25}$/', $username)) {
        installer_json(false, 'Tên đăng nhập phải dài 3–25 ký tự, chỉ gồm chữ, số, dấu chấm, gạch dưới, gạch ngang.', [], 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        installer_json(false, 'Email không hợp lệ.', [], 422);
    }
    if (strlen($password) < 8) {
        installer_json(false, 'Mật khẩu phải có tối thiểu 8 ký tự.', [], 422);
    }
    if (!hash_equals($password, $confirm)) {
        installer_json(false, 'Mật khẩu xác nhận không khớp.', [], 422);
    }

    $_SESSION['stc_install_admin'] = [
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => $password,
    ];
    installer_json(true, 'Thông tin quản trị hợp lệ.');
}

function installer_handle_install()
{
    if (!installer_rate_ok('install', 6, 600)) {
        installer_json(false, 'Quá nhiều lần cài đặt. Vui lòng chờ rồi thử lại.', [], 429);
    }

    $db = $_SESSION['stc_install_db'] ?? null;
    $admin = $_SESSION['stc_install_admin'] ?? null;
    if (!is_array($db) || empty($db['database']) || !is_array($admin) || empty($admin['username'])) {
        installer_json(false, 'Thiếu dữ liệu cài đặt. Vui lòng hoàn thành bước 1 và bước 2.', [], 422);
    }

    // Mutex lock chống hai request cài đặt đồng thời.
    $dir = dirname(installer_mutex_path());
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $lock = @fopen(installer_mutex_path(), 'c');
    if (!$lock) {
        // Không tạo được file lock — gần như chắc chắn là quyền thư mục storage.
        installer_json(false, 'Không ghi được thư mục storage. Hãy cấp quyền ghi cho thư mục storage trên hosting.', [], 500);
    }
    if (!flock($lock, LOCK_EX | LOCK_NB)) {
        fclose($lock);
        installer_json(false, 'Một tiến trình cài đặt khác đang chạy. Vui lòng chờ.', [], 409);
    }

    try {
        // Re-check sau khi đã giữ lock: có thể tiến trình kia vừa cài xong.
        if (installer_state_fresh() === 'installed') {
            installer_json(false, 'Website vừa được cài đặt xong. Không cần cài lại.', [], 409);
        }

        // Kết nối lại DB lần cuối.
        $mysqli = installer_try_connect($db, $err);
        if (!$mysqli) {
            installer_json(false, $err, [], 422);
        }

        /*
         * Recovery journal (crash-safe): nếu lần trước bị kill giữa import,
         * journal ghi nhận nonce + fingerprint đích (hash host/port/user/db,
         * KHÔNG password) + initial_tables + planned_tables (allowlist).
         * Retry CHỈ cleanup khi fingerprint khớp, và chỉ DROP:
         *      current_tables ∩ planned_tables − initial_tables
         * Journal hỏng / sai version / sai fingerprint => KHÔNG DROP gì,
         * dừng với thông báo an toàn. Không bao giờ "current − initial" tùy ý.
         */
        $recovery = installer_journal_recover($mysqli, $db);
        if (empty($recovery['ok'])) {
            mysqli_close($mysqli);
            installer_json(false, $recovery['message'], [], 409);
        }

        // Sau recovery, database PHẢI trống (hoặc chỉ còn bảng ngoài allowlist
        // => foreign, từ chối cài). Đây là điều kiện bắt buộc trước khi ghi journal mới.
        $tables = installer_list_tables($mysqli);
        if (!empty(installer_foreign_tables($tables))) {
            mysqli_close($mysqli);
            installer_json(false, 'Database đã có dữ liệu, không thể cài mới để tránh mất dữ liệu.', [], 409);
        }

        // Ghi journal TRƯỚC khi chạy DDL đầu tiên: initial_tables = snapshot DB
        // (đã xác minh trống ở trên), planned_tables = allowlist phân tích từ
        // các file SQL. Nếu tiến trình bị SIGKILL ngay sau đó, lần chạy tiếp
        // theo vẫn nhận diện được và cleanup đúng phần đã tạo.
        if (!installer_journal_start($db, $tables, installer_planned_tables())) {
            mysqli_close($mysqli);
            installer_json(false, 'Không ghi được journal phục hồi trong thư mục storage. Kiểm tra quyền ghi.', [], 500);
        }

        $created = [];
        // 1) Import base SQL.
        if (!installer_import_sql($mysqli, APP_ROOT . '/shoprobloxv4 (2).sql', $created, $err)) {
            installer_journal_add_created($created);
            installer_rollback_created($mysqli, $created);
            installer_journal_clear();
            @unlink(installer_config_path()); // không để config dở
            mysqli_close($mysqli);
            installer_json(false, 'Import database gốc thất bại. ' . $err, [], 500);
        }
        // 2) Import migration chat-box.
        if (!installer_import_sql($mysqli, APP_ROOT . '/database/migrations/20260812_chat_box.sql', $created, $err)) {
            installer_journal_add_created($created);
            installer_rollback_created($mysqli, $created);
            installer_journal_clear();
            @unlink(installer_config_path()); // không để config dở
            mysqli_close($mysqli);
            installer_json(false, 'Import migration chat thất bại. ' . $err, [], 500);
        }
        // Cập nhật journal với danh sách bảng đã tạo (phòng trường hợp bị kill
        // sau đây — ví dụ ngay trước bước tạo admin).
        installer_journal_add_created($created);

        // 3) Tạo/cập nhật admin.
        if (!installer_upsert_admin($mysqli, $admin, $err)) {
            installer_rollback_created($mysqli, $created);
            installer_journal_clear();
            @unlink(installer_config_path()); // không để config dở
            mysqli_close($mysqli);
            installer_json(false, 'Không tạo được tài khoản quản trị. ' . $err, [], 500);
        }

        // 4) Xác minh FULL schema (tất cả bảng bắt buộc của phiên bản hiện tại).
        foreach (installer_full_tables() as $t) {
            if (!installer_table_exists($mysqli, $t)) {
                installer_rollback_created($mysqli, $created);
                installer_journal_clear();
                @unlink(installer_config_path()); // không để config dở
                mysqli_close($mysqli);
                installer_json(false, 'Thiếu bảng bắt buộc sau khi import: ' . $t, [], 500);
            }
        }

        // 5) Xác minh admin đăng nhập được theo logic hiện tại (SHA1).
        if (!installer_verify_admin_login($mysqli, $admin)) {
            installer_rollback_created($mysqli, $created);
            installer_journal_clear();
            @unlink(installer_config_path()); // không để config dở
            mysqli_close($mysqli);
            installer_json(false, 'Không xác minh được tài khoản quản trị sau khi tạo.', [], 500);
        }

        // 6) Ghi cấu hình database (atomic). Nếu thất bại thì rollback DB, không báo thành công.
        if (!installer_write_config($db, $err)) {
            installer_rollback_created($mysqli, $created);
            installer_journal_clear();
            @unlink(installer_config_path()); // không để config dở
            mysqli_close($mysqli);
            installer_json(false, $err, [], 500);
        }

        // 7) Tạo marker cài đặt hoàn tất — bước cuối cùng. Nếu thất bại thì
        // rollback DB + xoá config, KHÔNG để lại installed.lock dở dang.
        if (!installer_write_lock($mysqli)) {
            installer_rollback_created($mysqli, $created);
            @unlink(installer_config_path());
            installer_journal_clear();
            mysqli_close($mysqli);
            installer_json(false, 'Không tạo được dấu hiệu cài đặt (installed.lock). Kiểm tra quyền thư mục storage.', [], 500);
        }

        // Cài đặt thành công: xoá journal phục hồi.
        installer_journal_clear();
        mysqli_close($mysqli);

        // Làm mới phiên cài đặt để tránh dùng lại dữ liệu nhạy cảm trong session.
        unset($_SESSION['stc_install_db'], $_SESSION['stc_install_admin']);
        session_regenerate_id(true);

        installer_json(true, 'Cài đặt thành công.', ['redirect' => '/login']);
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

/**
 * Tính lại trạng thái cài đặt, bỏ qua cache trong-request.
 * Dùng sau khi đã giữ mutex để tránh quyết định dựa trên cache cũ.
 */
function installer_state_fresh()
{
    // isApplicationInstalled() cache trong-request; ở đây mutex đảm bảo chỉ một
    // tiến trình cài, và trạng thái có thể đã thay đổi từ request trước. Gọi
    // trực tiếp các kiểm tra cốt lõi thay vì cache.
    if (is_file(installer_lock_path())) {
        return 'installed';
    }
    return installer_state();
}

/* ------------------------------------------------------------------------ */

/** Đọc + validate input DB từ POST. Trả về array config hoặc chuỗi lỗi. */
function installer_read_db_input()
{
    $host = trim((string) ($_POST['db_host'] ?? 'localhost'));
    $port = (int) ($_POST['db_port'] ?? 3306);
    $database = trim((string) ($_POST['db_name'] ?? ''));
    $username = trim((string) ($_POST['db_user'] ?? ''));
    $password = (string) ($_POST['db_pass'] ?? '');

    if ($host === '') {
        $host = 'localhost';
    }
    if (strlen($host) > 255 || !preg_match('/^[A-Za-z0-9._\-\[\]:]+$/', $host)) {
        return 'Database host không hợp lệ.';
    }
    if ($port < 1 || $port > 65535) {
        return 'Database port không hợp lệ.';
    }
    if (!installer_valid_db_name($database)) {
        return 'Database name không hợp lệ (chỉ gồm chữ, số, gạch dưới).';
    }
    if ($username === '' || strlen($username) > 64) {
        return 'Database username không hợp lệ.';
    }
    // Password có thể rỗng trên một số hosting local; không giới hạn ký tự.

    return [
        'host' => $host,
        'port' => $port,
        'username' => $username,
        'password' => $password,
        'database' => $database,
    ];
}

/**
 * Bảng "ngoại lai": bảng không do installer tạo (không mang prefix dự phòng).
 * Nếu database có bất kỳ bảng nào như vậy thì coi là đã có dữ liệu.
 */
function installer_foreign_tables(array $tables)
{
    $foreign = [];
    foreach ($tables as $t) {
        if (strpos($t, 'stc_install_probe_') === 0) {
            continue;
        }
        $foreign[] = $t;
    }
    return $foreign;
}

/**
 * Tạo mới hoặc cập nhật tài khoản admin. Dùng prepared statement.
 * Tương thích login hiện tại (SHA1). Không ghi plaintext password đi đâu.
 *
 * Base SQL có bootstrap: username=admin, password='!' (PLAINTEXT trong dump).
 * Ta cập nhật row đó (nếu có), nếu không thì tạo admin mới. Không tin role từ client.
 */
function installer_upsert_admin($mysqli, array $admin, &$err = '')
{
    $err = '';
    $username = (string) $admin['username'];
    $name = (string) $admin['name'];
    $email = (string) $admin['email'];
    $hash = sha1((string) $admin['password']); // tương thích hệ thống login hiện tại
    $token = bin2hex(random_bytes(21)); // token ngẫu nhiên an toàn (42 hex chars)
    $createDate = date('Y-m-d H:i:s');

    // Tìm row bootstrap admin. Base SQL gốc lưu password PLAINTEXT '!' (không phải
    // sha1), nên chấp nhận cả hai dạng để tương thích mọi phiên bản dump.
    $bootstrapId = null;
    $stmt = mysqli_prepare($mysqli, "SELECT `id`, `password` FROM `users` WHERE `username` = 'admin' LIMIT 1");
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if ($row && ($row['password'] === '!' || $row['password'] === sha1('!'))) {
            $bootstrapId = (int) $row['id'];
        }
    }

    // Tránh trùng username với một member khác (không phải row bootstrap sẽ bị thay).
    $stmt = mysqli_prepare($mysqli, 'SELECT `id` FROM `users` WHERE `username` = ? LIMIT 1');
    if (!$stmt) {
        $err = 'Lỗi truy vấn người dùng.';
        return false;
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $existing = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);

    if ($existing && (int) $existing['id'] !== (int) $bootstrapId) {
        $err = 'Tên đăng nhập quản trị đã tồn tại. Hãy chọn tên khác.';
        return false;
    }

    if ($bootstrapId !== null) {
        // Cập nhật bootstrap admin thành thông tin người dùng nhập.
        $stmt = mysqli_prepare(
            $mysqli,
            'UPDATE `users` SET `username` = ?, `password` = ?, `name` = ?, `email` = ?, '
            . "`level` = 'admin', `token` = ?, `banned` = 0, `status_2fa` = 0, `create_date` = ? WHERE `id` = ?"
        );
        if (!$stmt) {
            $err = 'Không cập nhật được tài khoản quản trị.';
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'ssssssi', $username, $hash, $name, $email, $token, $createDate, $bootstrapId);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$ok) {
            $err = 'Không cập nhật được tài khoản quản trị.';
            return false;
        }
        return true;
    }

    // Không có bootstrap: tạo admin mới đúng cách.
    if ($existing) {
        $err = 'Tên đăng nhập quản trị đã tồn tại. Hãy chọn tên khác.';
        return false;
    }
    $stmt = mysqli_prepare(
        $mysqli,
        'INSERT INTO `users` (`username`, `password`, `name`, `email`, `level`, `token`, `banned`, `create_date`, `secretkey`, `status_2fa`, `money`) '
        . "VALUES (?, ?, ?, ?, 'admin', ?, 0, ?, '', 0, 0)"
    );
    if (!$stmt) {
        $err = 'Không tạo được tài khoản quản trị.';
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'ssssss', $username, $hash, $name, $email, $token, $createDate);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$ok) {
        $err = 'Không tạo được tài khoản quản trị (có thể trùng tên đăng nhập).';
        return false;
    }
    return true;
}

/** Xác minh admin vừa tạo có thể đăng nhập theo logic hiện tại (so khớp SHA1). */
function installer_verify_admin_login($mysqli, array $admin)
{
    $stmt = mysqli_prepare($mysqli, "SELECT `password`, `level`, `banned` FROM `users` WHERE `username` = ? LIMIT 1");
    if (!$stmt) {
        return false;
    }
    $username = (string) $admin['username'];
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    if (!$row) {
        return false;
    }
    $hash = sha1((string) $admin['password']);
    return hash_equals((string) $row['password'], $hash)
        && $row['level'] === 'admin'
        && (int) $row['banned'] === 0;
}
