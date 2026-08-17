<?php
// statically decompiled from delete.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (!is_admin_account($data_user)) {
            http_response_code(403);
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            // Bắt buộc CSRF token hợp lệ cho mọi thao tác xoá (admin/superadmin).
            verify_csrf_token(true);
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $id = Anti_xss($_POST['id']);
                $action = Anti_xss($_POST['action']);
                if (empty($id) || empty($action)) {
                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                } else {
                    switch ($action) {
                        case 'removeIpWhite':
                            $check = $db->get_row('SELECT * FROM `ip_white` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'IP không tồn tại'));
                            } else {
                                $isRemove = $db->remove('ip_white', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa Ip được phép truy cập admin [' . $check['ip'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa IP thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa IP'));
                                }
                            }
                        case 'removeUser':
                            $check_user = $db->get_row('SELECT * FROM `users` WHERE `id` = ' . $id);
                            if (!$check_user) {
                                exit(JsonMsg('error', 'Người dùng không tồn tại'));
                            } elseif (!is_superadmin_account($data_user) && is_superadmin_account($check_user)) {
                                // Admin thường không bao giờ được xoá superadmin.
                                http_response_code(403);
                                exit(JsonMsg('error', 'Không thể xoá tài khoản superadmin này'));
                            }
                            // TOCTOU: nếu xoá một superadmin đang ACTIVE thì phải
                            // khoá TOÀN BỘ active superadmin (không chỉ hàng target)
                            // để serialize hai request đồng thời xoá hai superadmin
                            // khác nhau A/B — chỉ khoá hàng target thì cả hai cùng
                            // đếm thấy 2 và cùng xoá thành công => 0 active superadmin.
                            $__isActiveSuper = is_superadmin_account($check_user)
                                && (int) ($check_user['banned'] ?? 0) === 0;
                            if ($__isActiveSuper) {
                                superadmin_guard_begin($db);
                                // Re-read target TRONG transaction: target phải còn
                                // tồn tại và vẫn là active superadmin (không tin dữ
                                // liệu $check_user đã đọc trước transaction).
                                $lockedTarget = $db->get_row('SELECT `id`, `level`, `banned`, `username` FROM `users` WHERE `id` = \''
                                    . (int) $check_user['id'] . '\'');
                                $targetStillActive = is_array($lockedTarget)
                                    && ($lockedTarget['level'] ?? '') === 'superadmin'
                                    && (int) ($lockedTarget['banned'] ?? 1) === 0;
                                if (!$targetStillActive) {
                                    superadmin_guard_rollback($db);
                                    http_response_code(403);
                                    exit(JsonMsg('error', 'Tài khoản superadmin này vừa được thay đổi bởi một thao tác khác'));
                                }
                                // Đếm lại số ACTIVE superadmin bên trong transaction
                                // (sau khi đã khoá toàn bộ) để quyết định về 0 không.
                                if (count_superadmins($db) <= 1) {
                                    superadmin_guard_rollback($db);
                                    http_response_code(403);
                                    exit(JsonMsg('error', 'Không thể xoá superadmin cuối cùng của hệ thống'));
                                }
                                $isRemove = $db->remove('users', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    superadmin_guard_commit($db);
                                    insert_log($data_user['id'], 'Thực hiện xóa thành viên [' . $lockedTarget['username'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa người dùng thành công'));
                                }
                                superadmin_guard_rollback($db);
                                exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa người dùng'));
                            }
                            // Banned superadmin / admin / member: xoá trực tiếp (không ảnh
                            // hưởng invariant "còn ít nhất 1 superadmin ACTIVE").
                            $isRemove = $db->remove('users', ' `id` = \'' . $id . '\' ');
                            if ($isRemove) {
                                insert_log($data_user['id'], 'Thực hiện xóa thành viên [' . $check_user['username'] . '] ra khỏi hệ thống');
                                exit(JsonMsg('success', 'Xóa người dùng thành công'));
                            } else {
                                exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa người dùng'));
                            }
                        case 'removeBank':
                            if (!is_superadmin_account($data_user)) {
                                http_response_code(403);
                                exit(JsonMsg('error', 'Chức năng này chỉ dành cho Superadmin'));
                            }
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
                        case 'removeCategory':
                            $check = $db->get_row('SELECT * FROM `categories` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            } else {
                                $isRemove = $db->remove('categories', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    $iconPath = '../..' . $check['icon'];
                                    if (!empty($check['icon']) && file_exists($iconPath)) {
                                        unlink($iconPath);
                                    }
                                    insert_log($data_user['id'], 'Thực hiện xóa Chuyên mục [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa Chuyên mục thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                                }
                            }
                        case 'removeCategoryItem':
                            $check = $db->get_row('SELECT * FROM `category_items` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            } else {
                                $isRemove = $db->remove('category_items', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa Chuyên mục [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa Chuyên mục thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                                }
                            }
                        case 'removeSubCategory':
                            $subcategoryId = (int) $id;
                            $subcategory = $db->get_row(
                                "SELECT * FROM `subcategory` WHERE `id` = '" . $subcategoryId . "' LIMIT 1"
                            );
                            if (!$subcategory) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            }

                            // Xóa danh mục theo yêu cầu kể cả khi còn tài khoản.
                            // Dọn các bản ghi phụ và kho tài khoản trong cùng một
                            // transaction để không tạo account mồ côi. Lịch sử mua
                            // được giữ lại vì chứa snapshot detail cho khách hàng.
                            $accountRows = $db->get_list(
                                "SELECT `id` FROM `accounts` WHERE `sub_id` = '" . $subcategoryId . "'"
                            );
                            $accountIds = [];
                            foreach ($accountRows as $accountRow) {
                                $accountIds[] = (int) $accountRow['id'];
                            }

                            $db->query('START TRANSACTION');
                            $deleteOk = true;
                            if (count($accountIds) > 0) {
                                $idList = implode(',', $accountIds);
                                $deleteOk = $db->query(
                                    "DELETE FROM `favorites` WHERE `acc_id` IN (" . $idList . ")"
                                ) !== false && $deleteOk;
                                $deleteOk = $db->query(
                                    "DELETE FROM `flash_sale_products` WHERE `product_id` IN (" . $idList . ")"
                                ) !== false && $deleteOk;
                            }
                            $deleteOk = $db->query(
                                "DELETE FROM `accounts` WHERE `sub_id` = '" . $subcategoryId . "'"
                            ) !== false && $deleteOk;
                            $isRemove = $db->remove('subcategory', "`id` = '" . $subcategoryId . "'");
                            $subcategoryRemoved = $isRemove && $db->affected_rows() === 1;

                            if (!$deleteOk || !$subcategoryRemoved) {
                                $db->query('ROLLBACK');
                                exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                            }
                            $db->query('COMMIT');

                            $detail = json_decode((string) $subcategory['detail'], true);
                            $subcategoryName = is_array($detail) && !empty($detail['name_product'])
                                ? (string) $detail['name_product']
                                : ('#' . $subcategoryId);
                            insert_log(
                                $data_user['id'],
                                'Thực hiện xóa Chuyên mục [' . $subcategoryName . '] và '
                                . count($accountIds) . ' tài khoản trong kho'
                            );
                            exit(JsonMsg(
                                'success',
                                'Xóa Chuyên mục thành công cùng ' . count($accountIds) . ' tài khoản trong kho'
                            ));
                        case 'removeUnit':
                            $subcategory = $db->get_row('SELECT * FROM `units` WHERE `id` = ' . $id);
                            if (!$subcategory) {
                                exit(JsonMsg('error', 'Đơn vị không tồn tại'));
                            } else {
                                if (0 < $db->num_rows('SELECT * FROM `package_units` WHERE `unit_id` = \'' . $subcategory['id'] . '\'')) {
                                    exit(JsonMsg('error', 'Đơn vị này đang chứa gói, bạn không thể xóa'));
                                } else {
                                    $detail = json_decode($subcategory['detail'], true);
                                    $isRemove = $db->remove('units', ' `id` = \'' . $id . '\' ');
                                    if ($isRemove) {
                                        insert_log($data_user['id'], 'Thực hiện xóa Đơn vị [' . $detail['name_product'] . '] ra khỏi hệ thống');
                                        exit(JsonMsg('success', 'Xóa Đơn vị thành công'));
                                    } else {
                                        exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                                    }
                                }
                            }
                        case 'removeCategoryBoosting':
                            $check_bank = $db->get_row('SELECT * FROM `boostings` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            } else {
                                $isRemove = $db->remove('boostings', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa Chuyên mục [' . $check_bank['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa Chuyên mục thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                                }
                            }
                        case 'removePackage':
                            $check_bank = $db->get_row('SELECT * FROM `package_boostings` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Sản phẩm không tồn tại'));
                            } else {
                                $isRemove = $db->remove('package_boostings', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa sản phẩm [' . $check_bank['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa sản phẩm thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa sản phẩm'));
                                }
                            }
                        case 'removePackageUnit':
                            $check = $db->get_row('SELECT * FROM `package_units` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Gói không tồn tại'));
                            } else {
                                $isRemove = $db->remove('package_units', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa sản phẩm [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa gói thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa gói'));
                                }
                            }
                        case 'removeSubboosting':
                            $check_bank = $db->get_row('SELECT * FROM `subboostings` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            } else {
                                $detail = json_decode($check_bank['detail'], true);
                                $isRemove = $db->remove('subboostings', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../../' . $detail['thumb']);
                                    insert_log($data_user['id'], 'Thực hiện xóa nhóm Chuyên mục [' . $check_bank['id'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa Chuyên mục thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Chuyên mục'));
                                }
                            }
                        case 'removeBanner':
                            $check_bank = $db->get_row('SELECT * FROM `banner` WHERE `id` = ' . $id);
                            if (!$check_bank) {
                                exit(JsonMsg('error', 'Banner không tồn tại'));
                            } else {
                                $isRemove = $db->remove('banner', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $check_bank['image']);
                                    insert_log($data_user['id'], 'Thực hiện xóa banner [' . $check_bank['id'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa banner thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa banner'));
                                }
                            }
                        case 'removeSpin':
                            $check = $db->get_row('SELECT * FROM `spin_quests` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Vòng quay không tồn tại'));
                            } else {
                                $isRemove = $db->remove('spin_quests', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $check['image']);
                                    unlink('../..' . $check['cover']);
                                    insert_log($data_user['id'], 'Thực hiện xóa vòng quay [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa vòng quay thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa vòng quay'));
                                }
                            }
                        case 'removeTag':
                            $check = $db->get_row('SELECT * FROM `tag` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Nhãn dán không tồn tại'));
                            } else {
                                $isRemove = $db->remove('tag', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $check['images']);
                                    insert_log($data_user['id'], 'Thực hiện xóa nhãn dán [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa nhãn dán thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa nhãn dán'));
                                }
                            }
                        case 'removeReview':
                            $check = $db->get_row('SELECT * FROM `reviews` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Đánh giá không tồn tại'));
                            } else {
                                $isRemove = $db->remove('reviews', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa đánh giá [' . $check['review'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa đánh giá thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa đánh giá'));
                                }
                            }
                        case 'removeTick':
                            $check = $db->get_row('SELECT * FROM `tick` WHERE `id` = ' . $id);
                            if (!$check) {
                                exit(JsonMsg('error', 'Nhãn dán không tồn tại'));
                            } else {
                                $isRemove = $db->remove('tick', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $check['icon']);
                                    insert_log($data_user['id'], 'Thực hiện xóa tick [' . $check['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa tick thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa tick'));
                                }
                            }
                        case 'removeAccountFlashSale':
                            $check_accouunt = $db->get_row('SELECT * FROM `flash_sale_products` WHERE `id` = ' . $id);
                            if (!$check_accouunt) {
                                exit(JsonMsg('error', 'Tài khoản không tồn tại trong chiến dịch'));
                            } else {
                                $isRemove = $db->remove('flash_sale_products', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    insert_log($data_user['id'], 'Thực hiện xóa tài khoản [#' . $check_accouunt['id'] . '] ra khỏi chiến dịch flash sale');
                                    exit(JsonMsg('success', 'Xóa tài khoản thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa tài khoản'));
                                }
                            }
                        case 'removeFlashSale':
                            $flashsale = $db->get_row('SELECT * FROM `flash_sales` WHERE `id` = ' . $id);
                            if (!$flashsale) {
                                exit(JsonMsg('error', 'Tài khoản không tồn tại trong chiến dịch'));
                            } else {
                                $isRemove = $db->remove('flash_sales', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    $db->query('DELETE FROM `flash_sale_products` WHERE `flash_sale_id` = \'' . $flashsale['id'] . '\'');
                                    insert_log($data_user['id'], 'Thực hiện xóa Flash Sale [#' . $flashsale['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa Flash Sale thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa Flash Sale'));
                                }
                            }
                        case 'removePost':
                            $post = $db->get_row('SELECT * FROM `posts` WHERE `id` = ' . $id);
                            if (!$post) {
                                exit(JsonMsg('error', 'Bài viết không tồn tại'));
                            } else {
                                $isRemove = $db->remove('posts', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $post['image']);
                                    insert_log($data_user['id'], 'Thực hiện xóa bài viết [' . $post['title'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa bài viết thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa bài viết'));
                                }
                            }
                        case 'removeLink':
                            $post = $db->get_row('SELECT * FROM `links` WHERE `id` = ' . $id);
                            if (!$post) {
                                exit(JsonMsg('error', 'Liên kết không tồn tại'));
                            } else {
                                $isRemove = $db->remove('links', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $post['image']);
                                    insert_log($data_user['id'], 'Thực hiện xóa liên kết [' . $post['title'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa liên kết thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa liên kết'));
                                }
                            }
                        case 'removePr':
                            $post = $db->get_row('SELECT * FROM `advertisement` WHERE `id` = ' . $id);
                            if (!$post) {
                                exit(JsonMsg('error', 'Liên kết không tồn tại'));
                            } else {
                                $isRemove = $db->remove('advertisement', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $post['image']);
                                    insert_log($data_user['id'], 'Thực hiện xóa quảng cáo ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa quảng cáo thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa quảng cáo'));
                                }
                            }
                        case 'removeCategoryBlog':
                            $check_category = $db->get_row('SELECT * FROM `post_category` WHERE `id` = ' . $id);
                            if (!$check_category) {
                                exit(JsonMsg('error', 'Chuyên mục không tồn tại'));
                            } else {
                                $isRemove = $db->remove('post_category', ' `id` = \'' . $id . '\' ');
                                if ($isRemove) {
                                    unlink('../..' . $check_category['icon']);
                                    insert_log($data_user['id'], 'Thực hiện xóa chuyên mục bài viết [' . $check_category['name'] . '] ra khỏi hệ thống');
                                    exit(JsonMsg('success', 'Xóa chuyên mục bài viết thành công'));
                                } else {
                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa chuyên mục bài viết'));
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
