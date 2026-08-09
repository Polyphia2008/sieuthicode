<?php
// statically decompiled from unit.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if ($data_user['level'] != 'admin') {
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
                            $name_product = Anti_xss($_POST['name_product']);
                            $slug = create_slug($name_product);
                            $thele = Anti_xss($_POST['thele']);
                            $arr_data = [];
                            if (empty($name_product)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên đơn vị'));
                                } else {
                                    if (0 < $db->num_rows('SELECT * FROM `units` WHERE `slug` = \'' . $slug . '\'')) {
                                        exit(JsonMsg('error', 'Đơn vị này đã tồn tại trên hệ thống'));
                                    } else {
                                        $arr_data = [];
                                        if (isset($_POST['fields']) && is_array($_POST['fields'])) {
                                            foreach ($_POST['fields'] as $__key => $field) {
                                                $i = $__key;
                                                if (!(empty($field['name']) || empty($field['type']))) {
                                                    $arr_data[] = ['id' => $i, 'label' => $field['name'], 'type' => $field['type'], 'name' => toslug($field['name']), 'option' => $field['type'] === 'select' && !empty($field['options']) ? $field['options'] : null];
                                                }
                                            }
                                        }
                                        $json['author'] = 'SIEUTHICODE.NET';
                                        $json['name_product'] = $name_product;
                                        $json['thele'] = $thele;
                                        $json['data'] = $arr_data;
                                        $full_json = addslashes(json_encode($json));
                                        $db->query('INSERT INTO `units` (`stt`,`slug`,`detail`,`status`) VALUES (\'' . $stt . '\', \'' . $slug . '\',\'' . $full_json . '\',\'1\')');
                                        insert_log($data_user['id'], 'Thêm đơn vị ' . $name_product . ' ');
                                        exit(JsonMsg('success', 'Thêm thành công đơn vị ' . $name_product . ''));
                                    }
                                }
                            }
                        case 'update':
                            $stt = Anti_xss($_POST['stt']);
                            $id = Anti_xss($_POST['id']);
                            $name_product = Anti_xss($_POST['name_product']);
                            $status = Anti_xss($_POST['status']);
                            $slug = create_slug($name_product);
                            $thele = Anti_xss($_POST['thele']);
                            $arr_data = [];
                            if (empty($stt)) {
                                exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị'));
                            } else {
                                if (empty($name_product)) {
                                    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
                                } else {
                                    if (0 < $db->num_rows('SELECT * FROM `units` WHERE `slug` = \'' . $slug . '\' AND `id` != \'' . $id . '\'')) {
                                        exit(JsonMsg('error', 'Đơn vị này đã tồn tại trên hệ thống'));
                                    } else {
                                        $query_product = $db->get_row('SELECT * FROM `units` WHERE `id` = \'' . $id . '\'');
                                        if (!$query_product) {
                                            exit(JsonMsg('error', 'Đơn vị không tồn tại'));
                                        } else {
                                            $detail = json_decode($query_product['detail'], true);
                                            if (isset($_POST['fields']) && is_array($_POST['fields'])) {
                                                foreach ($_POST['fields'] as $__key => $field) {
                                                    $i = $__key;
                                                    if (!(empty($field['name']) || empty($field['type']))) {
                                                        $arr_data[] = ['id' => $i, 'label' => $field['name'], 'type' => $field['type'], 'name' => toslug($field['name']), 'option' => $field['type'] === 'select' && !empty($field['options']) ? $field['options'] : null];
                                                    }
                                                }
                                            }
                                            $json['author'] = 'SIEUTHICODE.NET';
                                            $json['name_product'] = $name_product;
                                            $json['thele'] = $thele;
                                            $json['data'] = $arr_data;
                                            $full_json = addslashes(json_encode($json));
                                            $db->query('UPDATE `units` SET 
                    `stt` = \'' . $stt . '\',
                    `detail` = \'' . $full_json . '\',
                    `slug` = \'' . $slug . '\', 
                    `status` = \'' . $status . '\' 
                    WHERE `id` = \'' . $id . '\'');
                                            insert_log($data_user['id'], 'Chỉnh sửa danh mục ' . $detail['name_product']);
                                            echo JsonMsg('success', 'Chỉnh sửa thành công danh mục ' . $name_product);
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
