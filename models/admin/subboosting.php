<?php
// statically decompiled from subboosting.php  [structured; all 1 record(s) structured]

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
                            $stt = Anti_xss($_POST['stt']);
                            $category = Anti_xss($_POST['category']);
                            $type = Anti_xss($_POST['type']);
                            $name_product = Anti_xss($_POST['name_product']);
                            $type_category = create_slug($name_product);
                            $thele = Anti_xss($_POST['thele']);
                            $fake = Anti_xss($_POST['fake']);
                            $link = Anti_xss($_POST['link']);
                            $coefficient = Anti_xss($_POST['coefficient']);
                            $min = Anti_xss($_POST['min']);
                            $max = Anti_xss($_POST['max']);
                            $unit = Anti_xss($_POST['unit']);
                            if (empty($stt)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
                                } else {
                                    if (empty($fake)) {
                                        exit(JsonMsg('error', 'Vui lòng nhập số lượng giao dịch ảo'));
                                    } else {
                                        if ($type == 'robux') {
                                            if (empty($coefficient)) {
                                                exit(JsonMsg('error', 'Vui lòng nhập hệ số bán Robux'));
                                            } else {
                                                if (empty($unit)) {
                                                    exit(JsonMsg('error', 'Vui lòng nhập đơn vị'));
                                                } else {
                                                    if (empty($min)) {
                                                        exit(JsonMsg('error', 'Vui lòng nhập giá mua tối thiểu'));
                                                    } else {
                                                        if (empty($max)) {
                                                            exit(JsonMsg('error', 'Vui lòng nhập giá mua tối đa'));
                                                        } else {
                                                            if (0 < $db->num_rows('SELECT * FROM `subboostings` WHERE `type_category` = \'' . $type_category . '\'')) {
                                                                exit(JsonMsg('error', 'Nhóm này đã tồn tại trên hệ thống'));
                                                            } else {
                                                                $arr_data = [];
                                                                if (isset($_POST['fields']) && is_array($_POST['fields'])) {
                                                                    foreach ($_POST['fields'] as $__key => $field) {
                                                                        $i = $__key;
                                                                        if (!(empty($field['name']) || empty($field['type']))) {
                                                                            $arr_data[] = ['id' => $i, 'label' => $field['name'], 'type' => $field['type'], 'name' => toslug($field['name']), 'option' => $field['type'] === 'select' && !empty($field['options']) ? $field['options'] : null, 'Content' => $field['type'] === 'select' && !empty($field['optionContent']) ? $field['optionContent'] : null];
                                                                        }
                                                                    }
                                                                }
                                                                $json['author'] = 'SIEUTHICODE.NET';
                                                                $json['name_product'] = $name_product;
                                                                $json['thumb'] = upload_file('thumb', 'product');
                                                                $json['thele'] = stripslashes($thele);
                                                                $json['coefficient'] = $coefficient;
                                                                $json['unit'] = $unit;
                                                                $json['min'] = $min;
                                                                $json['max'] = $max;
                                                                $json['data'] = $arr_data;
                                                                $full_json = json_encode($json);
                                                                $isInsert = $db->insert('subboostings', ['stt' => $stt, 'category' => $category, 'type' => $type, 'type_category' => $type_category, 'link' => $link, 'detail' => $full_json, 'fake' => $fake]);
                                                                if ($isInsert) {
                                                                    insert_log($data_user['id'], 'Thêm nhóm cày thuê ' . $name_product . ' ');
                                                                    exit(JsonMsg('success', 'Thêm thành công nhóm ' . $name_product . ''));
                                                                } else {
                                                                    exit(JsonMsg('error', 'Đã xảy ra lỗi khi thêm dữ liệu'));
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
                        case 'update':
                            $stt = Anti_xss($_POST['stt']);
                            $id = Anti_xss($_POST['id']);
                            $category = Anti_xss($_POST['category']);
                            $type = Anti_xss($_POST['type']);
                            $name_product = Anti_xss($_POST['name_product']);
                            $status = Anti_xss($_POST['status']);
                            $type_category = create_slug($name_product);
                            $thele = Anti_xss($_POST['thele']);
                            $fake = Anti_xss($_POST['fake']);
                            $link = Anti_xss($_POST['link']);
                            $coefficient = Anti_xss($_POST['coefficient']);
                            $min = Anti_xss($_POST['min']);
                            $max = Anti_xss($_POST['max']);
                            $unit = Anti_xss($_POST['unit']);
                            $arr_data = [];
                            if (empty($stt)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
                                } else {
                                    if (empty($fake)) {
                                        exit(JsonMsg('error', 'Vui lòng nhập số lượng giao dịch ảo'));
                                    } else {
                                        if ($type == 'robux') {
                                            if (empty($coefficient)) {
                                                exit(JsonMsg('error', 'Vui lòng nhập hệ số bán Robux'));
                                            } else {
                                                if (empty($unit)) {
                                                    exit(JsonMsg('error', 'Vui lòng nhập đơn vị'));
                                                } else {
                                                    if (empty($min)) {
                                                        exit(JsonMsg('error', 'Vui lòng nhập giá mua tối thiểu'));
                                                    } else {
                                                        if (empty($max)) {
                                                            exit(JsonMsg('error', 'Vui lòng nhập giá mua tối đa'));
                                                        } else {
                                                            if (0 < $db->num_rows('SELECT * FROM `subboostings` WHERE `type_category` = \'' . $type_category . '\' AND `id` != \'' . $id . '\'')) {
                                                                exit(JsonMsg('error', 'Nhóm này đã tồn tại trên hệ thống'));
                                                            } else {
                                                                $query_product = $db->get_row('SELECT * FROM `subboostings` WHERE `id` = \'' . $id . '\'');
                                                                if (!$query_product) {
                                                                    exit(JsonMsg('error', 'Sản phẩm không tồn tại'));
                                                                } else {
                                                                    $detail = json_decode($query_product['detail'], true);
                                                                    if (isset($_POST['fields']) && is_array($_POST['fields'])) {
                                                                        foreach ($_POST['fields'] as $__key => $field) {
                                                                            $i = $__key;
                                                                            if (!(empty($field['name']) || empty($field['type']))) {
                                                                                $arr_data[] = ['id' => $i, 'label' => $field['name'], 'type' => $field['type'], 'name' => toslug($field['name']), 'option' => $field['type'] === 'select' && !empty($field['options']) ? $field['options'] : null, 'Content' => $field['type'] === 'select' && !empty($field['optionContent']) ? $field['optionContent'] : null];
                                                                            }
                                                                        }
                                                                    }
                                                                    $json['author'] = 'SIEUTHICODE.NET';
                                                                    $json['name_product'] = $name_product;
                                                                    $json['thumb'] = update_file('thumb', $detail['thumb'], 'product');
                                                                    $json['thele'] = stripslashes($thele);
                                                                    $json['coefficient'] = $coefficient;
                                                                    $json['unit'] = $unit;
                                                                    $json['min'] = $min;
                                                                    $json['max'] = $max;
                                                                    $json['data'] = $arr_data;
                                                                    $full_json = json_encode($json);
                                                                    $isUpdate = $db->update('subboostings', ['detail' => $full_json, 'type' => $type, 'category' => $category, 'type_category' => $type_category, 'link' => $link, 'stt' => $stt, 'fake' => $fake, 'status' => $status], ' `id` = \'' . $id . '\' ');
                                                                    insert_log($data_user['id'], 'Chỉnh sửa danh mục ' . $detail['name_product']);
                                                                    echo JsonMsg('success', 'Chỉnh sửa thành công danh mục ' . $name_product);
                                                                    break;
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
