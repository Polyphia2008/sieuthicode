<?php

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if ($user) {
    new Redirect('/');
    exit();
}

$token = trim((string) ($_GET['token'] ?? ''));
$account = $token === '' ? false : $db->get_row(
    "SELECT `id` FROM `users` WHERE `token` = '" . $db->escape($token) . "' AND `banned` = 0 LIMIT 1"
);
if (!$account) {
    http_response_code(404);
    new Redirect('/login');
    exit();
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác minh 2FA</title>
    <link rel="icon" href="<?= htmlspecialchars((string) $db->site('favicon'), ENT_QUOTES, 'UTF-8') ?>">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Arial, sans-serif; background: #f3f4f6; color: #111827; }
        main { width: min(420px, calc(100% - 32px)); background: #fff; padding: 32px; border-radius: 14px; box-shadow: 0 12px 35px rgba(0,0,0,.1); }
        h1 { margin: 0 0 10px; font-size: 24px; }
        p { color: #6b7280; line-height: 1.5; }
        input, button { width: 100%; height: 48px; border-radius: 9px; font-size: 16px; }
        input { border: 1px solid #d1d5db; padding: 0 14px; margin: 12px 0; letter-spacing: 4px; }
        button { border: 0; background: #dc2626; color: #fff; font-weight: 700; cursor: pointer; }
        button:disabled { opacity: .65; cursor: wait; }
        #message { min-height: 22px; margin-top: 14px; color: #dc2626; }
    </style>
</head>
<body>
<main>
    <h1>Xác minh hai bước</h1>
    <p>Nhập mã gồm 6 chữ số trong ứng dụng xác thực của bạn.</p>
    <form id="verify-form">
        <input name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" placeholder="000000" required autofocus>
        <button type="submit">Xác minh</button>
    </form>
    <div id="message" role="alert"></div>
</main>
<script>
document.getElementById('verify-form').addEventListener('submit', async function (event) {
    event.preventDefault();
    const button = this.querySelector('button');
    const message = document.getElementById('message');
    button.disabled = true;
    message.textContent = '';
    try {
        const body = new FormData(this);
        body.append('type', 'VerifyGoogle2FA');
        body.append('token', <?= json_encode($token, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        const response = await fetch('/model/authenticator', { method: 'POST', body });
        const result = await response.json();
        if (result.status === 'success') {
            window.location.href = '/';
            return;
        }
        message.textContent = result.msg || 'Không thể xác minh mã.';
    } catch (error) {
        message.textContent = 'Máy chủ không phản hồi hợp lệ. Vui lòng thử lại.';
    } finally {
        button.disabled = false;
    }
});
</script>
</body>
</html>
