<?php
// statically decompiled from classdb.php  [structured; all 45 record(s) structured]



function getRowRealtime($table, $id, $row)
{
    global $db;
    return $db->get_row('SELECT * FROM `' . $table . '` WHERE `id` = \'' . $id . '\' ')[$row] ?? '';
}

function getRowUser($id, $row)
{
    global $db;
    return $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $id . '\' ')[$row] ?? 'NULL';
}

function rechargeBankMonth()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) as total FROM invoices WHERE YEAR(FROM_UNIXTIME(create_time)) = ' . date('Y') . ' AND MONTH(FROM_UNIXTIME(create_time)) = ' . date('m') . '')['total'] ?? 0);
}

function rechargeBankWeekday()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) AS total_amount FROM invoices WHERE create_time >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)) AND create_time < UNIX_TIMESTAMP(DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY))')['total_amount'] ?? 0);
}

function rechargeBankDay()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) AS total_amount FROM invoices WHERE DATE(FROM_UNIXTIME(create_time)) = CURDATE();')['total_amount'] ?? 0);
}

function withdrawTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) as total FROM withdraw_ref WHERE `status` = 2')['total'] ?? 0);
}

function withdrawMonth()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) as total FROM withdraw_ref WHERE `status` = 2 AND YEAR(FROM_UNIXTIME(create_gettime)) = ' . date('Y') . ' AND MONTH(FROM_UNIXTIME(create_gettime)) = ' . date('m') . '')['total'] ?? 0);
}

function withdrawWeekday()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) AS total_amount FROM withdraw_ref WHERE `status` = 2 AND create_gettime >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)) AND create_gettime < UNIX_TIMESTAMP(DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY))')['total_amount'] ?? 0);
}

function withdrawDay()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) AS total_amount FROM withdraw_ref WHERE `status` = 2 AND DATE(FROM_UNIXTIME(create_gettime)) = CURDATE();')['total_amount'] ?? 0);
}

function orderCanceledTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM orders WHERE `status` = \'canceled\'')['total'] ?? 0);
}

function orderPendingTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM orders WHERE `status` = \'pending\'')['total'] ?? 0);
}

function orderWithdrawCtvPendingTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM withdraw_ctv WHERE `status` = \'0\'')['total'] ?? 0);
}

function orderCompletedTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM orders WHERE `status` = \'completed\'')['total'] ?? 0);
}

function orderPaymentTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(payment) AS total_amount FROM orders WHERE `status` = \'completed\'')['total_amount'] ?? 0);
}

function usersTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM users')['total'] ?? 0);
}

function accountSoldTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM history_buy')['total'] ?? 0);
}

function boostingCompletedTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM orders WHERE `status` = \'completed\'')['total'] ?? 0);
}

function usersMonthTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM users WHERE YEAR(create_date) = ' . date('Y') . ' AND MONTH(create_date) = ' . date('m') . '')['total'] ?? 0);
}

function usersDayTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT COUNT(id) as total FROM users WHERE DATE(create_date) = CURDATE();')['total'] ?? 0);
}

function revenueTotal()
{
    global $db;
    $total_bank = $db->get_row('SELECT SUM(amount) AS total_amount FROM invoices')['total_amount'] ?? 0;
    $total_card = $db->get_row('SELECT SUM(amount) FROM cards WHERE  `status` = \'completed\' ')['SUM(amount)'] ?? 0;
    return format_cash($total_bank + $total_card);
}

function revenueMonthTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) as total FROM invoices WHERE YEAR(FROM_UNIXTIME(create_time)) = ' . date('Y') . ' AND MONTH(FROM_UNIXTIME(create_time)) = ' . date('m') . '')['total'] ?? 0);
}

function revenueDayTotal()
{
    global $db;
    return format_cash($db->get_row('SELECT SUM(amount) AS total_amount FROM invoices WHERE DATE(FROM_UNIXTIME(create_time)) = CURDATE();')['total_amount'] ?? 0);
}

function checkCoupon($coupon, $user_id, $total_money)
{
    global $db;
    $coupon = Anti_xss($coupon);
    $coupon = $db->get_row('SELECT * FROM `tbl_coupons` WHERE `code` = \'' . $coupon . ('\' AND `min` <= ' . $total_money . ' AND `max` >= ' . $total_money . ' AND `used` < `amount` '));
    if ($coupon) {
        if ($coupon['used'] < $coupon['amount']) {
            if (!$db->get_row('SELECT * FROM `tbl_coupon_used` WHERE `coupon_id` = \'' . $coupon['id'] . '\' AND `user_id` = \'' . $user_id . '\' ')) {
                return $coupon['discount'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkPromotion($amount)
{
    global $db;
    foreach ($db->get_list('SELECT * FROM `promotions` WHERE `amount` <= \'' . $amount . '\' ORDER by `amount` DESC ') as $promotion) {
        $received = $amount + $amount * $promotion['discount'] / 100;
        return $received;
    }
    return $amount;
}

function insert_log($user_id, $reason)
{
    global $db;
    $db->insert('logs', ['user_id' => $user_id, 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'create_date' => gettime(), 'action' => $reason]);
}

function RemoveCredits($user_id, $amount, $reason)
{
    global $db;
    $db->insert('log_balance', ['trans_id' => strtoupper('GD' . substr(md5(uniqid(mt_rand(), true)), 0, 16)), 'money_before' => getrowuser($user_id, 'money'), 'money_change' => $amount, 'money_after' => getrowuser($user_id, 'money') - $amount, 'time' => gettime(), 'type' => '-', 'content' => $reason, 'user_id' => $user_id]);
    $isRemove = $db->tru('users', 'money', $amount, ' `id` = \'' . $user_id . '\' ');
    if ($isRemove) {
        return true;
    } else {
        return false;
    }
}

function PlusCredits($user_id, $amount, $reason, $transactionId = null)
{
    global $db;
    $transactionId = $transactionId ?: strtoupper('GD' . substr(md5(uniqid(mt_rand(), true)), 0, 16));
    $db->insert('log_balance', ['trans_id' => $transactionId, 'money_before' => getrowuser($user_id, 'money'), 'money_change' => $amount, 'money_after' => getrowuser($user_id, 'money') + $amount, 'time' => gettime(), 'type' => '+', 'content' => $reason, 'user_id' => $user_id]);
    $isPlus = $db->cong('users', 'money', $amount, ' `id` = \'' . $user_id . '\' ');
    if ($isPlus) {
        $db->cong('users', 'total_money', $amount, ' `id` = \'' . $user_id . '\' ');
        $newBalance = (int) getrowuser($user_id, 'money');
        create_user_notification(
            (int) $user_id,
            'transaction',
            'Tài khoản của bạn đã được cộng số dư',
            'Số dư mới: ' . format_cash($newBalance) . 'đ. Đã cộng +' . format_cash($amount) . 'đ — ' . (string) $reason,
            '/customer/balance'
        );
        return true;
    } else {
        return false;
    }
}

function RemoveCreditsItem($user_id, $amount, $reason)
{
    global $db;
    $isRemove = $db->tru('users', 'coin', $amount, ' `id` = \'' . $user_id . '\' ');
    if ($isRemove) {
        return true;
    } else {
        return false;
    }
}

function PlusCreditsItem($user_id, $amount, $reason)
{
    global $db;
    $isPlus = $db->cong('users', 'coin', $amount, ' `id` = \'' . $user_id . '\' ');
    if ($isPlus) {
        return true;
    } else {
        return false;
    }
}

function Banned($user_id, $reason)
{
    global $db;
    $db->insert('logs', ['user_id' => $user_id, 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'created_at' => gettime(), 'action' => $reason]);
    $db->update('users', ['banned' => 1], 'id = \'' . $user_id . '\' ');
}

function addRef($user_id, $price, $note = '')
{
    global $db;
    if ($db->site('status_ref') != 1) {
        return false;
    } else {
        $getUser = $db->get_row(' SELECT * FROM `users` WHERE `id` = \'' . $user_id . '\' ');
        if ($getUser['ref_id'] != 0) {
            if (getrowuser($getUser['ref_id'], 'ip') == $getUser['ip']) {
                return false;
            } else {
                $ck = $db->site('ck_ref');
                if (getrowuser($getUser['ref_id'], 'ref_ck') != 0) {
                    $ck = getrowuser($getUser['ref_id'], 'ref_ck');
                }
                $price = $price * $ck / 100;
                $db->cong('users', 'ref_money', $price, ' `id` = \'' . $getUser['ref_id'] . '\' ');
                $db->cong('users', 'ref_total_money', $price, ' `id` = \'' . $getUser['ref_id'] . '\' ');
                $db->cong('users', 'ref_amount', $price, ' `id` = \'' . $getUser['ref_id'] . '\' ');
                $db->insert('log_ref', ['user_id' => $getUser['ref_id'], 'reason' => $note, 'sotientruoc' => getrowuser($getUser['ref_id'], 'ref_money') - $price, 'sotienthaydoi' => $price, 'sotienhientai' => getrowuser($getUser['ref_id'], 'ref_money'), 'created_at' => gettime()]);
                return true;
            }
        } else {
            return false;
        }
    }
}

function pusher($username = null, $data_array = null)
{
    global $db;
    $pushdata = $db->get_row('SELECT * FROM `tb_pusher` ORDER BY RAND() LIMIT 1');
    $options = ['cluster' => $pushdata['pusher_cluster'], 'useTLS' => true];
    $pusher = new Pusher\Pusher($pushdata['pusher_key'], $pushdata['pusher_secret'], $pushdata['pusher_app_id'], $options);
    return $pusher->trigger($username, 'realtime', $data_array);
}

function whereInvoicePending($payment_method, $amount)
{
    global $db;
    return $db->get_list('SELECT * FROM `invoices` WHERE 
            `status` = 0 AND 
            `payment_method` = \'' . $payment_method . '\' AND 
            `pay` <= \'' . $amount . '\' AND 
            `fake` = 0
            ORDER BY id DESC ');
}

function insetLog($user_id, $reason)
{
    global $db;
    $db->insert('logs', ['user_id' => $user_id, 'ip' => myip(), 'device' => $_SERVER['HTTP_USER_AGENT'], 'create_date' => gettime(), 'action' => $reason]);
}

function sendCSM($mail_nhan, $ten_nhan, $chu_de, $noi_dung, $bcc = '', $path = '')
{
    global $db;
    if ($db->site('pass_email_smtp') != '' && $db->site('smtp_status') == 1) {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $db->site('email_smtp');
        $mail->Password = $db->site('pass_email_smtp');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($db->site('email_smtp'), $bcc);
        $mail->addAddress($mail_nhan, $ten_nhan);
        if ($path !== '' && is_file($path)) {
            $mail->addAttachment($path);
        }
        $mail->addReplyTo($db->site('email_smtp'), $bcc);
        $mail->isHTML(true);
        $mail->Subject = $chu_de;
        $mail->Body = $noi_dung;
        $mail->CharSet = 'UTF-8';
        $send = $mail->send();
        return $send;
    } else {
        return 'Chưa cấu hình SMTP';
    }
}

function uploadAndSaveOption($inputName, $optionKey)
{
    global $db;
    if (check_img($inputName)) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', rand(5, 10));
        $destination_path = realpath($_SERVER['DOCUMENT_ROOT']);
        $uploads_dir_audio = $destination_path . '/upload/theme/' . $rand . '.png';
        $uploads_dir = '/upload/theme/' . $rand . '.png';
        $tmp_name = $_FILES[$inputName]['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name, $uploads_dir_audio);
        if ($addlogo) {
            $db->update('options', ['value' => $uploads_dir], ' `key` = \'' . $optionKey . '\' ');
        }
    }
}

function sendMessAdmin($my_text)
{
    if ($my_text != '') {
        return sendMessTelegram($my_text);
    } else {
        return false;
    }
}

function sendMessUser($my_text, $user_id)
{
    if ($my_text != '') {
        return sendMessTelegramUser($my_text, $user_id);
    } else {
        return false;
    }
}

function sendMessTelegramUser($my_text, $user_id)
{
    global $db;
    $checkUser = $db->get_row('select * from `users` where `id` = \'' . $user_id . '\'');
    if (!$checkUser) {
        return false;
    } else {
        if ($checkUser['telegram_id'] == '') {
            return false;
        } else {
            if ($checkUser['telegram_token'] == '') {
                return false;
            } else {
                if ($my_text == '') {
                    return false;
                } else {
                    if ($checkUser['status_telegram'] == 2 && $checkUser['telegram_token'] != '' && $checkUser['telegram_id'] != '') {
                        $telegram_url = 'https://api.telegram.org/bot' . $checkUser['telegram_token'] . '/sendMessage';
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $telegram_url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $checkUser['telegram_id'], 'text' => $my_text, 'parse_mode' => 'HTML']));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        return $response;
                    } else {
                        return false;
                    }
                }
            }
        }
    }
}

function sendMessTelegram($my_text, $token = '', $chat_id = '')
{
    global $db;
    if ($chat_id == '') {
        $chat_id = $db->site('telegram_chat_id');
    }
    if ($token == '') {
        $token = $db->site('telegram_token');
    }
    if ($my_text == '') {
        return false;
    } else {
        if ($db->site('telegram_status') == 1 && $token != '' && $chat_id != '') {
            $telegram_url = 'https://api.telegram.org/bot' . $token . '/sendMessage';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $telegram_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $chat_id, 'text' => $my_text, 'parse_mode' => 'HTML']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        } else {
            return false;
        }
    }
}

function napthe($telco, $amount, $serial, $pin, $trans_id)
{
    global $db;
    $partner_id = $db->site('card_partner_id');
    $partner_key = $db->site('card_partner_key');
    $url = $db->site('card_url_api') . '?sign=' . md5($partner_key . $pin . $serial) . '&telco=' . $telco . '&code=' . $pin . '&serial=' . $serial . '&amount=' . $amount . '&request_id=' . $trans_id . '&partner_id=' . $partner_id . '&command=charging';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getFlashSale($flashSaleID)
{
    global $db;
    $currentTime = time();
    $flashSale = $db->get_row('SELECT start_time, end_time FROM flash_sales WHERE id = \'' . $flashSaleID . '\'');
    if (!$flashSale) {
        return ['status' => 'error', 'msg' => 'Chiến dịch Flash Sale không tồn tại'];
    } else {
        $startTime = strtotime($flashSale['start_time']);
        $endTime = strtotime($flashSale['end_time']);
        if ($currentTime < $startTime) {
            return ['status' => 'upcoming', 'msg' => 'Sắp diễn ra'];
        } else {
            if ($startTime <= $currentTime && $currentTime <= $endTime) {
                return ['status' => 'ongoing', 'msg' => 'Đang diễn ra'];
            } else {
                return ['status' => 'expired', 'msg' => 'Đã kết thúc'];
            }
        }
    }
}

function getProductFlashSale($productID)
{
    global $db;
    $flashSaleProduct = $db->get_row('SELECT fs.id as flash_sale_id, fs.start_time, fs.end_time, fsp.discount_price FROM flash_sales fs
                                          JOIN flash_sale_products fsp ON fs.id = fsp.flash_sale_id
                                          WHERE fsp.product_id = \'' . $productID . '\'');
    $flashSaleStatus = 'Không có chương trình flash sale';
    $discountPrice = 0;
    $flashSaleID = 0;
    if ($flashSaleProduct) {
        $flashSaleID = $flashSaleProduct['flash_sale_id'];
        $flashSale = getflashsale($flashSaleID);
        if ($flashSale['status'] == 'error') {
            return ['flashSaleID' => $flashSaleID, 'flashSaleStatus' => $flashSaleStatus, 'discountPrice' => $discountPrice];
        } else {
            $flashSaleStatus = $flashSale['status'];
            $discountPrice = $flashSaleStatus === 'ongoing' ? $flashSaleProduct['discount_price'] : 0;
            return ['flashSaleID' => $flashSaleID, 'flashSaleStatus' => $flashSaleStatus, 'discountPrice' => $discountPrice, 'start_time' => $flashSaleProduct['start_time'] ?? '', 'end_time' => $flashSaleProduct['end_time'] ?? ''];
        }
    }

    return ['flashSaleID' => 0, 'flashSaleStatus' => $flashSaleStatus, 'discountPrice' => 0, 'start_time' => '', 'end_time' => ''];
}

function debit_processing($user_id)
{
    global $db;
    $getUser = $db->get_row(' SELECT * FROM `users` WHERE `id` = \'' . $user_id . '\' ');
    if (0 < $getUser['debit']) {
        if ($getUser['debit'] < $getUser['money']) {
            $isTru = $db->tru('users', 'debit', $getUser['debit'], ' `id` = \'' . $user_id . '\' ');
            if ($isTru) {
                removecredits($getUser['id'], $getUser['debit'], 'Thanh toán số tiền ghi nợ');
                return true;
            } else {
                return false;
            }
        } else {
            $isTru = $db->tru('users', 'debit', $getUser['money'], ' `id` = \'' . $user_id . '\' ');
            if ($isTru) {
                removecredits($getUser['id'], $getUser['money'], 'Thanh toán số tiền ghi nợ');
                return true;
            }
        }
    }
}
