<?php
// statically decompiled from buyaccount.php  [structured; all 2 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if (!isset($_POST['id'])) {
                exit(JsonMsg('error', 'Quý khách cần chọn tài khoản để thanh toán'));
            } else {
                $id = Anti_xss($_POST['id']);
                if (empty($id)) {
                    exit(JsonMsg('error', 'Quý khách cần chọn tài khoản để thanh toán'));
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
                                    $ck = $getUser['chietkhau'];
                                    $row = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\'');
                                    if (!$row) {
                                        exit(JsonMsg('error', 'Không tìm thấy tài khoản!'));
                                    } else {
                                        if ($row['status'] === 'off') {
                                            exit(JsonMsg('error', 'Tài khoản này đã bán, vui lòng tìm tài khoản khác!'));
                                        } else {
                                            $total = $row['money'] - $row['money'] * $row['sale'] / 100;
                                            $total -= $total * $ck / 100;
                                            $total = $total - getProductFlashSale($id)['discountPrice'];
                                            if (!is_numeric($total) || $total < 0) {
                                                exit(JsonMsg('error', 'Dữ liệu không hợp lệ'));
                                            } else {
                                                if ($data_user['money'] < $total) {
                                                    exit(JsonMsg('error', 'Số dư của bạn không đủ ' . format_cash($total) . 'đ, vui lòng nạp thêm để thực hiện'));
                                                } else {
                                                    $detail = json_decode($row['detail'], true);
                                                    $name_product = isset($detail['name_product']) ? $detail['name_product'] : '';
                                                    $isMoney = RemoveCredits($data_user['id'], $total, 'Mua tài khoản #' . $id . ' ' . $name_product);
                                                    if ($isMoney) {
                                                        if (getRowRealtime('users', $data_user['id'], 'money') < -500) {
                                                            Banned($data_user['id'], 'Gian lận khi mua tài khoản');
                                                            exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                        } else {
                                                            $trans_id = strtoupper('GD' . substr(md5(uniqid(mt_rand(), true)), 0, 16));
                                                            $arr_data = array_map(function ($item, $i) {
    return ['id' => $i, 'label' => $item['label'], 'type' => $item['type'], 'name' => $item['name'], 'value' => $item['value'], 'show' => $item['show'], $item['name'] => $item['value']];
}, $detail['data'], array_keys($detail['data']));
                                                            $json = ['name_product' => $name_product, 'data' => $arr_data];
                                                            $full_detail = addslashes(json_encode($json));
                                                            $db->update('accounts', ['status' => 'off', 'updated_at' => time()], ' `id` = \'' . $id . '\'');
                                                            $date = time();
                                                            $categoryId = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $row['type_category'] . '\'')['id'] ?? 0;
                                                            $realMoney = $total - $total * ($db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $row['username_post'] . '\' AND `banned` = 0')['chietkhau_banacc'] ?? 0) / 100;
                                                            $db->query('INSERT INTO `history_buy`(`id_acc`,`trans_id`,`category_id`,`type_category`, `username`, `username_post`, `detail`, `cash`,`cost`, `updated_at`, `created_at`) VALUES (\'' . $id . '\',\'' . $trans_id . '\',\'' . $categoryId . '\',\'' . $row['type_category'] . '\', \'' . $getUser['username'] . '\', \'' . $row['username_post'] . '\', \'' . $full_detail . '\', \'' . $total . '\',\'' . $realMoney . '\', \'' . $date . '\', \'' . $date . '\')');
                                                            $db->query('UPDATE `users` SET `cost` = `cost` + ' . $realMoney . ' WHERE `username` = \'' . $row['username_post'] . '\'');
                                                            $db->query('UPDATE `users` SET `transacted` = `transacted` + 1 WHERE `username` = \'' . $row['username_post'] . '\'');
                                                            if ($db->site('telegram_status') == 1) {
                                                                $message = '🎮 THÔNG BÁO MUA TÀI KHOẢN 🎮
';
                                                                $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                                                $message .= '👤 Người mua: ' . $data_user['username'] . '
';
                                                                $message .= '🆔 Mã đơn hàng: ' . $trans_id . '
';
                                                                $message .= '🎯 Tên game: ' . $name_product . '
';
                                                                $message .= '💰 Giá: ' . format_cash($total) . 'đ
';
                                                                $message .= '📅 Thời gian: ' . gettime() . '
';
                                                                $message .= '📩 Vui lòng kiểm tra tài khoản của bạn!';
                                                                sendMessAdmin($message);
                                                            }
                                                            insert_log($data_user['id'], 'Mua tài khoản #' . $id . ' ' . $name_product);
                                                            $money = getRowUser($getUser['id'], 'money');
                                                            $cost = getRowUser($getUser['id'], 'cost');
                                                            removeViewedProduct($id);
                                                            exit(json_encode(['status' => 'success', 'msg' => 'Thanh toán thành công!', 'money' => $money, 'coin' => $cost]));
                                                        }
                                                    } else {
                                                        exit(JsonMsg('error', 'Đã xảy ra lỗi, vui lòng liên hệ admin'));
                                                    }
                                                }
                                            }
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
