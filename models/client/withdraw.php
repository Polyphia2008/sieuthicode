<?php
// statically decompiled from withdraw.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($db->site('status_ref') != 1) {
            exit(JsonMsg('error', 'Chức năng rút tiền đang bảo trì'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
                    exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
                } else {
                    if (empty($_POST['bank'])) {
                        exit(JsonMsg('error', 'Vui lòng chọn ngân hàng cần rút'));
                    } else {
                        if (empty($_POST['stk'])) {
                            exit(JsonMsg('error', 'Vui lòng nhập số tài khoản cần rút'));
                        } else {
                            if (empty($_POST['name'])) {
                                exit(JsonMsg('error', 'Vui lòng nhập tên chủ tài khoản'));
                            } else {
                                if (empty($_POST['amount'])) {
                                    exit(JsonMsg('error', 'Vui lòng nhập số tiền cần rút'));
                                } else {
                                    if ($_POST['amount'] < $db->site('minrut_ref')) {
                                        exit(JsonMsg('error', 'Số tiền rút tối thiểu phải là ' . format_cash($db->site('minrut_ref'))));
                                    } else {
                                        if ($data_user['ref_money'] < $_POST['amount']) {
                                            exit(JsonMsg('error', 'Số dư hoa hồng khả dụng của bạn không đủ'));
                                        } else {
                                            $amount = Anti_xss(preg_replace('/\\D/', '', $_POST['amount']));
                                            $trans_id = random('123456789QWERTYUIOPASDFGHJKLZXCVBNM', 6);
                                            $isTru = $db->tru('users', 'ref_money', $amount, ' `id` = \'' . $data_user['id'] . '\' ');
                                            if ($isTru) {
                                                $db->insert('log_ref', ['user_id' => $data_user['id'], 'reason' => 'Rút số dư hoa hồng #' . $trans_id, 'sotientruoc' => $data_user['ref_money'], 'sotienthaydoi' => $amount, 'sotienhientai' => $data_user['ref_money'] - $amount, 'created_at' => gettime()]);
                                                if (getRowUser($data_user['id'], 'ref_money') < 0) {
                                                    Banned($data_user['id'], 'Gian lận khi rút số dư hoa hồng');
                                                    exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                } else {
                                                    $isInsert = $db->insert('withdraw_ref', ['trans_id' => $trans_id, 'user_id' => $data_user['id'], 'bank' => Anti_xss($_POST['bank']), 'stk' => Anti_xss($_POST['stk']), 'name' => Anti_xss($_POST['name']), 'amount' => Anti_xss($_POST['amount']), 'status' => 0, 'create_gettime' => gettime(), 'update_gettime' => gettime(), 'reason' => null]);
                                                    if ($isInsert) {
                                                        exit(JsonMsg('success', 'Tạo yêu cầu rút tiền thành công, vui lòng đợi ADMIN xử lý'));
                                                    } else {
                                                        exit(JsonMsg('error', 'ERROR 1 - Phát hiện lỗi khi rút tiền, vui lòng liên hệ ADMIN'));
                                                    }
                                                }
                                            } else {
                                                exit(JsonMsg('error', 'ERROR 2 - Phát hiện lỗi khi rút tiền, vui lòng liên hệ ADMIN'));
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
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
