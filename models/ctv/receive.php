<?php
// statically decompiled from receive.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($data_user['ctv'] != 1) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($data_user['ctv_boosting'] != 1) {
                exit(JsonMsg('error', 'Bạn không có quyền thực hiện chức năng cày thuê'));
            } else {
                if ($db->site('status_demo') != 0) {
                    exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
                } else {
                    $action = Anti_xss($_POST['action']);
                    if (empty($action)) {
                        exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                    } else {
                        switch ($action) {
                            case 'receive':
                                $id = Anti_xss($_POST['id']);
                                if (empty($id)) {
                                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                                } else {
                                    $check_account = $db->get_row('SELECT * FROM `orders` WHERE `id` = ' . $id . ' AND `receiver` IS NULL AND `status` = \'pending\'');
                                    if (!$check_account) {
                                        exit(JsonMsg('error', 'Đơn không tồn tại hoặc đã có người nhận'));
                                    } else {
                                        $db->update('orders', ['receiver' => $data_user['username']], ' `id` = \'' . $id . '\' ');
                                        exit(JsonMsg('success', 'Đã nhận đơn hàng thành công'));
                                    }
                                }
                            case 'update':
                                break;
                            case 'delete':
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
