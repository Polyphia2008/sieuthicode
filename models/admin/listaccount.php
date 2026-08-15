<?php
// statically decompiled from listaccount.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$column = ['id', 'id', 'type_category', 'username_post', 'money', 'sale', 'status', 'created_at'];
$start = Anti_xss($_POST['start']);
$length = Anti_xss($_POST['length']);
$username = Anti_xss($_POST['username']);
$type = Anti_xss($_POST['type']);
if (!$user) {
    exit(JsonMsg('error', 'Bạn chưa đăng nhập'));
} else {
    if (!is_admin_account($data_user)) {
        exit(JsonMsg('error', 'Bạn không có quyền truy cập trang này'));
    } else {
        $sql_type = '';
        $sql_username = '';
        if ($type != '') {
            $sql_type = 'AND `type_category` = \'' . $type . '\'';
        }
        if ($username != '') {
            $sql_username = 'AND `username_post` = \'' . $username . '\'';
        }
        if (isset($_POST['order'])) {
            $orderby = 'ORDER BY `' . $column[$_POST['order'][0]['column']] . '` ' . $_POST['order'][0]['dir'] . '';
        } else {
            $orderby = 'ORDER BY `id` DESC';
        }
        $data = [];
        $sql_query = 'SELECT * FROM `accounts` WHERE `type_category` = \'' . $type . '\'';
        $query = $db->get_row($sql_query);
        if (!$query) {
            $output = ['draw' => $_POST['draw'], 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => $data];
            exit(json_encode($output));
        } else {
            $detail_query = json_decode($query['detail'], true);
            $arr_query = $detail_query['data'];
            $i = 2;
            $sql = [];
            $a = 2;
            while ($a < count($arr_query)) {
                if ($arr_query[$a]['type'] != 'password') {
                    $arr_name = $arr_query[$a]['name'];
                    $id = $arr_query[$a]['id'];
                    if (isset($_POST['data'][$i]['value']) && is_string($_POST['data'][$i]['value'])) {
                        $arr_value = Anti_xss($_POST['data'][$i]['value']);
                        $sql[] = 'AND JSON_UNQUOTE(JSON_EXTRACT(JSON_EXTRACT(`detail`, \'$.data[' . $id . ']\'), \'$.' . $arr_name . '\')) = \'' . $arr_value . '\'';
                    }
                    $i += 3;
                }
                ++$a;
            }
            $implode = implode('', $sql);
            foreach ($db->get_list('SELECT * FROM `accounts` WHERE `id` != \'0\' ' . $sql_type . ' ' . $sql_username . ' ' . $implode . ' ' . $orderby . ' LIMIT ' . $start . ', ' . $length) as $info) {
                $query_product = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $info['type_category'] . '\'');
                $detail_product = json_decode($query_product['detail'], true);
                $arr_img = json_decode($info['image'], true);
                $detail = json_decode($info['detail'], true);
                $arr_detail = $detail['data'];
                if ($info['status'] == 'on') {
                    $status = 'Chưa bán';
                } else {
                    if ($info['status'] == 'off') {
                        $status = 'Đã bán';
                    }
                }
                $json = [];
                $json[] = '<input type="checkbox" data-id="' . $info['id'] . '" name="checkbox_accounts"
                                            class="form-check-input" value="' . $info['id'] . '" />';
                $json[] = $info['id'];
                $json[] = $info['type'] == 'ACCOUNT' ? '<img width="100px" src="' . DOMAIN . '/' . $arr_img[0] . '">' : '<img width="100px" src="' . DOMAIN . '/' . $detail_product['thumb'] . '">';
                $json[] = $info['username_post'];
                $json[] = decodecryptData($arr_detail[0]['value']);
                $json[] = number_format($info['money']);
                $json[] = '' . $info['sale'] . '%';
                $json[] = $status;
                $json[] = '<a class="btn btn-info btn-sm" href="/cpanel/account/edit/' . $info['id'] . '" target="_blank"><i class="fa fa-pen"></i></a>';
                $json[] = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmAction(' . $info['id'] . ')"><i class="fa fa-trash"></i></button>';
                $json[] = date('H:i d-m-Y', $info['created_at']);
                $data[] = $json;
            }
            $output = ['draw' => $_POST['draw'], 'recordsTotal' => $db->num_rows('SELECT * FROM `accounts` WHERE `type_category` = \'' . $type . '\''), 'recordsFiltered' => $db->num_rows('SELECT * FROM `accounts` WHERE `id` != \'0\' ' . $sql_type . ' ' . $sql_username . ' ' . $implode), 'data' => $data];
            exit(json_encode($output));
        }
    }
}
