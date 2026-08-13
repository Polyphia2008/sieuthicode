<?php
/**
 * Unit/static tests for the Siêu Thị Code installer library.
 *
 * Runs standalone via CLI: `php tests/installer_unit_test.php`
 * Exercises pure functions that do NOT require a live MySQL connection:
 *  - installer_parse_legacy_config_source / installer_legacy_config_from_config_php
 *  - installer_valid_db_name
 *  - installer_split_sql (state-machine parser)
 *  - installer_journal_write/read/add_created/clear round-trip
 *  - installer_status_header phrase map (guarded for CLI)
 *
 * NOT committed with any credential, cookie, CSRF token, or DB dump.
 */

error_reporting(E_ALL);
define('APP_ROOT', sys_get_temp_dir() . '/stc_installer_test_' . getmypid());
@mkdir(APP_ROOT, 0755, true);
@mkdir(APP_ROOT . '/storage', 0755, true);

require_once __DIR__ . '/../libs/install.php';

$fail = 0;
$pass = 0;
function ok($cond, $label)
{
    global $fail, $pass;
    if ($cond) {
        $pass++;
        echo "PASS  $label\n";
    } else {
        $fail++;
        echo "FAIL  $label\n";
    }
}

/* ---- legacy config.php parsing ---- */

$legacySrc = <<<'PHP'
<?php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'cpanel_dbuser');
define('DB_DATABASE', 'cpanel_shopnick');
define('DB_PASSWORD', 'S3cretPass!2024');
PHP;
$parsed = installer_parse_legacy_config_source($legacySrc);
ok($parsed['DB_HOST'] === 'localhost', 'legacy parse: host');
ok($parsed['DB_PORT'] === 3306, 'legacy parse: port int');
ok($parsed['DB_USERNAME'] === 'cpanel_dbuser', 'legacy parse: username');
ok($parsed['DB_DATABASE'] === 'cpanel_shopnick', 'legacy parse: database');
ok($parsed['DB_PASSWORD'] === 'S3cretPass!2024', 'legacy parse: password literal');

// Default (unedited) config.php must NOT be treated as configured.
$defaultSrc = <<<'PHP'
<?php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'root');
define('DB_DATABASE', 'shopnickv5');
define('DB_PASSWORD', '');
PHP;
@file_put_contents(APP_ROOT . '/config.php', $defaultSrc);
ok(installer_legacy_config_from_config_php() === null, 'legacy default config.php -> not configured');

// Edited legacy config.php must be detected.
@file_put_contents(APP_ROOT . '/config.php', $legacySrc);
$legacy = installer_legacy_config_from_config_php();
ok(is_array($legacy) && $legacy['database'] === 'cpanel_shopnick', 'legacy edited config.php -> detected');
ok($legacy['password'] === 'S3cretPass!2024', 'legacy edited config.php -> password read (not overwritten)');

/* ---- db name validation ---- */
ok(installer_valid_db_name('cpanel_shopnick') === true, 'valid db name with underscore');
ok(installer_valid_db_name('a' . str_repeat('b', 70)) === false, 'reject db name > 64 chars');
ok(installer_valid_db_name('bad;DROP') === false, 'reject db name with semicolon');

/* ---- SQL splitter ---- */
$split1 = installer_split_sql("CREATE TABLE a (x VARCHAR(10) DEFAULT 'a;b');\n-- comment ;\nINSERT INTO a VALUES ('x;y');");
ok(count($split1) === 2, 'splitter keeps semicolons inside strings');
$split2 = installer_split_sql("/* block; comment */ SELECT 'it''s'; # tail\nSELECT \"d;d\";");
ok(count($split2) === 2, 'splitter handles block/line comments and escapes');
$split3 = installer_split_sql("SELECT `weird``name` FROM t; SELECT 2;");
ok(count($split3) === 2, 'splitter handles backticks');

/* ---- recovery journal round-trip ---- */
ok(installer_journal_start('testdb') === true, 'journal start writes file');
$j = installer_journal_read();
ok(is_array($j) && $j['database'] === 'testdb' && $j['created_tables'] === [], 'journal read: empty created list');
installer_journal_add_created(['users' => true, 'orders' => true]);
installer_journal_add_created(['chat_messages' => true]);
$j2 = installer_journal_read();
ok(in_array('users', $j2['created_tables'], true)
    && in_array('orders', $j2['created_tables'], true)
    && in_array('chat_messages', $j2['created_tables'], true), 'journal accumulates created tables');
$raw = file_get_contents(installer_journal_path());
ok(strpos($raw, 'password') === false, 'journal contains no password field');
installer_journal_clear();
ok(installer_journal_read() === null, 'journal clear removes file');

/* ---- status header phrase map (CLI-safe: just ensure no fatal) ---- */
ob_start();
installer_status_header(419);
ob_end_clean();
ok(true, 'status header 419 does not fatal on CLI');

/* ---- full/core table lists ---- */
$core = installer_core_tables();
$full = installer_full_tables();
ok(count($core) >= 5, 'core schema has >= 5 tables');
ok(in_array('chat_conversations', $full, true) && in_array('chat_messages', $full, true), 'full schema includes chat tables');
ok(count($full) > count($core), 'full schema is a superset of core');

/* ---- cleanup ---- */
@unlink(APP_ROOT . '/config.php');
@rmdir(APP_ROOT . '/storage');
@rmdir(APP_ROOT);

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
