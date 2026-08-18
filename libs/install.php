<?php
/**
 * Trình cài đặt Siêu Thị Code — thư viện lõi.
 *
 * Không chứa credential, không in mật khẩu, không hotlink tài nguyên ngoài.
 * Mọi hàm nhận credential dạng giá trị (không ghép SQL).
 *
 * Nguyên tắc bảo mật chính:
 *  - installed.lock là tín hiệu "đã cài" mạnh nhất.
 *  - Nếu đã có cấu hình DB rõ ràng (config.local.php / env / config.php đã sửa)
 *    nhưng DB tạm mất kết nối => website vẫn được xem là ĐÃ CẤU HÌNH, installer
 *    bị khoá, request thường chỉ thấy lỗi database. Không bao giờ mở lại
 *    installer để người lạ trỏ website sang database khác.
 *  - installed.lock chỉ được ghi khi FULL schema đã được xác minh và không có
 *    tiến trình cài đặt nào đang giữ mutex.
 */

// Fail-closed: không cho phép gọi trực tiếp file này qua web.
if (realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    header('HTTP/1.1 403 Forbidden', true, 403);
    exit('Forbidden');
}

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

/** Đường dẫn journal phục hồi sau khi tiến trình cài đặt bị kill giữa chừng. */
function installer_journal_path()
{
    return APP_ROOT . '/storage/install.journal.json';
}

/* ------------------------------------------------------------------------ *
 * Trạng thái cài đặt
 * ------------------------------------------------------------------------ */

/**
 * Xác định trạng thái cài đặt của website.
 *
 * Trả về một trong:
 *  - 'installed'   : đã cài (có lock, hoặc config hợp lệ + core schema tồn tại).
 *  - 'offline'     : ĐÃ có cấu hình DB rõ ràng nhưng không kết nối được.
 *                    Website vẫn được xem là đã cấu hình — installer phải khoá,
 *                    request thường chỉ hiển thị lỗi database.
 *  - 'installing'  : một tiến trình cài đặt khác đang giữ mutex mà chưa có
 *                    installed.lock — database có thể đang import dở. Request
 *                    công khai phải nhận 503 "Đang cài đặt" thay vì chạy app
 *                    trên database dở dang, và TUYỆT ĐỐI không tạo lock sớm.
 *  - 'interrupted' : có journal cài đặt dở HỢP LỆ khớp fingerprint cấu hình
 *                    hiện tại — lần cài trước bị hard-kill. Không được coi là
 *                    installed dù core schema đã tồn tại; không tự tạo lock;
 *                    trang thường nhận 503 "Cài đặt bị gián đoạn", /install
 *                    mở để phục hồi.
 *  - 'journal_invalid' : journal hỏng / sai version / fingerprint không khớp.
 *                    Fail closed: không DROP gì, không chạy app trên database
 *                    dở, không tự tạo lock; hiển thị lỗi an toàn yêu cầu quản
 *                    trị viên kiểm tra journal.
 *  - 'uninstalled' : chưa có cấu hình rõ ràng, hoặc DB kết nối được nhưng trống.
 *
 * Website legacy CHỈ được coi là installed khi không có journal cài dở VÀ có
 * core schema hợp lệ (hoặc có installed.lock).
 *
 * Kết quả được cache trong suốt request để tránh kết nối DB nhiều lần.
 */
function installer_state()
{
    static $state = null;
    if ($state !== null) {
        return $state;
    }

    // 1) Lock file là tín hiệu mạnh nhất.
    if (is_file(installer_lock_path())) {
        return $state = 'installed';
    }

    // 1b) Một tiến trình cài đặt khác đang chạy (giữ mutex) mà chưa có lock
    //     => database có thể đang import dở. Báo 'installing' để caller trả
    //     503 "Đang cài đặt"; không kết nối DB, không ghi installed.lock sớm.
    if (installer_mutex_is_held()) {
        return $state = 'installing';
    }

    /*
     * 1c) Journal cài đặt dở (hard-interruption) được kiểm tra TRƯỚC khi kết
     *     luận "installed" từ core schema: một database đã import xong base SQL
     *     nhưng bị kill trước chat migration / admin / config / lock vẫn có đủ
     *     core schema — tuyệt đối không được coi là đã cài, không tự tạo lock.
     */
    $journal = installer_journal_read();
    if ($journal !== null) {
        $cfg = installer_existing_db_config();
        if (!empty($journal['ok']) && $cfg !== null
            && $journal['fingerprint'] !== ''
            && hash_equals($journal['fingerprint'], installer_journal_fingerprint($cfg))
        ) {
            // Journal hợp lệ khớp đích hiện tại => lần cài trước bị gián đoạn.
            return $state = 'interrupted';
        }
        if ($cfg === null && !empty($journal['ok'])) {
            // Chưa có cấu hình rõ ràng: mở installer để người dùng nhập lại DB
            // và phục hồi qua fingerprint (test_db/install tự kiểm chứng).
            return $state = 'uninstalled';
        }
        // Journal hỏng / sai version / fingerprint không khớp: fail closed.
        return $state = 'journal_invalid';
    }

    // 2) Cấu hình DB rõ ràng: config.local.php > env > config.php đã sửa tay.
    $cfg = installer_existing_db_config();
    if ($cfg === null) {
        return $state = 'uninstalled';
    }

    // 3) Có cấu hình nhưng không kết nối được => DB tạm offline.
    //    TUYỆT ĐỐI không mở lại installer trong trường hợp này.
    $mysqli = installer_try_connect($cfg, $err);
    if (!$mysqli) {
        return $state = 'offline';
    }

    // 4) Kết nối được: website cũ đã cài nếu có đầy đủ core schema.
    if (installer_has_core_schema($mysqli)) {
        // Chỉ ghi lock khi FULL schema đã xác minh VÀ không có tiến trình
        // cài đặt đang giữ mutex (tránh race: request đồng thời trong lúc
        // import thấy đủ bảng rồi tự đánh dấu hoàn tất quá sớm).
        if (installer_has_full_schema($mysqli) && !installer_mutex_is_held()) {
            @installer_write_lock($mysqli);
        }
        mysqli_close($mysqli);
        return $state = 'installed';
    }

    mysqli_close($mysqli);
    return $state = 'uninstalled';
}

/** Website đã được cài đặt (hoặc đã cấu hình nhưng DB tạm offline)? */
function isApplicationInstalled()
{
    $state = installer_state();
    // 'offline' vẫn trả true: website đã được cấu hình, installer phải khoá.
    return $state === 'installed' || $state === 'offline';
}

/** Website đã cấu hình nhưng database tạm mất kết nối? */
function installer_is_offline()
{
    return installer_state() === 'offline';
}

/* ------------------------------------------------------------------------ *
 * Phát hiện cấu hình database hiện có
 * ------------------------------------------------------------------------ */

/**
 * Lấy cấu hình DB hiện có (nếu có). Thứ tự ưu tiên:
 *   1. config.local.php (do installer ghi).
 *   2. Biến môi trường DB_*.
 *   3. config.php đã được sửa tay (legacy — README cũ yêu cầu sửa trực tiếp).
 * Trả về null nếu không có cấu hình rõ ràng nào (chỉ còn default mẫu).
 */
function installer_existing_db_config()
{
    // 1) config.local.php
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

    // 2) Biến môi trường (vhost SetEnv / cPanel / Docker).
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

    // 3) Legacy: config.php đã sửa tay (khác giá trị mẫu mặc định).
    return installer_legacy_config_from_config_php();
}

/**
 * Đọc credential literal từ config.php của website cũ.
 * Chỉ trả về cấu hình khi người dùng đã sửa ít nhất một giá trị khác mẫu
 * mặc định của source — nếu không, coi như chưa cấu hình (installer mở).
 * Hàm này CHỈ ĐỌC, tuyệt đối không ghi/sửa config.php.
 */
function installer_legacy_config_from_config_php()
{
    $file = APP_ROOT . '/config.php';
    if (!is_file($file) || !is_readable($file)) {
        return null;
    }
    $src = @file_get_contents($file);
    if ($src === false) {
        return null;
    }
    $vals = installer_parse_legacy_config_source($src);

    $host = $vals['DB_HOST'] ?? '';
    $port = (int) ($vals['DB_PORT'] ?? 3306);
    $user = $vals['DB_USERNAME'] ?? '';
    $db = $vals['DB_DATABASE'] ?? '';
    $pass = $vals['DB_PASSWORD'] ?? '';

    if ($db === '' || !installer_valid_db_name($db)) {
        return null;
    }

    // Giá trị mẫu mặc định của source gốc => coi như CHƯA cấu hình.
    if ($host === 'localhost' && $port === 3306 && $user === 'root'
        && $db === 'shopnickv5' && $pass === '') {
        return null;
    }

    return [
        'host' => $host !== '' ? $host : 'localhost',
        'port' => $port > 0 ? $port : 3306,
        'username' => $user,
        'password' => $pass,
        'database' => $db,
    ];
}

/**
 * Tách các hằng DB_* dạng literal ra khỏi source config.php (hỗ trợ test).
 * Chấp nhận cả dạng define('DB_HOST','localhost') lẫn define("DB_PORT", 3306).
 */
function installer_parse_legacy_config_source($src)
{
    $out = [];
    foreach (['DB_HOST', 'DB_PORT', 'DB_USERNAME', 'DB_DATABASE', 'DB_PASSWORD'] as $const) {
        // Dạng chuỗi: define('DB_HOST', 'localhost');
        if (preg_match(
            "/define\\(\\s*['\"]" . $const . "['\"]\\s*,\\s*'((?:[^'\\\\]|\\\\.)*)'\\s*\\)/",
            $src,
            $m
        )) {
            $out[$const] = stripcslashes($m[1]);
            continue;
        }
        if (preg_match(
            '/define\\(\\s*[\'"]' . $const . '[\'"]\\s*,\\s*"((?:[^"\\\\]|\\\\.)*)"\\s*\\)/',
            $src,
            $m
        )) {
            $out[$const] = stripcslashes($m[1]);
            continue;
        }
        // Dạng số: define('DB_PORT', 3306);
        if (preg_match(
            "/define\\(\\s*['\"]" . $const . "['\"]\\s*,\\s*(\\d+)\\s*\\)/",
            $src,
            $m
        )) {
            $out[$const] = (int) $m[1];
        }
    }
    return $out;
}

/* ------------------------------------------------------------------------ *
 * Kết nối và kiểm tra schema
 * ------------------------------------------------------------------------ */

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

/**
 * Core schema — tập bảng tối thiểu chứng tỏ website đã được cài.
 * Nhiều hơn đáng kể so với chỉ users+options để tránh nhận nhầm database
 * đang được import dở (bảng users nằm gần cuối file dump).
 */
function installer_core_tables()
{
    return ['users', 'options', 'accounts', 'categories', 'orders'];
}

/**
 * Full schema — toàn bộ bảng mà phiên bản code hiện tại yêu cầu,
 * bao gồm bảng chat (được tạo bởi migration cuối cùng của installer).
 * installed.lock CHỈ được ghi khi toàn bộ các bảng này tồn tại.
 */
function installer_full_tables()
{
    return [
        'users', 'options', 'accounts', 'categories', 'orders',
        'chat_conversations', 'chat_messages',
    ];
}

/** Kiểm tra core schema — dấu hiệu website cũ đã cài. */
function installer_has_core_schema($mysqli)
{
    foreach (installer_core_tables() as $t) {
        if (!installer_table_exists($mysqli, $t)) {
            return false;
        }
    }
    return true;
}

/** Kiểm tra full schema — điều kiện bắt buộc để ghi installed.lock. */
function installer_has_full_schema($mysqli)
{
    foreach (installer_full_tables() as $t) {
        if (!installer_table_exists($mysqli, $t)) {
            return false;
        }
    }
    return true;
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

/* ------------------------------------------------------------------------ *
 * Mutex chống cài đặt đồng thời
 * ------------------------------------------------------------------------ */

/**
 * Kiểm tra có tiến trình cài đặt nào đang giữ mutex không.
 * Không phá lock: thử lấy non-blocking rồi trả lại ngay.
 */
function installer_mutex_is_held()
{
    $path = installer_mutex_path();
    if (!is_file($path)) {
        return false;
    }
    $fh = @fopen($path, 'c');
    if (!$fh) {
        // Không đọc/ghi được file lock — thận trọng: coi như đang bận.
        return true;
    }
    $got = flock($fh, LOCK_EX | LOCK_NB);
    if ($got) {
        flock($fh, LOCK_UN);
    }
    fclose($fh);
    return !$got;
}

/* ------------------------------------------------------------------------ *
 * Recovery journal (phục hồi sau hard interruption)
 * ------------------------------------------------------------------------ */

/**
 * Phiên bản định dạng journal hiện tại. Journal có version khác bị coi là
 * không hợp lệ: KHÔNG được dùng để cleanup (tránh DROP nhầm theo format cũ).
 */
define('INSTALLER_JOURNAL_VERSION', 2);

/**
 * Lọc một danh sách tên bảng: chỉ giữ tên hợp lệ ([A-Za-z0-9_]), loại trùng.
 */
function installer_sanitize_table_names($list)
{
    $out = [];
    if (!is_array($list)) {
        return $out;
    }
    foreach ($list as $t) {
        $t = preg_replace('/[^A-Za-z0-9_]/', '', (string) $t);
        if ($t !== '') {
            $out[$t] = true;
        }
    }
    return array_keys($out);
}

/**
 * Đọc journal của lần cài đặt dở (nếu có).
 *
 * Trả về mảng:
 *  [
 *    'ok'            => bool,   // false = file hỏng / sai version (cấm cleanup)
 *    'version'       => int,
 *    'nonce'         => string, // id ngẫu nhiên của lần cài đặt
 *    'fingerprint'   => string, // hash(host+port+username+database), KHÔNG password
 *    'database'      => string,
 *    'initial_tables'=> array,  // bảng tồn tại TRƯỚC khi cài (bằng chứng DB trống)
 *    'planned_tables'=> array,  // allowlist: bảng installer dự kiến tạo
 *    'created_tables'=> array,  // bảng đã xác nhận tạo thành công
 *    'started_at'    => string,
 *  ]
 * hoặc null nếu không có file journal.
 *
 * Journal KHÔNG bao giờ chứa DB password hay admin password.
 */
function installer_journal_read()
{
    $file = installer_journal_path();
    if (!is_file($file)) {
        return null;
    }
    $raw = @file_get_contents($file);
    $data = ($raw !== false && $raw !== '') ? json_decode($raw, true) : null;

    // Journal không đọc được / JSON hỏng => trả về bản ghi "hỏng" để caller
    // dừng an toàn (KHÔNG cleanup), thay vì âm thầm coi như không có journal.
    if (!is_array($data)
        || empty($data['database']) || !is_string($data['database'])
        || !isset($data['created_tables']) || !is_array($data['created_tables'])
        || !isset($data['planned_tables']) || !is_array($data['planned_tables'])
        || !isset($data['initial_tables']) || !is_array($data['initial_tables'])
    ) {
        return [
            'ok' => false,
            'version' => 0,
            'nonce' => '',
            'fingerprint' => '',
            'database' => is_array($data) && isset($data['database']) && is_string($data['database'])
                ? (string) $data['database'] : '',
            'initial_tables' => [],
            'planned_tables' => [],
            'created_tables' => [],
            'started_at' => is_array($data) && isset($data['started_at'])
                ? (string) $data['started_at'] : '',
        ];
    }

    $version = (int) ($data['version'] ?? 0);
    return [
        'ok' => $version === INSTALLER_JOURNAL_VERSION,
        'version' => $version,
        'nonce' => (string) ($data['nonce'] ?? ''),
        'fingerprint' => (string) ($data['fingerprint'] ?? ''),
        'database' => (string) $data['database'],
        'initial_tables' => installer_sanitize_table_names($data['initial_tables']),
        'planned_tables' => installer_sanitize_table_names($data['planned_tables']),
        'created_tables' => installer_sanitize_table_names($data['created_tables']),
        'started_at' => (string) ($data['started_at'] ?? ''),
    ];
}

/**
 * Fingerprint nhận diện đích cài đặt: hash(host + port + username + database).
 * KHÔNG bao giờ đưa password vào fingerprint. Retry chỉ được cleanup khi
 * fingerprint của kết nối hiện tại khớp journal — hai server khác nhau có thể
 * có cùng tên database nên không được chỉ so sánh tên database.
 */
function installer_journal_fingerprint(array $cfg)
{
    return hash('sha256', implode('|', [
        'stc-install-target',
        strtolower(trim((string) ($cfg['host'] ?? 'localhost'))),
        (string) (int) ($cfg['port'] ?? 3306),
        (string) ($cfg['username'] ?? ''),
        (string) ($cfg['database'] ?? ''),
    ]));
}

/**
 * Phân tích danh sách bảng mà các file SQL của installer sẽ tạo
 * (base dump + migration chat). Đây là allowlist dùng để giới hạn cleanup:
 * retry chỉ được DROP các bảng thuộc danh sách này.
 */
function installer_planned_tables()
{
    $planned = [];
    $files = [
        APP_ROOT . '/shoprobloxv4 (2).sql',
        APP_ROOT . '/database/migrations/20260812_chat_box.sql',
        APP_ROOT . '/database/migrations/20260814_superadmin_sessions.sql',
        APP_ROOT . '/database/migrations/20260818_chat_direct_admin.sql',
        APP_ROOT . '/database/migrations/20260819_notifications_history.sql',
    ];
    foreach ($files as $file) {
        if (!is_file($file) || !is_readable($file)) {
            continue;
        }
        $sql = @file_get_contents($file);
        if ($sql === false) {
            continue;
        }
        foreach (installer_split_sql($sql) as $stmt) {
            // installer_split_sql() giữ lại các comment header (dòng "--" rỗng,
            // block comment) ở ĐẦU statement, nên CREATE TABLE không nằm ở vị
            // trí đầu chuỗi. Tìm CREATE TABLE ở bất kỳ đâu trong statement
            // (statement DDL hợp lệ chỉ chứa đúng một CREATE TABLE).
            if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([A-Za-z0-9_]+)`?/i', $stmt, $m)) {
                $planned[$m[1]] = true;
            }
        }
    }
    return array_keys($planned);
}

/**
 * Ghi journal TRƯỚC khi chạy DDL bất kỳ.
 *
 * Ghi nhận: version, nonce ngẫu nhiên, fingerprint đích (không secret),
 * database, initial_tables (bằng chứng DB trống lúc bắt đầu — nếu DB không
 * trống thì caller phải từ chối cài TRƯỚC khi gọi hàm này), planned_tables
 * (allowlist cleanup) và started_at.
 */
function installer_journal_start(array $cfg, array $initialTables, array $plannedTables)
{
    $payload = [
        'version' => INSTALLER_JOURNAL_VERSION,
        'nonce' => bin2hex(random_bytes(16)),
        'fingerprint' => installer_journal_fingerprint($cfg),
        'database' => (string) ($cfg['database'] ?? ''),
        'initial_tables' => array_values(installer_sanitize_table_names($initialTables)),
        'planned_tables' => array_values(installer_sanitize_table_names($plannedTables)),
        'created_tables' => [],
        'started_at' => date('c'),
    ];
    return installer_journal_write($payload);
}

/** Bổ sung các bảng vừa được installer tạo vào journal (atomic, giữ nguyên các trường khác). */
function installer_journal_add_created(array $createdTables)
{
    $journal = installer_journal_read();
    if ($journal === null || empty($journal['ok'])) {
        return false;
    }
    $merged = installer_sanitize_table_names(
        array_merge($journal['created_tables'], array_keys($createdTables))
    );
    $payload = [
        'version' => INSTALLER_JOURNAL_VERSION,
        'nonce' => $journal['nonce'],
        'fingerprint' => $journal['fingerprint'],
        'database' => $journal['database'],
        'initial_tables' => $journal['initial_tables'],
        'planned_tables' => $journal['planned_tables'],
        'created_tables' => $merged,
        'started_at' => $journal['started_at'] !== '' ? $journal['started_at'] : date('c'),
    ];
    return installer_journal_write($payload);
}

/**
 * Phục hồi sau hard-interruption (SIGKILL giữa import).
 *
 * An toàn tuyệt đối:
 *  - Journal hỏng / sai version / fingerprint không khớp / database khác:
 *    KHÔNG DROP gì, dừng với thông báo an toàn.
 *  - Fingerprint khớp: chỉ cleanup đúng allowlist
 *      current_tables ∩ planned_tables − initial_tables
 *    (tuyệt đối không "current − initial" rồi DROP tùy ý).
 *  - Cleanup xong: xoá journal + dọn config.local.php dở (nếu có).
 *
 * Trả về mảng:
 *  ['ok'=>true, 'recovered'=>bool, 'message'=>'']
 *  ['ok'=>false, 'message'=>'...lý do dừng an toàn...']
 */
function installer_journal_recover($mysqli, array $cfg)
{
    $journal = installer_journal_read();
    if ($journal === null) {
        return ['ok' => true, 'recovered' => false, 'message' => ''];
    }

    $stop = function ($message) {
        return ['ok' => false, 'message' => $message];
    };

    if (empty($journal['ok'])) {
        return $stop(
            'Phát hiện journal cài đặt dở nhưng file bị hỏng hoặc sai phiên bản. '
            . 'Không tự động dọn dẹp để tránh mất dữ liệu. Hãy xoá thủ công '
            . 'storage/install.journal.json sau khi đã kiểm tra database.'
        );
    }
    if ($journal['database'] !== (string) ($cfg['database'] ?? '')) {
        return $stop(
            'Journal cài đặt dở thuộc database khác ("' . $journal['database'] . '"). '
            . 'Không tự động dọn dẹp. Hãy xoá thủ công storage/install.journal.json '
            . 'sau khi đã kiểm tra database.'
        );
    }
    if ($journal['fingerprint'] === ''
        || !hash_equals($journal['fingerprint'], installer_journal_fingerprint($cfg))) {
        return $stop(
            'Journal cài đặt dở thuộc một máy chủ/tài khoản database khác '
            . '(fingerprint không khớp). Không tự động dọn dẹp. Hãy xoá thủ công '
            . 'storage/install.journal.json sau khi đã kiểm tra database.'
        );
    }

    // Fingerprint khớp: cleanup chọn lọc theo allowlist.
    $current = installer_list_tables($mysqli);
    $targets = array_values(array_diff(
        array_intersect($current, $journal['planned_tables']),
        $journal['initial_tables']
    ));
    installer_rollback_created($mysqli, array_fill_keys($targets, true));
    installer_journal_clear();
    // Không để config.local.php dở từ lần cài bị gián đoạn.
    @unlink(installer_config_path());
    return ['ok' => true, 'recovered' => !empty($targets), 'message' => ''];
}

/** Xoá journal sau khi cài đặt hoàn tất (hoặc rollback sạch). */
function installer_journal_clear()
{
    @unlink(installer_journal_path());
}

/** Ghi journal atomic (temp + LOCK_EX + rename, quyền 0600). */
function installer_journal_write(array $payload)
{
    $dir = dirname(installer_journal_path());
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $tmp = installer_journal_path() . '.tmp.' . bin2hex(random_bytes(6));
    if (@file_put_contents($tmp, json_encode($payload, JSON_PRETTY_PRINT), LOCK_EX) === false) {
        @unlink($tmp);
        return false;
    }
    @chmod($tmp, 0600);
    if (!@rename($tmp, installer_journal_path())) {
        @unlink($tmp);
        return false;
    }
    return true;
}

/* ------------------------------------------------------------------------ *
 * Import SQL an toàn
 * ------------------------------------------------------------------------ */

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

/* ------------------------------------------------------------------------ *
 * CSRF / rate limit / session / headers
 * ------------------------------------------------------------------------ */

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

/* ------------------------------------------------------------------------ *
 * Ghi file runtime an toàn
 * ------------------------------------------------------------------------ */

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

/* ------------------------------------------------------------------------ *
 * HTTP response
 * ------------------------------------------------------------------------ */

/**
 * Gửi status line đầy đủ, tương thích Apache/mod_php.
 * http_response_code() đơn lẻ với mã không chuẩn (vd 419) có thể bị
 * Apache 2.4 rewrite thành 500 vì thiếu reason phrase đã đăng ký.
 */
function installer_status_header($code)
{
    static $phrases = [
        200 => 'OK',
        302 => 'Found',
        400 => 'Bad Request',
        403 => 'Forbidden',
        405 => 'Method Not Allowed',
        409 => 'Conflict',
        419 => 'Authentication Timeout',
        422 => 'Unprocessable Entity',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];
    // CLI / headers đã gửi: không thể set header, bỏ qua (tránh warning).
    if (PHP_SAPI === 'cli' || headers_sent()) {
        return;
    }
    $code = (int) $code;
    $protocol = (string) ($_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1');
    if ($protocol !== 'HTTP/1.1' && $protocol !== 'HTTP/1.0') {
        $protocol = 'HTTP/1.1';
    }
    if (isset($phrases[$code])) {
        header($protocol . ' ' . $code . ' ' . $phrases[$code], true, $code);
    } else {
        http_response_code($code);
    }
}

/** Trả JSON response cho API installer và dừng. Không bao giờ echo credential. */
function installer_json($ok, $message, $extra = [], $httpCode = 200)
{
    installer_status_header($httpCode);
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
