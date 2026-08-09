<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($data_user['ctv'] != 1) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $id = Anti_xss($_POST['id']);
                $action = Anti_xss($_POST['action']);
                if (empty($id) || empty($action)) {
                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                } else {
                    switch ($action) {
                        case 'updateOrderBoosting':
                            $status = Anti_xss($_POST['status']);
                            $admin_note = !empty($_POST['admin_note']) ? Anti_xss($_POST['admin_note']) : '';
                            $check_order = $db->get_row('SELECT * FROM `orders` WHERE `receiver` = \'' . $data_user['username'] . '\' AND `id` = \'' . $id . '\'');
                            if (!$check_order) {
                                exit(JsonMsg('error', 'Đơn hàng không tồn tại'));
                            } else {
                                if ($check_order['status'] != 'pending') {
                                    exit(JsonMsg('error', 'Đơn hàng này đã cập nhật trạng thái rồi'));
                                } else {
                                    $updateData = ['status' => $status, 'admin_note' => $admin_note, 'updated_at' => gettime()];
                                    if (isset($_FILES['thumb']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
                                        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/order/';
                                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff'];
                                        $maxFileSize = 5242882;
                                        if (!is_dir($uploadDir)) {
                                            mkdir($uploadDir, 493, true);
                                        }
                                        $fileTmpPath = $_FILES['thumb']['tmp_name'];
                                        $fileName = uniqid() . '-' . basename($_FILES['thumb']['name']);
                                        $fileType = $_FILES['thumb']['type'];
                                        $fileSize = $_FILES['thumb']['size'];
                                        $uploadFilePath = $uploadDir . $fileName;
                                        if (!in_array($fileType, $allowedTypes)) {
                                            exit(JsonMsg('error', 'Định dạng file không được hỗ trợ!'));
                                        } else {
                                            if ($maxFileSize < $fileSize) {
                                                exit(JsonMsg('error', 'Kích thước file vượt quá giới hạn!'));
                                            } else {
                                                if (!empty($check_order['image_path'])) {
                                                    $oldImagePath = $_SERVER['DOCUMENT_ROOT'] . $check_order['image_path'];
                                                    if (file_exists($oldImagePath)) {
                                                        unlink($oldImagePath);
                                                    }
                                                }
                                                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                                                    $updateData['image_path'] = '/upload/order/' . $fileName;
                                                    if ($status == 'error_refund' || $status == 'cancelled_refund') {
                                                        if ($check_order['status'] == 'error_refund' || $check_order['status'] == 'cancelled_refund') {
                                                            exit(JsonMsg('error', 'Đơn hàng này đã hoàn tiền rồi'));
                                                        } else {
                                                            PlusCredits($check_order['user_id'], $check_order['payment'], $admin_note);
                                                            $isUpdate = $db->update('orders', $updateData, ' `id` = \'' . $id . '\' ');
                                                            if ($isUpdate) {
                                                                insert_log($data_user['id'], 'Cập nhật đơn hàng cày thuê (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                                                exit(JsonMsg('success', 'Cập nhật thành công'));
                                                            } else {
                                                                exit(JsonMsg('error', 'Cập nhật không thành công'));
                                                            }
                                                        }
                                                    } else {
                                                        if ($status == 'completed') {
                                                            $isUpdate = $db->update('orders', $updateData, ' `id` = \'' . $id . '\' ');
                                                            if ($isUpdate) {
                                                                insert_log($data_user['id'], 'Cập nhật đơn hàng đã hoàn thành cày thuê (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                                                $realMoney = $check_order['payment'] - $check_order['payment'] * ($db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $check_order['receiver'] . '\' AND `banned` = 0')['chietkhau_banacc'] ?? 0) / 100;
                                                                $db->query('UPDATE `users` SET `cost` = `cost` + ' . $realMoney . ' WHERE `username` = \'' . $check_order['receiver'] . '\'');
                                                                exit(JsonMsg('success', 'Cập nhật thành công'));
                                                            } else {
                                                                exit(JsonMsg('error', 'Cập nhật không thành công'));
                                                            }
                                                        } else {
                                                            $isUpdate = $db->update('orders', $updateData, ' `id` = \'' . $id . '\' ');
                                                            if ($isUpdate) {
                                                                insert_log($data_user['id'], 'Cập nhật đơn hàng cày thuê (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                                                exit(JsonMsg('success', 'Cập nhật thành công'));
                                                            } else {
                                                                exit(JsonMsg('error', 'Cập nhật không thành công'));
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    exit(JsonMsg('error', 'Lỗi khi tải lên hình ảnh!'));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        default:
                            break;
                    }
                }
            }
        }
    } else {
        exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
    }
}
