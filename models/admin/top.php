<?php
// statically decompiled from top.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (!is_admin_account($data_user)) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $user = Anti_xss($_POST['user']);
                if (empty($user)) {
                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                } else {
                    $check_user = $db->get_row('SELECT * FROM `top` WHERE `username` = \'' . $user . '\'');
                    if (!$check_user) {
                        exit(JsonMsg('error', 'Người dùng không tồn tại'));
                    } else {
                        $isRemove = $db->remove('top', ' `username` = \'' . $user . '\' ');
                        if ($isRemove) {
                            insert_log($data_user['id'], 'Thực hiện xóa top nạp [' . $check_user['username'] . '] ra khỏi hệ thống');
                            exit(JsonMsg('success', 'Xóa top nạp thành công'));
                        } else {
                            exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa top nạp'));
                        }
                    }
                }
            }
        }
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
