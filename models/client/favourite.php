<?php
// statically decompiled from favourite.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                if (!isset($_POST['id'])) {
                    exit(JsonMsg('error', 'Quý khách cần chọn tài khoản để thêm vào giỏ'));
                } else {
                    $id = Anti_xss($_POST['id']);
                    if (empty($id)) {
                        exit(JsonMsg('error', 'Quý khách cần chọn tài khoản để thêm vào giỏ'));
                    } else {
                        if ($db->site('status_demo') == 1) {
                            exit(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
                        } else {
                            try {
                                $getUser = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $data_user['id'] . '\' AND `banned` = 0');
                                if (!$getUser) {
                                    exit(JsonMsg('error', 'Người dùng không tồn tại hoặc đã bị cấm.'));
                                } else {
                                    if ($getUser['level'] === 'ctv') {
                                        exit(JsonMsg('error', 'Cộng tác viên không được thực hiện mua tài khoản'));
                                    } else {
                                        $account = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\' AND `status` = \'on\' ');
                                        if (!$account) {
                                            exit(JsonMsg('error', 'Tài khoản không tồn tại'));
                                        } else {
                                            $isFavorite = $db->get_row(' SELECT * FROM `favorites` WHERE `user_id` = \'' . $data_user['id'] . '\' AND `acc_id` = \'' . $account['id'] . '\' ');
                                            if ($isFavorite) {
                                                $db->remove('favorites', ' `id` = \'' . $isFavorite['id'] . '\' ');
                                                exit(JsonMsg('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích'));
                                            } else {
                                                $db->insert('favorites', ['acc_id' => $account['id'], 'user_id' => $data_user['id'], 'created_at' => gettime()]);
                                                exit(JsonMsg('success', 'Đã thêm sản phẩm vào danh sách yêu thích'));
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                exit(JsonMsg('error', 'Đã xảy ra lỗi ngoại lệ'));
                            }
                        }
                    }
                }
            }
        }
    }
}
