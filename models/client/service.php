<?php
// statically decompiled from service.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if ($db->site('status_demo') != 0) {
            exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
        } else {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
                exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
            } else {
                if (empty($_POST['package'])) {
                    exit(JsonMsg('error', 'Vui lòng nhập đủ thông tin'));
                } else {
                    $package = Anti_xss($_POST['package']);
                    try {
                        $getUser = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $data_user['id'] . '\' AND `banned` = 0');
                        if (!$getUser) {
                            exit(JsonMsg('error', 'Người dùng không tồn tại hoặc đã bị cấm.'));
                        } else {
                            if ($getUser['level'] === 'ctv') {
                                exit(JsonMsg('error', 'Cộng tác viên không được thực hiện chức năng này'));
                            } else {
                                $ck = $getUser['chietkhau'];
                                $row = $db->get_row('SELECT * FROM `package_boostings` WHERE `id` = \'' . $package . '\'');
                                if (!$row) {
                                    exit(JsonMsg('error', 'Không tìm thấy gói dịch vụ!'));
                                } else {
                                    $sub = $db->get_row('SELECT * FROM `subboostings` WHERE `id` = \'' . $row['sub_id'] . '\'');
                                    if (!$sub) {
                                        exit(JsonMsg('error', 'Không tìm thấy nhóm dịch vụ!'));
                                    } else {
                                        $arr_query = json_decode($sub['detail'], true);
                                        $total = $row['price'];
                                        $total -= $total * $ck / 100;
                                        if ($total < 0) {
                                            exit(JsonMsg('error', 'Dữ liệu không hợp lệ'));
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
                                            $json['package'] = $row['name'];
                                            $json['data'] = $arr_data;
                                            $full_json = json_encode($json);
                                            if ($data_user['money'] < $total) {
                                                exit(JsonMsg('error', 'Số dư của bạn không đủ ' . format_cash($total) . 'đ, vui lòng nạp thêm để thực hiện'));
                                            } else {
                                                $isMoney = RemoveCredits($data_user['id'], $total, 'Mua gói #' . $row['name']);
                                                if ($isMoney) {
                                                    if (getRowRealtime('users', $data_user['id'], 'money') < -500) {
                                                        Banned($data_user['id'], 'Gian lận khi mua dịch vụ');
                                                        exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                    } else {
                                                        $transid = random('QWERTYUOPASDFGHJKZXCVBNM123456789', 3) . uniqid();
                                                        $db->insert('orders', ['user_id' => $data_user['id'], 'username' => $data_user['username'], 'detail' => $full_json, 'name' => $row['name'], 'code' => $transid, 'payment' => $total, 'package_id' => $package, 'sub_id' => $row['sub_id'], 'name_sub' => $arr_query['name_product'], 'created_at' => gettime(), 'updated_at' => gettime()]);
                                                        if ($db->site('telegram_status') == 1) {
                                                            $message = '📢 THÔNG BÁO THUÊ DỊCH VỤ 📢
';
                                                            $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                                            $message .= '👤 Người dùng: ' . $data_user['username'] . '
';
                                                            $message .= '🆔 Mã giao dịch: ' . $transid . '
';
                                                            $message .= '🛒 Dịch vụ: ' . $arr_query['name_product'] . '
';
                                                            $message .= '📦 Gói: ' . $row['name'] . '
';
                                                            $message .= '💰 Giá: ' . format_cash($total) . 'đ
';
                                                            $message .= '📅 Thời gian đặt: ' . gettime() . '
';
                                                            $message .= '✅ Trạng thái: Chờ xử lý
';
                                                            $message .= '⚡ Cảm ơn bạn đã sử dụng dịch vụ!';
                                                            sendMessAdmin($message);
                                                        }
                                                        insert_log($data_user['id'], 'Mua dịch vụ ' . $row['name']);
                                                        exit(JsonMsg('success', 'Thanh toán thành công, chuyển hướng đến lịch sử mua!'));
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
