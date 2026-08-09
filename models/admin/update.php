<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($data_user['level'] != 'admin') {
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
                        case 'updateTablePost':
                            $isUpdate = $db->update('posts', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Cập nhật trạng thái bài viết (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableLink':
                            $isUpdate = $db->update('links', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Cập nhật trạng thái liên kết (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTablePr':
                            $isUpdate = $db->update('advertisement', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Cập nhật trạng thái quảng cáo (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableCategoryBlog':
                            $isUpdate = $db->update('post_category', ['status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Cập nhật trạng thái chuyên mục bài viết (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableCategory':
                            $isUpdate = $db->update('categories', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table Category (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableCategoryItem':
                            $isUpdate = $db->update('category_items', ['status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table Category Item (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableCategoryBoosting':
                            $isUpdate = $db->update('boostings', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table Category (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableSubCategory':
                            $isUpdate = $db->update('subcategory', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table SubCategory (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableSubboosting':
                            $isUpdate = $db->update('subboostings', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table SubCategory (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableUnit':
                            $isUpdate = $db->update('units', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table Unit (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableBanner':
                            $isUpdate = $db->update('banner', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table banner (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateTableSpin':
                            $isUpdate = $db->update('spin_quests', ['stt' => !empty($_POST['stt']) ? Anti_xss($_POST['stt']) : 0, 'status' => !empty($_POST['status']) ? Anti_xss($_POST['status']) : 0], ' `id` = \'' . Anti_xss($_POST['id']) . '\' ');
                            if ($isUpdate) {
                                insert_log($data_user['id'], 'Update Table Spin (ID ' . Anti_xss($_POST['id']) . ')');
                                exit(JsonMsg('success', 'Cập nhật thành công'));
                            } else {
                                exit(JsonMsg('error', 'Cập nhật thất bại'));
                            }
                        case 'updateOrderBoosting':
                            $status = Anti_xss($_POST['status']);
                            $admin_note = !empty($_POST['admin_note']) ? Anti_xss($_POST['admin_note']) : '';
                            $check_order = $db->get_row('SELECT * FROM `orders` WHERE `id` = \'' . $id . '\'');
                            if (!$check_order) {
                                exit(JsonMsg('error', 'Đơn hàng không tồn tại'));
                            } else {
                                if ($check_order['status'] != 'pending' && $check_order['status'] != 'processing') {
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
                                                                insert_log($data_user['id'], 'Cập nhật đơn hàng (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                                                exit(JsonMsg('success', 'Cập nhật thành công'));
                                                            } else {
                                                                exit(JsonMsg('error', 'Cập nhật không thành công'));
                                                            }
                                                        }
                                                    } else {
                                                        $isUpdate = $db->update('orders', $updateData, ' `id` = \'' . $id . '\' ');
                                                        if ($isUpdate) {
                                                            insert_log($data_user['id'], 'Cập nhật đơn hàng (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                                            exit(JsonMsg('success', 'Cập nhật thành công'));
                                                        } else {
                                                            exit(JsonMsg('error', 'Cập nhật không thành công'));
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
                        case 'updateOrderItem':
                            $status = Anti_xss($_POST['status']);
                            $admin_note = !empty($_POST['admin_note']) ? Anti_xss($_POST['admin_note']) : '';
                            $check_order = $db->get_row('SELECT * FROM `order_items` WHERE `id` = \'' . $id . '\'');
                            if (!$check_order) {
                                exit(JsonMsg('error', 'Đơn hàng không tồn tại'));
                            } else {
                                if ($check_order['status'] != 'pending' && $check_order['status'] != 'processing') {
                                    exit(JsonMsg('error', 'Đơn hàng này đã cập nhật trạng thái rồi'));
                                } else {
                                    if ($status == 'error_refund' || $status == 'cancelled_refund') {
                                        if ($check_order['status'] == 'error_refund' || $check_order['status'] == 'cancelled_refund') {
                                            exit(JsonMsg('error', 'Đơn hàng này đã hoàn tiền rồi'));
                                        } else {
                                            PlusCredits($check_order['user_id'], $check_order['payment'], $admin_note);
                                            $db->update('order_items', ['status' => $status, 'admin_note' => $admin_note, 'updated_at' => gettime()], ' `id` = \'' . $id . '\' ');
                                            insert_log($data_user['id'], 'Cập nhật đơn hàng mua vật phẩm (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                            exit(JsonMsg('success', 'Cập nhật thành công'));
                                        }
                                    } else {
                                        $isUpdate = $db->update('order_items', ['status' => $status, 'admin_note' => $admin_note, 'updated_at' => gettime()], ' `id` = \'' . $id . '\' ');
                                        if ($isUpdate) {
                                            insert_log($data_user['id'], 'Cập nhật đơn hàng mua vật phẩm (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                            exit(JsonMsg('success', 'Cập nhật thành công'));
                                        } else {
                                            exit(JsonMsg('error', 'Cập nhật không thành công'));
                                        }
                                    }
                                }
                            }
                        case 'updateOrderwithdraw':
                            $status = Anti_xss($_POST['status']);
                            $admin_note = !empty($_POST['admin_note']) ? Anti_xss($_POST['admin_note']) : '';
                            if ($status == 'error_refund' || $status == 'cancelled_refund') {
                                $check_order = $db->get_row('SELECT * FROM `withdraw_logs` WHERE `id` = \'' . $id . '\'');
                                if (!$check_order) {
                                    exit(JsonMsg('error', 'Đơn hàng không tồn tại'));
                                } else {
                                    if ($check_order['status'] == 'error_refund' || $check_order['status'] == 'cancelled_refund') {
                                        exit(JsonMsg('error', 'Đơn hàng này đã hoàn rồi'));
                                    } else {
                                        $db->cong('users', 'coin', $check_order['value'], ' `id` = \'' . $check_order['user_id'] . '\' ');
                                        $db->update('withdraw_logs', ['status' => $status, 'admin_note' => $admin_note], ' `id` = \'' . $id . '\' ');
                                        insert_log($data_user['id'], 'Cập nhật đơn hàng rút thưởng (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                        exit(JsonMsg('success', 'Cập nhật thành công'));
                                    }
                                }
                            } else {
                                $isUpdate = $db->update('withdraw_logs', ['status' => $status, 'admin_note' => $admin_note], ' `id` = \'' . $id . '\' ');
                                if ($isUpdate) {
                                    insert_log($data_user['id'], 'Cập nhật đơn hàng rút thưởng (ID ' . $id . ') với trạng thái ' . $status . ' và ghi chú ' . $admin_note);
                                    exit(JsonMsg('success', 'Cập nhật thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Cập nhật không thành công'));
                                }
                            }
                        case 'updateWithdrawCTV':
                            $status = Anti_xss($_POST['status']);
                            $note = Anti_xss($_POST['note']);
                            $row = $db->get_row('SELECT * FROM `withdraw_ctv` WHERE `id` = \'' . $id . '\' ');
                            if (!$row) {
                                exit(JsonMsg('error', 'ID lịch sử không tồn tại trong hệ thống!'));
                            } else {
                                $isUpdate = $db->update('withdraw_ctv', ['status' => $status, 'reason' => $note, 'update_gettime' => gettime()], ' `id` = \'' . $row['id'] . '\' ');
                                if ($isUpdate) {
                                    insert_log($data_user['id'], 'Chỉnh sửa trạng thái lịch sử rút tiền CTV (ID ' . $row['id'] . ')');
                                    exit(JsonMsg('success', 'Cập nhật thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Cập nhật thất bại'));
                                }
                            }
                        case 'removeBank':
                            $check_bank = $db->get_row('SELECT * FROM `bank` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Ngân hàng không tồn tại'));
                            } else {
                                $isRemove = $db->remove('bank', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa ngân hàng [' . $check_bank['short_name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa ngân hàng thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa ngân hàng'));
                                }
                            }
                        case 'removeCategoryCaptcha':
                            $check_bank = $db->get_row('SELECT * FROM `category_captcha` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Dịch vụ không tồn tại'));
                            } else {
                                $isRemove = $db->remove('category_captcha', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa dịch vụ [' . $check_bank['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa dịch vụ thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa dịch vụ'));
                                }
                            }
                        case 'removePromotion':
                            $check_promotions = $db->get_row('SELECT * FROM `promotions` WHERE `id` = ' . $id);
                            if (!$check_promotions) {
                                exit(JsonMsg('error', 'Mốc khuyến mãi không tồn tại'));
                            } else {
                                $isRemove = $db->remove('promotions', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa mốc khuyến mãi [' . format_cash($check_promotions['amount']) . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa mốc khuyến mãi thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa mốc khuyến mãi'));
                                }
                            }
                        case 'removeCoupon':
                            $check_coupon = $db->get_row('SELECT * FROM `tbl_coupons` WHERE `id` = ' . $id);
                            if (!$check_coupon) {
                                exit(JsonMsg('error', 'Mã giảm giá không tồn tại'));
                            } else {
                                $isRemove = $db->remove('tbl_coupons', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện mã giảm giá [' . $check_coupon['code'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa mã giảm giá thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa mã giảm giá'));
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
