<?php
// statically decompiled from flashsale.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    } else {
        if ($data_user['level'] != 'admin') {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (!empty($input)) {
                        foreach ($input as $product) {
                            $flashSaleID = Anti_xss($product['flashSaleID'] ?? '');
                            $productID = Anti_xss($product['productID'] ?? '');
                            $discountPrice = Anti_xss($product['discountPrice'] ?? '');
                            if (empty($productID) || empty($discountPrice) || empty($flashSaleID)) {
                                exit(JsonMsg('error', 'Dữ liệu không hợp lệ cho sản phẩm ID: ' . $productID));
                            } else {
                                if (!is_numeric($discountPrice) || $discountPrice < 0) {
                                    exit(JsonMsg('error', 'Số tiền giảm giá không hợp lệ'));
                                } else {
                                    $flashsale = $db->get_row('SELECT * FROM flash_sales WHERE id = \'' . $flashSaleID . '\'');
                                    if (!$flashsale) {
                                        exit(JsonMsg('error', 'Chiến dịch Flash Sale không tồn tại'));
                                    } else {
                                        $accounts = $db->get_row('SELECT * FROM accounts WHERE id = \'' . $productID . '\' AND status = \'on\'');
                                        if (!$accounts) {
                                            exit(JsonMsg('error', 'Sản phẩm ID: ' . $productID . ' không tồn tại hoặc không hợp lệ.'));
                                        } else {
                                            if ($db->get_row('SELECT * FROM flash_sale_products WHERE flash_sale_id = \'' . $flashSaleID . '\' AND product_id = \'' . $productID . '\'')) {
                                                exit(JsonMsg('error', 'Sản phẩm ID: ' . $productID . ' đã tồn tại trong flash sale này'));
                                            } else {
                                                $db->insert('flash_sale_products', ['flash_sale_id' => $flashSaleID, 'product_id' => $productID, 'discount_price' => $discountPrice, 'created_at' => gettime()]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo JsonMsg('success', 'Các sản phẩm đã được thêm vào flash sale thành công!');
                    } else {
                        exit(JsonMsg('error', 'Không có dữ liệu nào được gửi.'));
                    }
                } else {
                    exit(JsonMsg('error', 'Phương thức request không hợp lệ.'));
                }
            }
        }
    }
} else {
    exit(JsonMsg('error', 'Dữ liệu không hợp lệ!'));
}
