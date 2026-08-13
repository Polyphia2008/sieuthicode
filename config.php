<?php
/**
 * Database configuration.
 *
 * On cPanel, replace the fallback values below with the database name, user and
 * password created in "MySQL Databases". cPanel normally prefixes the database
 * and user with your hosting account name (for example: account_shop).
 * Environment variables are supported when the host provides them.
 */
// config.local.php is written by the installer and never committed (.gitignore).
// Priority: config.local.php > environment variables > built-in defaults.
$__local = is_file(__DIR__ . '/config.local.php') ? include __DIR__ . '/config.local.php' : null;
$__localDb = (is_array($__local) && isset($__local['db']) && is_array($__local['db'])) ? $__local['db'] : [];

define('DB_HOST', (string) ($__localDb['host'] ?? (getenv('DB_HOST') ?: 'localhost')));
define('DB_PORT', (int) ($__localDb['port'] ?? (getenv('DB_PORT') ?: 3306)));
define('DB_USERNAME', (string) ($__localDb['username'] ?? (getenv('DB_USERNAME') ?: 'root')));
define('DB_DATABASE', (string) ($__localDb['database'] ?? (getenv('DB_DATABASE') ?: 'shopnickv5')));
define('DB_PASSWORD', (string) ($__localDb['password'] ?? (getenv('DB_PASSWORD') ?: '')));
unset($__local, $__localDb);
