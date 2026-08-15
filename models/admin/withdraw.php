<?php
// statically decompiled from withdraw.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($user) {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        if (!is_admin_account($data_user)) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $id = Anti_xss($_POST['id']);
                $status = Anti_xss($_POST['status']);
                $note = Anti_xss($_POST['note']);
                $row = $db->get_row('SELECT * FROM `withdraw_ref` WHERE `id` = \'' . $id . '\' ');
                if (!$row) {
                    exit(JsonMsg('error', 'ID lịch sử không tồn tại trong hệ thống!'));
                } else {
                    $isUpdate = $db->update('withdraw_ref', ['status' => $status, 'reason' => $note, 'update_gettime' => gettime()], ' `id` = \'' . $row['id'] . '\' ');
                    if ($isUpdate) {
                        $db->insert('logs', ['user_id' => $data_user['id'], 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'create_date' => gettime(), 'action' => 'Chỉnh sửa trạng thái lịch sử rút tiền CTV (ID ' . $row['id'] . ')']);
                        exit(JsonMsg('success', 'Cập nhật thành công'));
                    } else {
                        exit(JsonMsg('error', 'Cập nhật thất bại'));
                    }
                }
            }
        }
    } else {
        exit(JsonMsg('error', 'Dữ liệu không hợp lệ!'));
    }
} else {
    exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
}
