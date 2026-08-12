<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if (isset($_COOKIE['remember_me'])) {
    $token = (string) $_COOKIE['remember_me'];
    $secure = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        || strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')[0])) === 'https';
    setcookie('remember_me', '', time() - 3600, '/', '', $secure, true);
    $db->query("DELETE FROM `auth_tokens` WHERE `token` = '" . $db->escape($token) . "'");
}

$session->destroy();
new Redirect('/login');
exit();
