<?php
// statically decompiled from login.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
    exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
} else {
    if (empty($_POST['account-login'])) {
        exit(jsonMsg('error', 'Vui lòng nhập username'));
    } else {
        if (empty($_POST['password-login'])) {
            exit(jsonMsg('error', 'Vui lòng nhập mật khẩu'));
        } else {
            $username = Anti_xss($_POST['account-login']);
            $password = sha1(Anti_xss($_POST['password-login']));
            if ($db->site('status_captcha') == 1) {
                if (empty($_POST['g-recaptcha-response'])) {
                    exit(JsonMsg('error', 'Vui lòng xác thực captcha'));
                } else {
                    $secret = $db->site('secret_key');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $response = Anti_xss($_POST['g-recaptcha-response']);
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response . '&remoteip=' . $ip;
                    $fire = curl_get($url);
                    $data = json_decode($fire);
                    if (!$data->success) {
                        exit(JsonMsg('error', 'Vui lòng xác thực captcha'));
                    } else {
                        $getUser = $db->get_row(' SELECT * FROM `users` WHERE `username` = \'' . $username . '\' ');
                        if (!$getUser) {
                            exit(JsonMsg('error', 'Tài khoản hoặc mật khẩu không chính xác'));
                        } else {
                            $users = $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $username . '\' AND `password` = \'' . $password . '\'');
                            if (!$users) {
                                if (5 <= $getUser['login_attempts']) {
                                    $db->update('users', ['banned' => 1, 'device' => $_SERVER['HTTP_USER_AGENT']], ' `id` = \'' . $getUser['id'] . '\' ');
                                    $db->insert('logs', ['user_id' => $getUser['id'], 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'create_date' => gettime(), 'action' => 'Tài khoản của bạn đã bị tạm khoá do đang nhập sai nhiều lần']);
                                    exit(JsonMsg('error', 'Tài khoản của bạn đã bị tạm khoá do đang nhập sai nhiều lần'));
                                } else {
                                    $db->cong('users', 'login_attempts', 1, ' `id` = \'' . $getUser['id'] . '\' ');
                                    exit(JsonMsg('error', 'Tài khoản hoặc mật khẩu không chính xác'));
                                }
                            } else {
                                if ($users['banned'] == 1) {
                                    exit(JsonMsg('error', 'Tài khoản của bạn đã bị tạm khoá, liên hệ admin để hỗ trợ'));
                                } else {
                                    if ($users['status_2fa'] == 1) {
                                        exit(json_encode(['status' => 'verify', 'url' => '/verify/' . $users['token'], 'msg' => 'Vui lòng xác minh 2FA để hoàn thành đăng nhập']));
                                    } else {
                                        $db->update('users', ['login_attempts' => 0, 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'time_session' => time()], ' `id` = \'' . $getUser['id'] . '\' ');
                                        insert_log($users['id'], 'Đăng nhập vào hệ thống bằng phương thức tài khoản');
                                        echo JsonMsg('success', 'Đăng nhập thành công!');
                                        $session->send($username);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
