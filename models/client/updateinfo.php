<?php
// statically decompiled from updateinfo.php  [structured; all 2 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                if (time() - $data_user['time_session'] < 60) {
                    exit(JsonMsg('error', 'Vui lòng thử lại sau 60 giây'));
                } else {
                    $email = Anti_xss($_POST['email'] ?? '');
                    $name = Anti_xss($_POST['name'] ?? '');
                    $phone = Anti_xss($_POST['phone'] ?? '');
                    function validateInput($field, $message)
{
    if (empty($field)) {
        exit(JsonMsg('error', $message));
    }
}
                    validateInput($email, 'Vui lòng nhập email');
                    if (!check_email($email)) {
                        exit(JsonMsg('error', 'Email không đúng định dạng'));
                    } else {
                        validateInput($phone, 'Vui lòng nhập số điện thoại');
                        if (!isValidPhoneNumber($phone)) {
                            exit(JsonMsg('error', 'Số điện thoại không hợp lệ'));
                        } else {
                            validateInput($name, 'Vui lòng nhập họ và tên');
                            if ($data_user['email'] != $email) {
                                if (0 < $db->num_rows('SELECT * FROM `users` WHERE `email` = \'' . $email . '\'')) {
                                    exit(JsonMsg('error', 'Email này đã tồn tại trên hệ thống'));
                                } else {
                                    $isUpdate = $db->update('users', ['email' => $email, 'name' => $name, 'phone' => $phone, 'time_session' => time()], ' `id` = \'' . $data_user['id'] . '\' ');
                                    if ($isUpdate) {
                                        insert_log($data_user['id'], 'Cập nhật thông tin cá nhân');
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
        }
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
