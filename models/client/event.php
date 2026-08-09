<?php
// statically decompiled from event.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
} else {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            exit(JsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                if ($db->site('status_event') != 1) {
                    exit(JsonMsg('error', 'Chức năng bảo trì'));
                } else {
                    if ($data_user['gift'] == 1) {
                        exit(JsonMsg('error', 'Bạn đã nhận quà rồi, không thể nhận thêm'));
                    } else {
                        if ($data_user['money'] < $db->site('balance')) {
                            exit(JsonMsg('error', 'Số dư bạn không hợp lệ để tham gia sự kiện'));
                        } else {
                            $min = $db->site('min');
                            $max = $db->site('max');
                            $amount = rand($min, $max);
                            $unit = $db->site('unit');
                            $successMessage = 'Chúc mừng bạn đã nhận được ' . $amount . ' ' . $unit . ' từ sự kiện';
                            $logMessage = 'Nhận ' . $amount . ' ' . $unit . ' từ sự kiện lúc ' . gettime();
                            $db->query('UPDATE `users` SET `gift` = 1, `coin` = `coin` + ' . $amount . ' WHERE `username` = \'' . $data_user['username'] . '\'');
                            insert_log($data_user['id'], $logMessage);
                            exit(JsonMsg('success', $successMessage));
                        }
                    }
                }
            }
        }
    }
}
