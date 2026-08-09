<?php
// statically decompiled from updatepassword.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $current_password = sha1(Anti_xss($_POST['old-password']));
                $new_password = Anti_xss($_POST['new-password']);
                $confirm_password = Anti_xss($_POST['new-password-2']);
                $Cuser = '/^[A-Za-z0-9_.]{3,32}$/';
                if (empty($new_password) || empty($new_password) || empty($confirm_password)) {
                    exit(JsonMsg('error', 'Vui lòng nhập đầy đủ thông tin'));
                } else {
                    if (strlen($new_password) < 6 || strlen($confirm_password) < 6) {
                        exit(JsonMsg('error', 'Mật khẩu phải từ 6 ký tự trở lên'));
                    } else {
                        if (time() - $data_user['time_session'] < 60) {
                            exit(JsonMsg('error', 'Vui lòng thử lại sau 60 giây'));
                        } else {
                            if ($db->num_rows('SELECT * FROM `users` WHERE `username` = \'' . $data_user['username'] . '\' AND `password` = \'' . $current_password . '\'') == 0) {
                                exit(JsonMsg('error', 'Mật khẩu hiện tại không chính xác'));
                            } else {
                                $isUpdate = $db->update('users', ['password' => sha1($new_password), 'time_session' => time()], ' `id` = \'' . $data_user['id'] . '\' ');
                                if ($isUpdate) {
                                    insert_log($data_user['id'], 'Cập nhật mật khẩu cá nhân');
                                    $session->destroy();
                                    if (isset($_COOKIE['remember_me'])) {
                                        $token = Anti_xss($_COOKIE['remember_me']);
                                        setcookie('remember_me', '', time() - 3600, '/', '', true, true);
                                        $sql = 'DELETE FROM auth_tokens WHERE token = ' . $token;
                                        $pdo->query($sql);
                                    }
                                    exit(JsonMsg('success', 'Cập nhật thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi cập nhập dữ liệu'));
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
