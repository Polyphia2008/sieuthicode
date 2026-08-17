<?php
// statically decompiled from account.php  [structured; all 2 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if (!function_exists('admin_random_account_parse_line')) {
    /**
     * Parse one RANDOM stock line into configured fields plus optional details.
     *
     * Supported examples for a two-field account/password schema:
     *   user|pass
     *   user:pass
     *   user:pass | Name: A | Level: 30 | Rank: Kim Cương
     *   user|pass|Name: A|Level: 30
     *
     * Everything after the configured fields is preserved as one optional
     * "Thông tin chi tiết" value; it is not required for other stock lines.
     */
    function admin_random_account_parse_line($line, $expectedFields)
    {
        $line = trim((string) $line);
        $segments = array_map('trim', explode('|', $line));
        $fields = [];
        $details = [];

        // Preferred extended format: account:password | arbitrary details...
        if ($expectedFields === 2 && isset($segments[0]) && strpos($segments[0], ':') !== false) {
            $credentials = array_map('trim', explode(':', $segments[0], 2));
            $fields = $credentials;
            $details = array_slice($segments, 1);
        } else {
            $fields = array_slice($segments, 0, $expectedFields);
            $details = array_slice($segments, $expectedFields);

            // Legacy short format without any pipe: account:password.
            if (count($fields) < $expectedFields && $expectedFields === 2
                && strpos($line, '|') === false && strpos($line, ':') !== false) {
                $fields = array_map('trim', explode(':', $line, 2));
                $details = [];
            }
        }

        $details = array_values(array_filter($details, function ($value) {
            return trim((string) $value) !== '';
        }));

        return [
            'fields' => $fields,
            'details' => implode(' | ', $details),
        ];
    }
}

if (!function_exists('admin_encrypt_random_detail')) {
    /** Encrypt metadata that can be longer than one RSA-2048 plaintext block. */
    function admin_encrypt_random_detail($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        $chunks = [];
        foreach (str_split($value, 180) as $chunk) {
            $chunks[] = encryptData($chunk);
        }
        return 'rsa_chunks_v1:' . base64_encode(json_encode($chunks));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user) {
        if (!is_admin_account($data_user)) {
            exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
        } else {
            if ($db->site('status_demo') == 1) {
                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này'));
            } else {
                $action = Anti_xss($_POST['action']);
                if (empty($action)) {
                    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                } else {
                    switch ($action) {
                        case 'add':
                            $type_category = Anti_xss($_POST['type_category']);
                            $link_anh = [];
                            $arr_data = [];
                            $date = time();
                            if ($db->num_rows('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type_category . '\' AND `type` IN (\'ACCOUNT\',\'RANDOM\')') < 1) {
                                exit(JsonMsg('error', 'Không tìm thấy danh mục tài khoản'));
                            } else {
                                $query = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type_category . '\' AND `type` IN (\'ACCOUNT\',\'RANDOM\')');
                                $detail = json_decode($query['detail'], true);
                                if ($query['type'] == 'ACCOUNT') {
                                    $cash = Anti_xss($_POST['cash']);
                                    $sale = Anti_xss($_POST['sale']);
                                    if ($cash < 0) {
                                        exit(JsonMsg('error', 'Giá tiền không được dưới 0đ'));
                                    } else {
                                        if ($sale < 0) {
                                            exit(JsonMsg('error', 'Khuyến mại không được dưới 0%'));
                                        } else {
                                            $fileNames = isset($_FILES['image']['name']) && is_array($_FILES['image']['name'])
                                                ? $_FILES['image']['name']
                                                : [];
                                            $imageUrlsRaw = trim((string) ($_POST['image_urls'] ?? ''));
                                            $hasUpload = count(array_filter($fileNames, function ($name) {
                                                return trim((string) $name) !== '';
                                            })) > 0;
                                            if (!$hasUpload && $imageUrlsRaw === '') {
                                                exit(JsonMsg('error', 'Vui lòng tải ảnh lên hoặc nhập URL ảnh'));
                                            } else {
                                                $i = 0;
                                                while ($i < count($fileNames)) {
                                                    array_push($link_anh, upload_multiple_file('image', 'product', $i));
                                                    ++$i;
                                                }
                                                $imageUrls = array_values(array_filter(array_map(
                                                    'trim',
                                                    preg_split('/\r\n|\r|\n/', $imageUrlsRaw)
                                                ), function ($url) {
                                                    return $url !== '';
                                                }));
                                                if (count($imageUrls) > 10) {
                                                    exit(JsonMsg('error', 'Mỗi tài khoản chỉ được nhập tối đa 10 URL ảnh'));
                                                }
                                                try {
                                                    foreach ($imageUrls as $imageUrl) {
                                                        $link_anh[] = upload_image_from_url($imageUrl, 'product');
                                                    }
                                                } catch (Throwable $imageError) {
                                                    exit(JsonMsg('error', $imageError->getMessage()));
                                                }
                                                $link_anh = array_values(array_filter($link_anh, function ($v) {
                                                    return is_string($v) && $v !== '';
                                                }));
                                                if (count($link_anh) < 1) {
                                                    exit(JsonMsg('error', 'Không có ảnh hợp lệ để đăng tài khoản'));
                                                }
                                                $i = 0;
                                                while ($i < count($detail['data'])) {
                                                    $field = $detail['data'][$i]['name'];
                                                    if ($field === '' || $field === null || !isset($_POST[$field]) || Anti_xss($_POST[$field]) === '') {
                                                        $label = isset($detail['data'][$i]['label']) && $detail['data'][$i]['label'] !== '' ? $detail['data'][$i]['label'] : 'đầy đủ thông tin';
                                                        exit(JsonMsg('error', 'Vui lòng nhập ' . $label));
                                                    }
                                                    if ($field == 'taikhoan' || $field == 'matkhau') {
                                                        $name = encryptData(Anti_xss($_POST[$field]));
                                                    } else {
                                                        $name = Anti_xss($_POST[$field]);
                                                    }
                                                    array_push($arr_data, ['id' => $i, 'label' => $detail['data'][$i]['label'], 'type' => $detail['data'][$i]['type'], 'name' => $field, 'value' => encryptData(Anti_xss($_POST[$field])), 'show' => $detail['data'][$i]['show'], $field => $name]);
                                                    ++$i;
                                                }
                                                $json_image = json_encode($link_anh);
                                                $json['author'] = 'db.NET';
                                                $json['name_product'] = $detail['name_product'];
                                                $json['data'] = $arr_data;
                                                $full_detail = addslashes(json_encode($json));
                                                $db->query('INSERT INTO `accounts`(`sub_id`,`type`, `type_category`, `username_post`, `detail`, `image`, `money`, `sale`, `updated_at`, `created_at`) VALUES (\'' . $query['id'] . '\',\'' . $query['type'] . '\', \'' . $type_category . '\', \'' . $data_user['username'] . '\', \'' . $full_detail . '\', \'' . $json_image . '\', \'' . $cash . '\', \'' . $sale . '\',\'' . $date . '\', \'' . $date . '\')');
                                                insert_log($data_user['id'], 'Thêm tài khoản ' . $detail['name_product'] . '');
                                                exit(JsonMsg('success', 'Đăng tài khoản thành công'));
                                            }
                                        }
                                    }
                                } else {
                                    if ($query['type'] == 'RANDOM') {
                                        // Keep separators and metadata exactly as entered. Anti_xss()
                                        // applies addslashes(), which would make JSON-like details show
                                        // unwanted backslashes after purchase. Values are encrypted and
                                        // escaped when the account JSON is written to SQL; output is also
                                        // HTML-escaped in customer history.
                                        $data = str_replace("\0", '', (string) ($_POST['data'] ?? ''));
                                        $data = trim($data);
                                        if (empty($data)) {
                                            exit(JsonMsg('error', 'Vui lòng nhập dữ liệu cần đăng'));
                                        } else {
                                            function upload_random($arr, $account, $name_product, $extraDetail = '')
{
    $arr_data = [];
    $i = 0;
    while ($i < count($arr)) {
        $value = isset($account[$i]) ? trim($account[$i]) : '';
        $arr_data[] = ['id' => $i, 'label' => $arr[$i]['label'], 'type' => $arr[$i]['type'], 'name' => $arr[$i]['name'], 'value' => $value === '' ? '' : encryptData($value), 'show' => $arr[$i]['show']];
        ++$i;
    }
    if (trim((string) $extraDetail) !== '') {
        $arr_data[] = [
            'id' => count($arr_data),
            'label' => 'Thông tin chi tiết',
            'type' => 'detail',
            'name' => 'thongtinchitiet',
            'value' => admin_encrypt_random_detail($extraDetail),
            'show' => 'off',
        ];
    }
    return ['author' => 'db.NET', 'name_product' => $name_product, 'data' => $arr_data];
}
                                            $arr = isset($detail['data']) && is_array($detail['data'])
                                                ? array_values($detail['data'])
                                                : [];
                                            if (count($arr) < 1) {
                                                exit(JsonMsg('error', 'Danh mục chưa cấu hình trường dữ liệu. Hãy sửa danh mục và thêm Tài khoản/Mật khẩu trước khi đăng kho.'));
                                            }
                                            $data = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $data))), function ($line) {
                                                return $line !== '';
                                            }));
                                            if (count($data) < 1) {
                                                exit(JsonMsg('error', 'Vui lòng nhập dữ liệu cần đăng'));
                                            }
                                            $parsedAccounts = [];
                                            foreach ($data as $lineNumber => $line) {
                                                $parsed = admin_random_account_parse_line($line, count($arr));
                                                $parts = $parsed['fields'];
                                                if (count($parts) !== count($arr) || in_array('', $parts, true)) {
                                                    exit(JsonMsg(
                                                        'error',
                                                        'Dòng ' . ($lineNumber + 1) . ' không đúng định dạng. Cần '
                                                        . count($arr) . ' trường theo thứ tự '
                                                        . implode('|', array_column($arr, 'label'))
                                                    ));
                                                }
                                                $parsedAccounts[] = $parsed;
                                            }
                                            foreach ($parsedAccounts as $parsedAccount) {
                                                $encoded = json_encode(
                                                    upload_random(
                                                        $arr,
                                                        $parsedAccount['fields'],
                                                        $detail['name_product'],
                                                        $parsedAccount['details']
                                                    ),
                                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                                );
                                                if ($encoded === false) {
                                                    exit(JsonMsg('error', 'Không thể tạo dữ liệu tài khoản để đăng'));
                                                }
                                                $full_json = addslashes($encoded);
                                                $db->query('INSERT INTO `accounts`(`sub_id`,`type`, `type_category`, `username_post`, `detail`, `image`, `money`, `sale`, `updated_at`, `created_at`) VALUES (\'' . $query['id'] . '\',\'' . $query['type'] . '\', \'' . $type_category . '\', \'' . $data_user['username'] . '\', \'' . $full_json . '\', \'\', \'' . $detail['cash'] . '\', \'0\', \'' . $date . '\', \'' . $date . '\')');
                                            }
                                            insert_log($data_user['id'], 'Thêm ' . count($data) . ' tài khoản ' . $detail['name_product'] . '');
                                            exit(JsonMsg('success', 'Đăng thành công ' . count($data) . ''));
                                        }
                                    } else {
                                        break;
                                    }
                                }
                            }
                        case 'update':
                            $id = Anti_xss($_POST['id']);
                            $cash = Anti_xss($_POST['cash']);
                            $sale = Anti_xss($_POST['sale']);
                            $type = Anti_xss($_POST['type']);
                            $type_category = Anti_xss($_POST['type_category']);
                            $status = Anti_xss($_POST['status']);
                            $link_anh = [];
                            $arr_data = [];
                            $date = time();
                            if (empty($cash)) {
                                exit(JsonMsg('error', 'Vui lòng nhập giá tiền'));
                            } else {
                                if ($cash < 0) {
                                    exit(JsonMsg('error', 'Giá tiền không được dưới 0đ'));
                                } else {
                                    if ($sale < 0) {
                                        exit(JsonMsg('error', 'Khuyến mại không được bé hơn 0%'));
                                    } else {
                                        if (100 < $sale) {
                                            exit(JsonMsg('error', 'Khuyến mại không được lớn hơn 100%'));
                                        } else {
                                            if (empty($status)) {
                                                exit(JsonMsg('error', 'Vui lòng chọn trạng thái tài khoản'));
                                            } else {
                                                if (0 < $db->num_rows('SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\' AND `type` = \'' . $type . '\' AND `type_category` = \'' . $type_category . '\'')) {
                                                    $query_product = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type_category . '\' AND `type` IN (\'ACCOUNT\',\'RANDOM\')');
                                                    $detail = json_decode($query_product['detail'], true);
                                                    $query = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\'');
                                                    $full_img = '';
                                                    if ($query['type'] == 'ACCOUNT') {
                                                        $fileNames = isset($_FILES['image']['name']) && is_array($_FILES['image']['name'])
                                                            ? $_FILES['image']['name']
                                                            : [];
                                                        $i = 0;
                                                        while ($i < count($fileNames)) {
                                                            array_push($link_anh, upload_multiple_file('image', 'product', $i));
                                                            ++$i;
                                                        }
                                                        $imageUrlsRaw = trim((string) ($_POST['image_urls'] ?? ''));
                                                        $imageUrls = array_values(array_filter(array_map(
                                                            'trim',
                                                            preg_split('/\r\n|\r|\n/', $imageUrlsRaw)
                                                        ), function ($url) {
                                                            return $url !== '';
                                                        }));
                                                        if (count($imageUrls) > 10) {
                                                            exit(JsonMsg('error', 'Mỗi tài khoản chỉ được nhập tối đa 10 URL ảnh'));
                                                        }
                                                        try {
                                                            foreach ($imageUrls as $imageUrl) {
                                                                $link_anh[] = upload_image_from_url($imageUrl, 'product');
                                                            }
                                                        } catch (Throwable $imageError) {
                                                            exit(JsonMsg('error', $imageError->getMessage()));
                                                        }
                                                        $link_anh = array_values(array_filter($link_anh, function ($v) {
                                                            return is_string($v) && $v !== '';
                                                        }));
                                                        if (0 < count($link_anh)) {
                                                            $full_img = json_encode($link_anh);
                                                        } else {
                                                            $full_img = $query['image'];
                                                        }
                                                    }
                                                    $i = 0;
                                                    while ($i < count($detail['data'])) {
                                                        $field = $detail['data'][$i]['name'];
                                                        if ($field === '' || $field === null || !isset($_POST[$field]) || Anti_xss($_POST[$field]) === '') {
                                                            $label = isset($detail['data'][$i]['label']) && $detail['data'][$i]['label'] !== '' ? $detail['data'][$i]['label'] : 'đầy đủ thông tin';
                                                            exit(JsonMsg('error', 'Vui lòng nhập ' . $label));
                                                        }
                                                        if ($field == 'taikhoan' || $field == 'matkhau') {
                                                            $name = encryptData(Anti_xss($_POST[$field]));
                                                        } else {
                                                            $name = Anti_xss($_POST[$field]);
                                                        }
                                                        array_push($arr_data, ['id' => $i, 'label' => $detail['data'][$i]['label'], 'type' => $detail['data'][$i]['type'], 'name' => $field, 'value' => encryptData(Anti_xss($_POST[$field])), 'show' => $detail['data'][$i]['show'], $field => $name]);
                                                        ++$i;
                                                    }
                                                    $json['author'] = 'db.NET';
                                                    $json['data'] = $arr_data;
                                                    $full_detail = addslashes(json_encode($json));
                                                    $db->query('UPDATE `accounts` SET `type` = \'' . $type . '\',`detail` = \'' . $full_detail . '\',`image` = \'' . $full_img . '\',`money` = \'' . $cash . '\',`sale` = \'' . $sale . '\',`status` = \'' . $status . '\',`updated_at` = \'' . $date . '\' WHERE `id` = \'' . $id . '\'');
                                                    insert_log($data_user['id'], 'Chỉnh sửa tài khoản #' . $id . '');
                                                    echo JsonMsg('success', 'Chỉnh sửa thành công tài khoản #' . $id . '');
                                                } else {
                                                    echo JsonMsg('error', 'Không tìm thấy dữ liệu tài khoản');
                                                }
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        case 'delete':
                            $id = Anti_xss($_POST['id']);
                            if (empty($id)) {
                                exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                            } else {
                                $check_account = $db->get_row('SELECT * FROM `accounts` WHERE `id` = ' . $id);
                                if (!$check_account) {
                                    exit(JsonMsg('error', 'Tài khoản không tồn tại'));
                                } else {
                                    $isRemove = $db->remove('accounts', ' `id` = \'' . $id . '\' ');
                                    if ($isRemove) {
                                        insert_log($data_user['id'], 'Thực hiện xóa tài khoản [#' . $check_account['id'] . '] ra khỏi hệ thống');
                                        exit(JsonMsg('success', 'Xóa tài khoản thành công'));
                                    } else {
                                        exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa tài khoản'));
                                    }
                                }
                            }
                        case 'deleteSold':
                            $id = Anti_xss($_POST['id']);
                            if (empty($id)) {
                                exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
                            } else {
                                $check_account = $db->get_row('SELECT * FROM `history_buy` WHERE `id` = ' . $id);
                                if (!$check_account) {
                                    exit(JsonMsg('error', 'Tài khoản không tồn tại'));
                                } else {
                                    $isRemove = $db->remove('history_buy', ' `id` = \'' . $id . '\' ');
                                    if ($isRemove) {
                                        $db->query('DELETE FROM `accounts` WHERE `id` = \'' . $check_account['id_acc'] . '\'');
                                        insert_log($data_user['id'], 'Thực hiện xóa tài khoản đã bán [#' . $check_account['id'] . '] ra khỏi hệ thống');
                                        exit(JsonMsg('success', 'Xóa tài khoản thành công'));
                                    } else {
                                        exit(JsonMsg('error', 'Đã xảy ra lỗi khi xóa tài khoản'));
                                    }
                                }
                            }
                        case 'deleteAllSold':
                            $db->query('DELETE FROM `accounts` WHERE `status` = \'off\'');
                            insert_log($data_user['id'], 'Thực hiện xóa toàn bộ tài khoản đã bán ra khỏi hệ thống');
                            exit(JsonMsg('success', 'Xóa tài khoản thành công'));
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
