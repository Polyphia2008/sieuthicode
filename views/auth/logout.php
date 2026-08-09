<?php
// statically decompiled from logout.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$session->destroy();
if (isset($_COOKIE['remember_me'])) {
    $token = Anti_xss($_COOKIE['remember_me']);
    setcookie('remember_me', '', time() - 3600, '/', '', true, true);
    $sql = 'DELETE FROM auth_tokens WHERE token = ' . $token;
    $db->query($sql);
}
new Redirect('/login');
