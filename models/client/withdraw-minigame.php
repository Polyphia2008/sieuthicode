<?php
// statically decompiled from withdraw-minigame.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($db->site('status_demo') != 0) {
            exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
        } else {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
                exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
            } else {
                if (empty($_POST['vatpham'])) {
                    exit(JsonMsg('error', 'Vui lòng chọn vật phẩm cần rút'));
                } else {
                    if (empty($_POST['select-goimuonrut'])) {
                        exit(JsonMsg('error', 'Vui lòng chọn gói cần rút'));
                    } else {
                        $type = Anti_xss($_POST['vatpham']);
                        $unit = $db->get_row('SELECT * FROM `units` WHERE `id` = \'' . $type . '\' AND `status` = 1');
                        if (!$unit) {
                            exit(JsonMsg('error', 'Không tìm thấy vật phẩm'));
                        } else {
                            $pack = Anti_xss($_POST['select-goimuonrut']);
                            $package = $db->get_row('SELECT * FROM `package_units` WHERE `unit_id` = \'' . $type . '\' AND `id` = \'' . $pack . '\' AND `status` = 1');
                            if (!$package) {
                                exit(JsonMsg('error', 'Không tìm thấy gói vật phẩm'));
                            } else {
                                if ($data_user['coin'] < $package['value']) {
                                    exit(JsonMsg('error', 'Bạn không đủ ' . $package['name'] . ' để rút, vui lòng chơi thêm'));
                                } else {
                                    $arr_query = json_decode($unit['detail'], true);
                                    $amount = Anti_xss(preg_replace('/\\D/', '', $package['value']));
                                    $trans_id = random('123456789QWERTYUIOPASDFGHJKLZXCVBNM', 6);
                                    $isTru = $db->tru('users', 'coin', $amount, ' `id` = \'' . $data_user['id'] . '\' ');
                                    if ($isTru) {
                                        if (getRowUser($data_user['id'], 'coin') < 0) {
                                            Banned($data_user['id'], 'Gian lận khi rút số dư');
                                            exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                        } else {
                                            $arr_data = [];
                                            $a = 2;
                                            while ($a < count($arr_query['data'])) {
                                                $value = Anti_xss($_POST['fields_' . $a]);
                                                $label = $arr_query['data'][$a]['label'];
                                                $arr_data[] = ['id' => $a, 'label' => $label, 'value' => $value];
                                                ++$a;
                                            }
                                            $json['author'] = 'SIEUTHICODE.NET';
                                            $json['name_product'] = $arr_query['name_product'];
                                            $json['name_package'] = $package['name'];
                                            $json['data'] = $arr_data;
                                            $full_json = json_encode($json);
                                            $trans_id = strtoupper('GD' . substr(md5(uniqid(mt_rand(), true)), 0, 16));
                                            $isInsert = $db->insert('withdraw_logs', ['trans_id' => $trans_id, 'unit_id' => $type, 'user_id' => $data_user['id'], 'username' => $data_user['username'], 'value' => $amount, 'detail' => $full_json, 'current_balance' => $data_user['coin'] - $amount, 'status' => 'pending', 'created_at' => gettime(), 'updated_at' => gettime()]);
                                            if ($isInsert) {
                                                if ($db->site('telegram_status') == 1) {
                                                    $message = '🎮 THÔNG BÁO RÚT VẬT PHẨM 🎮
';
                                                    $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                                    $message .= '👤 Người rút: ' . $data_user['username'] . '
';
                                                    $message .= '🆔 Mã đơn hàng: ' . $trans_id . '
';
                                                    $message .= '🎯 Tên game: ' . $arr_query['name_product'] . '
';
                                                    $message .= '📦 Gói: ' . $package['name'] . '
';
                                                    $message .= '📅 Thời gian: ' . gettime() . '
';
                                                    $message .= '📩 Vui lòng kiểm tra đơn của bạn!';
                                                    sendMessAdmin($message);
                                                }
                                                exit(JsonMsg('success', 'Tạo yêu cầu rút thành công, vui lòng đợi ADMIN xử lý'));
                                            } else {
                                                exit(JsonMsg('error', 'ERROR 1 - Phát hiện lỗi khi rút, vui lòng liên hệ ADMIN'));
                                            }
                                        }
                                    } else {
                                        exit(JsonMsg('error', 'ERROR 2 - Phát hiện lỗi khi rút, vui lòng liên hệ ADMIN'));
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
