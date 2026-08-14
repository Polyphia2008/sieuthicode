<?php
// statically decompiled from forgotpasword.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
    } else {
        if ($db->site('status_demo') != 0) {
            exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
        } else {
            $email = Anti_xss($_POST['email']);
            if (empty($email)) {
                exit(JsonMsg('error', 'Vui lòng nhập email'));
            } else {
                if (!check_email($email)) {
                    exit(JsonMsg('error', 'Email không đúng định dạng'));
                } else {
                    $getUser = $db->get_row(' SELECT * FROM `users` WHERE `email` = \'' . $email . '\' ');
                    if (!$getUser) {
                        exit(JsonMsg('error', 'Địa chỉ Email này không tồn tại trong hệ thống'));
                    } else {
                        if (time() - $getUser['time_session'] < 300) {
                            exit(JsonMsg('error', 'Bạn thao tác quá nhanh vui lòng thử lại sau 5 phút'));
                        } else {
                            if ($db->site('pass_email_smtp') == '' || $db->site('email_smtp') == '') {
                                exit(JsonMsg('error', 'SMTP chưa được cấu hình'));
                            } else {
                                $token = md5(random('QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time());
                                $body = 'Nếu bạn yêu cầu đặt lại mật khẩu, mật khẩu mới bên dưới.<br>';
                                $body .= '<p>Mật khẩu mới là: ' . $token . '</p><br>';
                                $body .= '<p>Nếu không phải là bạn, vui lòng liên hệ ngay với Quản trị viên của bạn để được hỗ trợ về bảo mật.</p>';
                                $chu_de = 'Khôi phục lại mật khẩu - ' . $db->site('title');
                                ob_start();
                                require APP_ROOT . '/libs/mails/notification.php';
                                $content = ob_get_clean();
                                $content = str_replace('{title}', 'Xác nhận khôi phục mật khẩu', $content);
                                $content = str_replace('{content}', $body, $content);
                                $bcc = $db->site('title');
                                sendCSM($getUser['email'], $getUser['username'], $chu_de, $content, $bcc);
                                $isUpdate = $db->update('users', ['password' => sha1($token), 'time_session' => time()], ' `id` = \'' . $getUser['id'] . '\' ');
                                if ($isUpdate) {
                                    // Reset mật khẩu: thu hồi TẤT CẢ persistent token của user
                                    // (đăng xuất mọi thiết bị) để phiên cũ không thể dùng tiếp.
                                    auth_token_revoke_all($db, (int) $getUser['id']);
                                    exit(JsonMsg('success', 'Vui lòng kiểm tra Email của bạn để hoàn tất quá trình đặt lại mật khẩu'));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
