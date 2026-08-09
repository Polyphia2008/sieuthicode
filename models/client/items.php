<?php
// statically decompiled from items.php  [structured; all 1 record(s) structured]

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
                if (empty($_POST['service']) || empty($_POST['amount'])) {
                    exit(JsonMsg('error', 'Vui lòng nhập đầy đủ thông tin'));
                } else {
                    try {
                        $getUser = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $data_user['id'] . '\' AND `banned` = 0');
                        if (!$getUser || $getUser['level'] === 'ctv') {
                            exit(JsonMsg('error', 'Người dùng không tồn tại, đã bị cấm hoặc không có quyền thực hiện chức năng này.'));
                        } else {
                            $service = Anti_xss($_POST['service']);
                            $amount = Anti_xss($_POST['amount']);
                            if (!is_numeric($amount) || $amount <= 0) {
                                exit(JsonMsg('error', 'Số tiền không hợp lệ'));
                            } else {
                                $sub = $db->get_row('SELECT * FROM `subboostings` WHERE `id` = \'' . $service . '\' AND `status` = 1');
                                if (!$sub) {
                                    exit(JsonMsg('error', 'Không tìm thấy nhóm dịch vụ!'));
                                } else {
                                    $arr_query = json_decode($sub['detail'], true);
                                    if ($amount < $arr_query['min'] || $arr_query['max'] < $amount) {
                                        exit(JsonMsg('error', 'Bạn chỉ có thể thanh toán số tiền từ ' . format_cash($arr_query['min']) . ' đến ' . format_cash($arr_query['max'])));
                                    } else {
                                        $item = tinhKetQua($amount, $arr_query['coefficient']);
                                        $total = $amount - $amount * $getUser['chietkhau'] / 100;
                                        if ($total < 0 || $data_user['money'] < $total) {
                                            exit(JsonMsg('error', 'Số dư của bạn không đủ hoặc dữ liệu không hợp lệ'));
                                        } else {
                                            $package = $item . ' ' . $arr_query['unit'];
                                            if (RemoveCredits($data_user['id'], $total, 'Mua gói #' . $package)) {
                                                if (getRowRealtime('users', $data_user['id'], 'money') < -500) {
                                                    Banned($data_user['id'], 'Gian lận khi mua dịch vụ');
                                                    exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                } else {
                                                    $transid = random('QWERTYUOPASDFGHJKZXCVBNM123456789', 3) . uniqid();
                                                    $arr_data = [];
                                                    $a = 2;
                                                    while ($a < count($arr_query['data'])) {
                                                        $arr_data[] = ['id' => $a, 'label' => $arr_query['data'][$a]['label'], 'value' => Anti_xss($_POST['fields_' . $a])];
                                                        ++$a;
                                                    }
                                                    $json = ['author' => 'SIEUTHICODE.NET', 'name_product' => $arr_query['name_product'], 'package' => $package, 'data' => $arr_data];
                                                    $full_json = json_encode($json);
                                                    $db->insert('orders', ['user_id' => $data_user['id'], 'username' => $data_user['username'], 'detail' => $full_json, 'name' => $package, 'code' => $transid, 'payment' => $total, 'sub_id' => $sub['id'], 'name_sub' => $arr_query['name_product'], 'created_at' => gettime(), 'updated_at' => gettime()]);
                                                    if ($db->site('telegram_status') == 1) {
                                                        $message = '📢 THÔNG BÁO MUA ITEM 📢
';
                                                        $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                                        $message .= '👤 Người dùng: ' . $data_user['username'] . '
';
                                                        $message .= '🆔 Mã giao dịch: ' . $transid . '
';
                                                        $message .= '🛒 Dịch vụ: ' . $arr_query['name_product'] . '
';
                                                        $message .= '📦 Gói: ' . $package . '
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
                                                    insert_log($data_user['id'], 'Mua gói #' . $package);
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
                    } catch (Exception $e) {
                        exit(JsonMsg('error', 'Đã xảy ra lỗi ngoại lệ'));
                    }
                }
            }
        }
    }
}
