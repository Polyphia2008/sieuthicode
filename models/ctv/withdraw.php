<?php
// statically decompiled from withdraw.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        exit(JsonMsg('error', 'Invalid CSRF Protection Token'));
    } else {
        if ($db->site('status_demo') != 0) {
            exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
        } else {
            if ($data_user) {
                if ($data_user['ctv'] != 1) {
                    exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
                } else {
                    if ($db->site('status_withdraw_ctv') != 1) {
                        exit(JsonMsg('error', 'Chức năng rút tiền đang bảo trì'));
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
                                        if ($_POST['amount'] < $db->site('minrut_ctv')) {
                                            exit(JsonMsg('error', 'Số tiền rút tối thiểu phải là ' . format_cash($db->site('minrut_ctv'))));
                                        } else {
                                            if ($data_user['cost'] < $_POST['amount']) {
                                                exit(JsonMsg('error', 'Số dư khả dụng của bạn không đủ'));
                                            } else {
                                                $amount = Anti_xss($_POST['amount']);
                                                $trans_id = random('123456789QWERTYUIOPASDFGHJKLZXCVBNM', 6);
                                                $isTru = $db->tru('users', 'cost', $amount, ' `id` = \'' . $data_user['id'] . '\' ');
                                                if ($isTru) {
                                                    if (getRowRealTime('users', $data_user['id'], 'cost') < 0) {
                                                        Banned($data_user['id'], 'Gian lận khi rút số dư CTV');
                                                        exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                    } else {
                                                        $isInsert = $db->insert('withdraw_ctv', ['trans_id' => $trans_id, 'user_id' => $data_user['id'], 'bank' => Anti_xss($_POST['bank']), 'stk' => Anti_xss($_POST['stk']), 'name' => Anti_xss($_POST['name']), 'amount' => Anti_xss($_POST['amount']), 'status' => 0, 'create_gettime' => gettime(), 'update_gettime' => gettime()]);
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
            } else {
                exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
            }
        }
    }
}
