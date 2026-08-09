<?php
// statically decompiled from bank.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['type']) || !empty($_GET['type'])) {
    $type = Anti_xss($_GET['type']);
    $bank = $db->get_row('SELECT * FROM `bank` WHERE `short_name` = \'' . $type . '\'');
    if (!$bank) {
        exit(JsonMsg('error', 'Ngân hàng không tồn tại'));
    } else {
        if (empty($bank['url_api'])) {
            exit(JsonMsg('error', 'Vui lòng nhập api ở cấu hình'));
        } else {
            $url = $bank['url_api'];
            $MEMO_PREFIX = $db->site('prefix_autobank');
            $result = curl_get($url);
            $result = json_decode($result, true);
            print_r($result);
            if (!isset($result['transactions'])) {
                exit(JsonMsg('error', 'Không thể lấy được dữ liệu'));
            } else {
                foreach ($result['transactions'] as $data) {
                    if (!($data['type'] == 'OUT')) {
                        $des = $data['description'];
                        $amount = $data['amount'];
                        $tid = $data['transactionID'];
                        $id = parse_order_id($des, $MEMO_PREFIX);
                        if ($id) {
                            $getUser = $db->get_row(' SELECT * FROM `users` WHERE `id` = \'' . $id . '\' ');
                            if ($getUser) {
                                if ($db->num_rows(' SELECT * FROM `invoices` WHERE `trans_id` = \'' . $tid . '\' ') == 0) {
                                    $received = checkPromotion($amount);
                                    $create = $db->insert('invoices', ['trans_id' => $tid, 'payment_method' => $type, 'description' => $des, 'amount' => $received, 'create_time' => time(), 'created_at' => gettime(), 'status' => 'completed', 'user_id' => $getUser['id']]);
                                    if ($create) {
                                        $isCong = PlusCredits($getUser['id'], $received, 'Nạp tiền tự động qua ' . $bank['short_name'] . ' (#' . $tid . ' - ' . $des . ' - ' . $amount . ')', 'TOPUP_' . $bank['accountNumber'] . '_' . $tid);
                                        if ($isCong) {
                                            if ($db->site('status_ref') == 1 && $getUser['ref_id'] != 0) {
                                                addRef($getUser['id'], $received, 'Hoa hồng thành viên');
                                            }
                                            debit_processing($getUser['id']);
                                            $db->insert('top', ['username' => $getUser['username'], 'method' => 'card', 'amount' => $received, 'created_at' => time()]);
                                            $db->cong('users', 'total_money', $received, ' `id` = \'' . $getUser['id'] . '\' ');
                                            $arr_res = ['status' => '200', 'msg' => 'Tài khoản của bạn đã được cộng ' . format_cash($received) . ' thành công!'];
                                            if ($db->site('telegram_status') == 1) {
                                                $message = '💰 THÔNG BÁO NẠP TIỀN 💰
';
                                                $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                                $message .= '👤 Người dùng: ' . $getUser['username'] . '
';
                                                $message .= '🆔 Mã giao dịch: ' . $tid . '
';
                                                $message .= '💵 Số tiền: ' . format_cash($received) . '
';
                                                $message .= '🏦 Phương thức: ' . $type . '
';
                                                $message .= '📅 Thời gian: ' . gettime() . '
';
                                                $message .= '✅ Trạng thái: Thành công
';
                                                $message .= '⚡ Cảm ơn bạn đã nạp tiền!';
                                                sendMessAdmin($message);
                                            }
                                            echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
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
