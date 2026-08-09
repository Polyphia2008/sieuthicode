<?php
// statically decompiled from card.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if ($db->site('status_demo') != 0) {
            exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
        } else {
            if ($db->site('card_status') == 0) {
                exit(JsonMsg('error', 'Chức năng nạp thẻ đang bảo trì'));
            } else {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
                    exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
                } else {
                    if (empty($_POST['telco'])) {
                        exit(JsonMsg('error', 'Vui lòng chọn nhà mạng'));
                    } else {
                        if (empty($_POST['amount'])) {
                            exit(JsonMsg('error', 'Vui lòng chọn mệnh giá cần nạp'));
                        } else {
                            if ($_POST['amount'] < 0) {
                                exit(JsonMsg('error', 'Vui lòng chọn mệnh giá cần nạ'));
                            } else {
                                if (empty($_POST['serial'])) {
                                    exit(JsonMsg('error', 'Vui lòng nhập serial thẻ'));
                                } else {
                                    if (empty($_POST['pin'])) {
                                        exit(JsonMsg('error', 'Vui lòng nhập mã thẻ'));
                                    } else {
                                        if (empty($_POST['captcha'])) {
                                            exit(JsonMsg('error', 'Vui lòng nhập mã bảo vệ'));
                                        } else {
                                            $sessionCaptcha = $_SESSION['captcha'] ?? '';
                                            if (trim($_POST['captcha']) != $sessionCaptcha) {
                                                exit(JsonMsg('error', 'Mã bảo vệ không hợp lệ'));
                                            } else {
                                                $telco = Anti_xss($_POST['telco']);
                                                $amount = Anti_xss($_POST['amount']);
                                                $serial = Anti_xss($_POST['serial']);
                                                $pin = Anti_xss($_POST['pin']);
                                                if (!checkFormatCard($telco, $serial, $pin)['status']) {
                                                    exit(JsonMsg('error', checkFormatCard($telco, $serial, $pin)['msg']));
                                                } else {
                                                    if (5 < $db->num_rows(' SELECT * FROM `cards` WHERE `user_id` = \'' . $data_user['id'] . '\' AND `status` = \'pending\'  ')) {
                                                        exit(JsonMsg('error', 'Vui lòng không spam!'));
                                                    } else {
                                                        if (5 < $db->num_rows('SELECT * FROM `cards` WHERE `status` = \'error\' AND `user_id` = \'' . $data_user['id'] . '\' AND `create_date` >= DATE(NOW()) AND `create_date` < DATE(NOW()) + INTERVAL 1 DAY  ') - $db->num_rows('SELECT * FROM `cards` WHERE `status` = \'complted\' AND `user_id` = \'' . $data_user['id'] . '\' AND `create_date` >= DATE(NOW()) AND `create_date` < DATE(NOW()) + INTERVAL 1 DAY  ')) {
                                                            exit(JsonMsg('error', 'Bạn đã bị chặn sử dụng chức năng nạp thẻ trong 1 ngày'));
                                                        } else {
                                                            $trans_id = random('QWERTYUIOPASDFGHJKLZXCVBNM', 6) . time();
                                                            $data = napthe($telco, $amount, $serial, $pin, $trans_id);
                                                            if ($data['status'] == 99) {
                                                                $isInsert = $db->insert('cards', ['trans_id' => $trans_id, 'telco' => $telco, 'amount' => $amount, 'serial' => $serial, 'pin' => $pin, 'price' => 0, 'user_id' => $data_user['id'], 'status' => 'pending', 'reason' => '', 'create_date' => gettime(), 'update_date' => gettime()]);
                                                                if ($isInsert) {
                                                                    $db->update('users', ['time_request' => time()], ' `id` = \'' . $data_user['id'] . '\' ');
                                                                    insert_log($data_user['id'], 'Thực hiện nạp thẻ Serial: ' . $serial . ' - Pin: ' . $pin);
                                                                    exit(JsonMsg('success', 'Gửi thẻ thành công, đợi hệ thống xử lý trong giây lát'));
                                                                } else {
                                                                    exit(JsonMsg('error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin'));
                                                                }
                                                            } else {
                                                                if ($data['status'] == 3 && $data['message'] == 'PENDING') {
                                                                    $isInsert = $db->insert('cards', ['trans_id' => $trans_id, 'telco' => $telco, 'amount' => $amount, 'serial' => $serial, 'pin' => $pin, 'price' => 0, 'user_id' => $data_user['id'], 'status' => 'pending', 'reason' => '', 'create_date' => gettime(), 'update_date' => gettime()]);
                                                                    if ($isInsert) {
                                                                        $db->update('users', ['time_request' => time()], ' `id` = \'' . $data_user['id'] . '\' ');
                                                                        insert_log($data_user['id'], 'Thực hiện nạp thẻ Serial: ' . $serial . ' - Pin: ' . $pin);
                                                                        exit(JsonMsg('success', 'Gửi thẻ thành công, đợi hệ thống xử lý trong giây lát'));
                                                                    } else {
                                                                        exit(JsonMsg('error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin'));
                                                                    }
                                                                } else {
                                                                    exit(JsonMsg('error', $data['message']));
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
    }
}
