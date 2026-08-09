<?php
// statically decompiled from register.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
    exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
} else {
    if (empty($_POST['email-register'])) {
        exit(jsonMsg('error', 'Vui lòng nhập email'));
    } else {
        if (empty($_POST['username-register'])) {
            exit(jsonMsg('error', 'Vui lòng nhập tài khoản'));
        } else {
            if (empty($_POST['password-register'])) {
                exit(jsonMsg('error', 'Vui lòng nhập mật khẩu'));
            } else {
                if (empty($_POST['passwordcf-register'])) {
                    exit(jsonMsg('error', 'Vui lòng nhập lại mật khẩu'));
                } else {
                    $email = Anti_xss($_POST['email-register']);
                    $username = Anti_xss($_POST['username-register']);
                    $password = Anti_xss($_POST['password-register']);
                    $passwordcf = Anti_xss($_POST['passwordcf-register']);
                    $Cuser = '/^[A-Za-z0-9_.]{3,32}$/';
                    $ipRegister = myip();
                    if ($db->site('status_demo') != 0) {
                        exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
                    } else {
                        if (!check_email($email)) {
                            exit(JsonMsg('error', 'Email không đúng định dạng'));
                        } else {
                            if (0 < $db->num_rows('SELECT * FROM `users` WHERE `email` = \'' . $email . '\'')) {
                                exit(JsonMsg('error', 'Email này đã tồn tại trên hệ thống'));
                            } else {
                                if (!preg_match($Cuser, $username)) {
                                    exit(JsonMsg('error', 'Tên đăng nhập phải là chữ hoặc số, bao gồm dấu gạch ngang "-" và gạch dưới "_"'));
                                } else {
                                    if (strlen($username) <= 4) {
                                        exit(JsonMsg('error', 'Tên đăng nhập có nhiều hơn 4 ký tự trở lên'));
                                    } else {
                                        if (0 < $db->num_rows('SELECT * FROM `users` WHERE `username` = \'' . $username . '\'')) {
                                            exit(JsonMsg('error', 'Tên đăng nhập này đã tồn tại trên hệ thống'));
                                        } else {
                                            if (strlen($password) < 6) {
                                                exit(JsonMsg('error', 'Mật khẩu có nhiều hơn 6 ký tự trở lên'));
                                            } else {
                                                if ($username == $password) {
                                                    exit(JsonMsg('error', 'Không được đặt mật khẩu giống tài khoản đăng nhập'));
                                                } else {
                                                    if ($password != $passwordcf) {
                                                        exit(JsonMsg('error', 'Xác thực lại mật khẩu không đúng'));
                                                    } else {
                                                        if (5 < $db->num_rows('SELECT * FROM `users` WHERE `ip` = \'' . myip() . '\' ')) {
                                                            exit(JsonMsg('error', 'IP của bạn đã đạt đến giới hạn tạo tài khoản cho phép'));
                                                        } else {
                                                            $realPass = sha1($password);
                                                            $google2fa = new PragmaRX\\Google2FA\\Google2FA();
                                                            $isInsert = $db->insert('users', ['username' => $username, 'password' => $realPass, 'email' => $email, 'level' => 'member', 'device' => $_SERVER['HTTP_USER_AGENT'], 'ip' => myip(), 'ref_id' => !empty($_SESSION['ref']) ? $_SESSION['ref'] : 0, 'token' => md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time()), 'SecretKey' => $google2fa->generateSecretKey(), 'create_date' => gettime()]);
                                                            echo JsonMsg('success', 'Đăng ký thành công');
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
                }
            }
        }
    }
}
