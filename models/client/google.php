<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if ((int) $db->site('status_login_google') !== 1) {
    new Redirect('/login');
    exit();
}

if (GOOGLE_APP_ID === '' || GOOGLE_APP_SECRET === '') {
    http_response_code(503);
    exit('<script>alert("Google OAuth chưa được cấu hình trong trang quản trị.");location.href="/login";</script>');
}

$client = new Google\Client();
$client->setClientId(GOOGLE_APP_ID);
$client->setClientSecret(GOOGLE_APP_SECRET);
$client->setRedirectUri(GOOGLE_APP_CALLBACK_URL);
$client->setAccessType('online');
$client->setPrompt('select_account');
$client->addScope('email');
$client->addScope('profile');

if (empty($_GET['code'])) {
    $state = bin2hex(random_bytes(24));
    $_SESSION['google_oauth_state'] = $state;
    $client->setState($state);
    header('Location: ' . $client->createAuthUrl());
    exit();
}

$state = (string) ($_GET['state'] ?? '');
$expectedState = (string) ($_SESSION['google_oauth_state'] ?? '');
unset($_SESSION['google_oauth_state']);
if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state)) {
    http_response_code(400);
    exit('<script>alert("Phiên đăng nhập Google không hợp lệ hoặc đã hết hạn.");location.href="/login";</script>');
}

try {
    $token = $client->fetchAccessTokenWithAuthCode((string) $_GET['code']);
    if (isset($token['error'])) {
        throw new RuntimeException((string) ($token['error_description'] ?? $token['error']));
    }

    $client->setAccessToken($token);
    $oauth = new Google\Service\Oauth2($client);
    $profile = $oauth->userinfo->get();
    $email = strtolower(trim((string) $profile->email));
    $providerId = trim((string) $profile->id);

    if ($providerId === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Google không trả về thông tin tài khoản hợp lệ.');
    }

    $account = $db->get_row(
        "SELECT * FROM `users` WHERE (`provider` = 'google' AND `provider_id` = '"
        . $db->escape($providerId) . "') OR `email` = '" . $db->escape($email) . "' LIMIT 1"
    );

    if (!$account) {
        $baseUsername = preg_replace('/[^a-z0-9_.]/', '', strtolower(strstr($email, '@', true))) ?: 'googleuser';
        $baseUsername = substr($baseUsername, 0, 24);
        if (strlen($baseUsername) < 5) {
            $baseUsername .= 'user';
        }

        $username = $baseUsername;
        $suffix = 0;
        while ($db->get_row("SELECT `id` FROM `users` WHERE `username` = '" . $db->escape($username) . "' LIMIT 1")) {
            ++$suffix;
            $username = substr($baseUsername, 0, 24) . $suffix;
        }

        $google2fa = new PragmaRX\Google2FA\Google2FA();
        $inserted = $db->insert('users', [
            'username' => $username,
            'password' => sha1(bin2hex(random_bytes(32))),
            'name' => trim((string) $profile->name),
            'email' => $email,
            'level' => 'member',
            'provider' => 'google',
            'provider_id' => $providerId,
            'token' => md5(bin2hex(random_bytes(32))),
            'secretkey' => $google2fa->generateSecretKey(),
            'ip' => myip(),
            'device' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'create_date' => gettime(),
            'time_session' => time(),
        ]);

        if (!$inserted) {
            throw new RuntimeException('Không thể tạo tài khoản người dùng.');
        }
        $account = $db->get_row("SELECT * FROM `users` WHERE `username` = '" . $db->escape($username) . "' LIMIT 1");
    } else {
        if ((int) $account['banned'] === 1) {
            exit('<script>alert("Tài khoản đã bị khoá.");location.href="/login";</script>');
        }
        $db->update('users', [
            'provider' => 'google',
            'provider_id' => $providerId,
            'name' => $account['name'] ?: trim((string) $profile->name),
            'ip' => myip(),
            'device' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'time_session' => time(),
        ], "`id` = '" . (int) $account['id'] . "'");
    }

    insert_log($account['id'], 'Đăng nhập vào hệ thống bằng Google');
    $session->send($account['username']);
    // Google OAuth: persistent login mặc định 2 ngày (thời hạn tuyệt đối, hash trong DB).
    $authExpiresAt = auth_token_issue($db, (int) $account['id'], false, myip());
    if ($authExpiresAt > 0) {
        $_SESSION['auth_expires_at'] = $authExpiresAt;
    }
    header('Location: /');
    exit();
} catch (Throwable $exception) {
    error_log('Google OAuth error: ' . $exception->getMessage());
    http_response_code(500);
    exit('<script>alert("Đăng nhập Google thất bại. Vui lòng kiểm tra cấu hình OAuth.");location.href="/login";</script>');
}
