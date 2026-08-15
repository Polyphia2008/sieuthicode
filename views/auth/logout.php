<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

// Logout: thu hồi persistent token hiện tại (xoá theo SHA-256 hash trong DB)
// và xoá cookie remember_me trên trình duyệt.
auth_token_revoke_current($db);

$session->destroy();
new Redirect('/login');
exit();
