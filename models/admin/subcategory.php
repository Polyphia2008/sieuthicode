<?php
// statically decompiled from subcategory.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (!is_admin_account($data_user)) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') != 0) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
            } else {
                $action = Anti_xss($_POST['action']);
                if (empty($action)) {
                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                } else {
                    switch ($action) {
                        case 'add':
                            $type = Anti_xss($_POST['type']);
                            $stt = Anti_xss($_POST['stt']);
                            $category = Anti_xss($_POST['category']);
                            $name_product = Anti_xss($_POST['name_product']);
                            $type_category = create_slug($name_product);
                            $thele = $_POST['thele'];
                            $tag = Anti_xss($_POST['tag']);
                            $cash = Anti_xss($_POST['cash']);
                            $fake = Anti_xss($_POST['fake']);
                            $arr_data = [];
                            if (empty($name_product)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
                                } else {
                                    if (0 < $db->num_rows('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type_category . '\'')) {
                                        exit(JsonMsg('error', 'Sản phẩm này đã tồn tại trên hệ thống'));
                                    } else {
                                        if (empty($type)) {
                                            exit(JsonMsg('error', 'Vui lòng chọn loại tài khoản'));
                                        } else {
                                            if ($type == 'RANDOM') {
                                                if (empty($cash)) {
                                                    exit(JsonMsg('error', 'Vui lòng nhập giá tiền bán random'));
                                                } else {
                                                    $i = 2;
                                                    while ($i < count($_POST['data_name'])) {
                                                        array_push($arr_data, ['id' => $i, 'label' => $_POST['data_name'][$i], 'type' => $_POST['data_type'][$i], 'name' => toslug($_POST['data_name'][$i]), 'value' => $_POST['data_value'][$i], 'show' => $_POST['data_show'][$i]]);
                                                        ++$i;
                                                    }
                                                    $json['author'] = 'SIEUTHICODE.NET';
                                                    $json['name_product'] = $name_product;
                                                    $json['thumb'] = upload_file('thumb', 'product');
                                                    $json['tag'] = $tag;
                                                    $json['cash'] = $cash;
                                                    $json['thele'] = $thele;
                                                    $json['data'] = $arr_data;
                                                    $full_json = addslashes(json_encode($json));
                                                    $db->query('INSERT INTO `subcategory`(`stt`, `category`, `type`, `type_category`, `detail`,`fake`) VALUES (\'' . $stt . '\', \'' . $category . '\', \'' . $type . '\', \'' . $type_category . '\', \'' . $full_json . '\',\'' . $fake . '\')');
                                                    insert_log($data_user['id'], 'Thêm ngăn tài khoản game ' . $name_product . ' ');
                                                    exit(JsonMsg('success', 'Thêm thành công danh mục ' . $name_product . ''));
                                                }
                                            } else {
                                                if (empty($fake)) {
                                                    exit(JsonMsg('error', 'Vui lòng nhập giao dịch ảo'));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        case 'update':
                            $id = Anti_xss($_POST['id']);
                            $stt = Anti_xss($_POST['stt']);
                            $category = Anti_xss($_POST['category']);
                            $name_product = Anti_xss($_POST['name_product']);
                            $type_category = create_slug($name_product);
                            $thele = $_POST['thele'];
                            $tag = Anti_xss($_POST['tag']);
                            $display = Anti_xss($_POST['status']);
                            $fake = Anti_xss($_POST['fake']);
                            $arr_data = [];
                            if (empty($stt)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
                                } else {
                                    if ($db->num_rows('SELECT * FROM `subcategory` WHERE `id` = \'' . $id . '\'') < 1) {
                                        exit(JsonMsg('error', 'Không tìm thấy sản phẩm để chỉnh sửa'));
                                    } else {
                                        if (empty($fake)) {
                                            exit(JsonMsg('error', 'Vui lòng nhập giao dịch ảo'));
                                        } else {
                                            $query = $db->get_row('SELECT * FROM `subcategory` WHERE `id` = \'' . $id . '\' AND `type` IN (\'ACCOUNT\',\'RANDOM\')');
                                            $detail = json_decode($query['detail'], true);
                                            $i = 2;
                                            while ($i < count($_POST['data_name'])) {
                                                array_push($arr_data, ['id' => $i, 'label' => $_POST['data_name'][$i], 'type' => $_POST['data_type'][$i], 'name' => toslug($_POST['data_name'][$i]), 'value' => $_POST['data_value'][$i], 'show' => $_POST['data_show'][$i]]);
                                                ++$i;
                                            }
                                            $json['author'] = 'SIEUTHICODE.NET';
                                            $json['name_product'] = $name_product;
                                            $json['thumb'] = update_file('thumb', $detail['thumb'], 'product');
                                            $json['tag'] = $tag;
                                            if ($query['type'] == 'RANDOM') {
                                                $price = Anti_xss($_POST['price']);
                                                $json['cash'] = $price;
                                            }
                                            $json['thele'] = $thele;
                                            $json['data'] = $arr_data;
                                            $full_json = addslashes(json_encode($json));
                                            $db->query('UPDATE `subcategory` SET `category` = \'' . $category . '\',`stt` = \'' . $stt . '\',`detail` = \'' . $full_json . '\',`fake` = \'' . $fake . '\',`status` = \'' . $display . '\' WHERE `id` = \'' . $id . '\'');
                                            insert_log($data_user['id'], 'Chỉnh sửa danh mục ' . $detail['name_product'] . '');
                                            echo JsonMsg('success', 'Chỉnh sửa thành công danh mục ' . $name_product . '');
                                            break;
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
