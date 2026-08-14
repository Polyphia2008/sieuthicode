<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(JsonMsg('error', 'Phương thức không được hỗ trợ'));
}

$type = (string) ($_POST['type'] ?? '');
$google2fa = new PragmaRX\Google2FA\Google2FA();

if ($type === 'ChangeGoogle2FA') {
    if (!$user || !$data_user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])
        || !hash_equals((string) $_SESSION['csrf_token'], (string) $_POST['csrf_token'])) {
        exit(JsonMsg('error', 'Invalid CSRF Protection Token'));
    }

    $code = trim((string) ($_POST['secret'] ?? ''));
    if ($code === '') {
        exit(JsonMsg('error', 'Vui lòng nhập mã xác minh 2FA!'));
    }
    if (!$google2fa->verifyKey((string) $data_user['secretkey'], $code)) {
        exit(JsonMsg('error', 'Mã xác minh không chính xác!'));
    }

    $db->update('users', [
        'status_2fa' => (int) $data_user['status_2fa'] === 1 ? 0 : 1,
    ], "`id` = '" . (int) $data_user['id'] . "'");
    exit(JsonMsg('success', 'Lưu thành công'));
}

if ($type === 'VerifyGoogle2FA') {
    $token = trim((string) ($_POST['token'] ?? ''));
    $code = trim((string) ($_POST['code'] ?? ''));
    if ($token === '') {
        exit(JsonMsg('error', 'Phiên đăng nhập không hợp lệ!'));
    }
    if ($code === '') {
        exit(JsonMsg('error', 'Vui lòng nhập mã xác minh!'));
    }

    $getUser = $db->get_row(
        "SELECT * FROM `users` WHERE `token` = '" . $db->escape($token) . "' AND `banned` = 0 LIMIT 1"
    );
    if (!$getUser) {
        exit(JsonMsg('error', 'Phiên đăng nhập không hợp lệ!'));
    }
    if (!$google2fa->verifyKey((string) $getUser['secretkey'], $code)) {
        insert_log($getUser['id'], '[Warning] Có người nhập sai mã xác minh 2FA');
        exit(JsonMsg('error', 'Mã xác minh không chính xác!'));
    }

    insert_log($getUser['id'], 'Đăng nhập vào hệ thống bằng phương thức tài khoản và 2FA');
    $db->update('users', [
        'login_attempts' => 0,
        'ip' => myip(),
        'device' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'time_session' => time(),
    ], "`id` = '" . (int) $getUser['id'] . "'");
    $session->send($getUser['username']);
    // Chỉ cấp persistent token SAU khi 2FA verify thành công; dùng lựa chọn 2/7 ngày
    // đã lưu trong pending session lúc login (mặc định 2 ngày nếu không có).
    $remember = auth_remember_requested($_SESSION['pending_2fa_remember'] ?? 0);
    unset($_SESSION['pending_2fa_remember']);
    $authExpiresAt = auth_token_issue($db, (int) $getUser['id'], $remember, myip());
    if ($authExpiresAt > 0) {
        $_SESSION['auth_expires_at'] = $authExpiresAt;
    }
    exit(JsonMsg('success', 'Đăng nhập thành công'));
}

http_response_code(400);
exit(JsonMsg('error', 'Yêu cầu không hợp lệ'));
