<?php
/**
 * Database configuration.
 *
 * On cPanel, replace the fallback values below with the database name, user and
 * password created in "MySQL Databases". cPanel normally prefixes the database
 * and user with your hosting account name (for example: account_shop).
 * Environment variables are supported when the host provides them.
 */
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', (int) (getenv('DB_PORT') ?: 3306));
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
define('DB_DATABASE', getenv('DB_DATABASE') ?: 'shopnickv5');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
