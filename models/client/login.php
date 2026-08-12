<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(JsonMsg('error', 'Phương thức không được hỗ trợ'));
}

if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])
    || !hash_equals((string) $_SESSION['csrf_token'], (string) $_POST['csrf_token'])) {
    exit(JsonMsg('error', 'Invalid CSRF Protection Token'));
}

if (empty($_POST['account-login'])) {
    exit(JsonMsg('error', 'Vui lòng nhập username'));
}
if (empty($_POST['password-login'])) {
    exit(JsonMsg('error', 'Vui lòng nhập mật khẩu'));
}

$username = trim(Anti_xss($_POST['account-login']));
$password = sha1((string) $_POST['password-login']);

if ((int) $db->site('status_captcha') === 1) {
    if (empty($_POST['g-recaptcha-response'])) {
        exit(JsonMsg('error', 'Vui lòng xác thực captcha'));
    }

    $secret = (string) $db->site('secret_key');
    $response = curl_get(
        'https://www.google.com/recaptcha/api/siteverify?secret=' . rawurlencode($secret)
        . '&response=' . rawurlencode((string) $_POST['g-recaptcha-response'])
        . '&remoteip=' . rawurlencode($_SERVER['REMOTE_ADDR'] ?? '')
    );
    $captcha = json_decode($response);
    if (!$captcha || empty($captcha->success)) {
        exit(JsonMsg('error', 'Vui lòng xác thực captcha'));
    }
}

$getUser = $db->get_row(
    "SELECT * FROM `users` WHERE `username` = '" . $db->escape($username) . "' LIMIT 1"
);

if (!$getUser || !hash_equals((string) $getUser['password'], $password)) {
    if ($getUser) {
        $attempts = (int) ($getUser['login_attempts'] ?? 0) + 1;
        $updates = [
            'login_attempts' => $attempts,
            'device' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];
        if ($attempts >= 5) {
            $updates['banned'] = 1;
        }
        $db->update('users', $updates, "`id` = '" . (int) $getUser['id'] . "'");

        if ($attempts >= 5) {
            insert_log($getUser['id'], 'Tài khoản tạm khoá do nhập sai mật khẩu quá nhiều lần');
            exit(JsonMsg('error', 'Tài khoản của bạn đã bị tạm khoá do nhập sai nhiều lần'));
        }
    }
    exit(JsonMsg('error', 'Tài khoản hoặc mật khẩu không chính xác'));
}

if ((int) $getUser['banned'] === 1) {
    exit(JsonMsg('error', 'Tài khoản của bạn đã bị tạm khoá, liên hệ admin để hỗ trợ'));
}

if ((int) $getUser['status_2fa'] === 1) {
    exit(json_encode([
        'status' => 'verify',
        'url' => '/verify/' . rawurlencode((string) $getUser['token']),
        'msg' => 'Vui lòng xác minh 2FA để hoàn thành đăng nhập',
    ], JSON_UNESCAPED_UNICODE));
}

$db->update('users', [
    'login_attempts' => 0,
    'ip' => myip(),
    'device' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'time_session' => time(),
], "`id` = '" . (int) $getUser['id'] . "'");
insert_log($getUser['id'], 'Đăng nhập vào hệ thống bằng phương thức tài khoản');
$session->send($getUser['username']);
exit(JsonMsg('success', 'Đăng nhập thành công!'));
