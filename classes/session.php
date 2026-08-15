<?php

class Session
{
    public function start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
            || strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')[0])) === 'https';

        // Persistent login tối đa 7 ngày: GC phải giữ session ít nhất bằng thời hạn
        // remember token, nếu không session server-side bị dọn trước khi token hết hạn.
        if (!defined('AUTH_SESSION_MAX_TTL')) {
            define('AUTH_SESSION_MAX_TTL', 604800);
        }
        $gcMax = (int) ini_get('session.gc_maxlifetime');
        if ($gcMax < AUTH_SESSION_MAX_TTL) {
            ini_set('session.gc_maxlifetime', (string) AUTH_SESSION_MAX_TTL);
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public function send($user)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $this->start();
        }
        session_regenerate_id(true);
        $_SESSION['user'] = (string) $user;
        // Thời hạn phiên server-side tuyệt đối: mặc định 2 ngày kể từ lúc đăng nhập.
        // Luồng remember (7 ngày) ghi đè bằng expires_at tuyệt đối của token.
        if (!defined('AUTH_TOKEN_TTL_DEFAULT')) {
            define('AUTH_TOKEN_TTL_DEFAULT', 172800);
        }
        $_SESSION['auth_expires_at'] = time() + AUTH_TOKEN_TTL_DEFAULT;
    }

    public function get()
    {
        return $_SESSION['user'] ?? '';
    }

    public function destroy()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
