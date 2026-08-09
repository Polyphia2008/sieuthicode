<?php
// statically decompiled from card.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($db->site('card_status') != 1) {
    exit('status_card_off');
} else {
    if (isset($_GET['request_id']) && isset($_GET['callback_sign'])) {
        $status = Anti_xss($_GET['status']);
        $message = Anti_xss($_GET['message']);
        $request_id = Anti_xss($_GET['request_id']);
        $declared_value = Anti_xss($_GET['declared_value']);
        $value = Anti_xss($_GET['value']);
        $amount = Anti_xss($_GET['amount']);
        $code = Anti_xss($_GET['code']);
        $serial = Anti_xss($_GET['serial']);
        $telco = Anti_xss($_GET['telco']);
        $trans_id = Anti_xss($_GET['trans_id']);
        $callback_sign = Anti_xss($_GET['callback_sign']);
        if ($callback_sign != md5($db->site('card_partner_key') . $code . $serial)) {
            exit('callback_sign_error');
        } else {
            $row = $db->get_row(' SELECT * FROM `cards` WHERE `trans_id` = \'' . $request_id . '\' AND `status` = \'pending\' ');
            if (!$row) {
                exit('request_id_error');
            } else {
                $getUser = $db->get_row(' SELECT * FROM `users` WHERE `id` = \'' . $row['user_id'] . '\' AND `banned` = 0 ');
                if (!$getUser) {
                    exit('user không hợp lệ');
                } else {
                    if ($status == 1) {
                        if ($db->site('card_ck') == 0) {
                            $price = $serial;
                        } else {
                            $price = $declared_value - $declared_value * $db->site('card_ck') / 100;
                        }
                        $db->update('cards', ['status' => 'completed', 'price' => $price, 'update_date' => gettime()], ' `id` = \'' . $row['id'] . '\' ');
                        $isCong = PlusCredits($row['user_id'], $price, 'Nạp thẻ cào Seri ' . $row['serial'] . ' - Pin ' . $row['pin'], 'TOPUP_CARD_' . $row['pin']);
                        if ($isCong) {
                            if ($db->site('status_ref') == 1 && $getUser['ref_id'] != 0) {
                                addRef($getUser['id'], $price, 'Hoa hồng thành viên');
                            }
                            if ($db->site('telegram_status') == 1) {
                                $message = '📢 THÔNG BÁO NẠP THẺ 📢
';
                                $message .= '🌐 Tên miền: ' . $_SERVER['SERVER_NAME'] . '
';
                                $message .= '👤 Người dùng: ' . $getUser['username'] . '
';
                                $message .= '🆔 Mã giao dịch: ' . $request_id . '
';
                                $message .= '💳 Nhà mạng: ' . $telco . '
';
                                $message .= '🔢 Mệnh giá: ' . format_cash($amount) . '
';
                                $message .= '🔄 Mã thẻ: ' . $code . '
';
                                $message .= '🔄 Serial: ' . $serial . '
';
                                $message .= '📅 Thời gian: ' . gettime() . '
';
                                $message .= '✅ Trạng thái: Thành công
';
                                $message .= '⚡ Cảm ơn bạn đã sử dụng dịch vụ!';
                                sendMessAdmin($message);
                            }
                            $db->insert('top', ['username' => $getUser['username'], 'method' => 'card', 'amount' => $value, 'created_at' => time()]);
                            exit('payment.success');
                        } else {
                            exit('thẻ này đã được cộng tiền rồi');
                        }
                    } else {
                        $db->update('cards', ['status' => 'error', 'price' => 0, 'update_date' => gettime(), 'reason' => 'Thẻ cào không hợp lệ hoặc đã được sử dụng'], ' `id` = \'' . $row['id'] . '\' ');
                        exit('payment.error');
                    }
                }
            }
        }
    }
}
