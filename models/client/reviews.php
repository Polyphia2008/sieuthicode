<?php
// statically decompiled from reviews.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        $product_id = Anti_xss($_POST['product_id']);
        if (empty($product_id)) {
            exit(JsonMsg('error', 'Vui lòng chọn sản phẩm để đánh giá'));
        } else {
            $rating = Anti_xss($_POST['rating']);
            if (empty($rating)) {
                exit(JsonMsg('error', 'Vui lòng chọn sao đánh giá'));
            } else {
                if ($rating < 1 || 5 < $rating) {
                    exit(JsonMsg('error', 'Bạn chỉ được phép chọn từ 1 - 5 sao'));
                } else {
                    $comment = Anti_xss($_POST['review']);
                    if (empty($comment)) {
                        exit(JsonMsg('error', 'Vui lòng nhập nội dung cần đánh giá'));
                    } else {
                        if (isset($_POST['action']) && $_POST['action'] == 'account') {
                            $existingRecord = $db->get_row('
            SELECT * FROM history_buy
            WHERE id = ' . $product_id . ' AND username = \'' . $data_user['username'] . '\'
        ');
                            if (!$existingRecord) {
                                exit(JsonMsg('error', 'Tài khoản không tồn tại trong đơn hàng của bạn'));
                            } else {
                                if ($db->get_row('SELECT * FROM `reviews` WHERE `history_id` = \'' . $product_id . '\' AND `user_id` = \'' . $data_user['id'] . '\' AND `type` = \'account\'')) {
                                    exit(JsonMsg('error', 'Tài khoản này bạn đã đánh giá rồi'));
                                } else {
                                    $data = ['user_id' => $data_user['id'], 'seller' => $existingRecord['username_post'], 'seller_id' => $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $existingRecord['username_post'] . '\' ')['id'] ?? 0, 'acc_id' => $existingRecord['id_acc'], 'history_id' => $product_id, 'rating' => $rating, 'review' => $comment, 'type' => 'account', 'created_at' => gettime(), 'updated_at' => gettime()];
                                    if (!$db->insert('reviews', $data)) {
                                        exit(JsonMsg('error', 'Đánh giá thất bại'));
                                    } else {
                                        header('Content-Type: application/json');
                                        echo json_encode(['status' => 'success', 'msg' => 'Đánh giá thành công', 'rating' => $rating, 'review' => $comment, 'username' => $data_user['username'], 'date' => timeAgo(time())]);
                                        if (isset($_POST['action']) && $_POST['action'] == 'service') {
                                            $existingRecord = $db->get_row('
            SELECT * FROM orders
            WHERE id = ' . $product_id . ' AND user_id = \'' . $data_user['id'] . '\'
        ');
                                            if (!$existingRecord) {
                                                exit(JsonMsg('error', 'Dịch vụ không tồn tại trong đơn hàng của bạn'));
                                            } else {
                                                if ($db->get_row('SELECT * FROM `reviews` WHERE `history_id` = \'' . $product_id . '\' AND `user_id` = \'' . $data_user['id'] . '\' AND `type` = \'service\'')) {
                                                    exit(JsonMsg('error', 'Dịch vụ này bạn đã đánh giá rồi'));
                                                } else {
                                                    $data = ['user_id' => $data_user['id'], 'service_id' => $existingRecord['package_id'], 'history_id' => $product_id, 'rating' => $rating, 'review' => $comment, 'type' => 'service', 'created_at' => gettime(), 'updated_at' => gettime()];
                                                    if (!$db->insert('reviews', $data)) {
                                                        exit(JsonMsg('error', 'Đánh giá thất bại'));
                                                    } else {
                                                        header('Content-Type: application/json');
                                                        echo json_encode(['status' => 'success', 'msg' => 'Đánh giá thành công', 'rating' => $rating, 'review' => $comment, 'username' => $data_user['username'], 'date' => timeAgo(time())]);
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
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
