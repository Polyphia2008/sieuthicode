<?php
/**
 * DataTables source for the admin account list.
 *
 * This endpoint must always return a valid DataTables JSON document. A single
 * malformed/legacy account row must not turn the whole request into HTTP 500
 * (which DataTables only reports as the unhelpful "Ajax error").
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

header('Content-Type: application/json; charset=utf-8');

$draw = max(0, (int) ($_POST['draw'] ?? 0));

if (!function_exists('admin_account_datatable_exit')) {
    function admin_account_datatable_exit($draw, $total, $filtered, array $data, $error = '')
    {
        $output = [
            'draw' => (int) $draw,
            'recordsTotal' => max(0, (int) $total),
            'recordsFiltered' => max(0, (int) $filtered),
            'data' => $data,
        ];

        if ($error !== '') {
            $output['error'] = (string) $error;
        }

        $json = json_encode(
            $output,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
        );

        if ($json === false) {
            $json = '{"draw":' . (int) $draw
                . ',"recordsTotal":0,"recordsFiltered":0,"data":[],'
                . '"error":"Không thể tạo dữ liệu danh sách tài khoản"}';
        }

        exit($json);
    }
}

if (!$user) {
    admin_account_datatable_exit($draw, 0, 0, [], 'Phiên đăng nhập đã hết hạn');
}

if (!is_admin_account($data_user)) {
    admin_account_datatable_exit($draw, 0, 0, [], 'Bạn không có quyền truy cập trang này');
}

try {
    $start = max(0, (int) ($_POST['start'] ?? 0));
    $length = (int) ($_POST['length'] ?? 10);
    if ($length < 1) {
        $length = 10;
    }
    $length = min($length, 100);

    $type = trim((string) ($_POST['type'] ?? ''));
    $username = trim((string) ($_POST['username'] ?? ''));

    if ($type === '') {
        admin_account_datatable_exit($draw, 0, 0, []);
    }

    $escapedType = $db->escape($type);
    $where = ["`type_category` = '" . $escapedType . "'"];

    if ($username !== '') {
        $where[] = "`username_post` = '" . $db->escape($username) . "'";
    }

    $whereSql = implode(' AND ', $where);

    // Map every visible DataTables column to a safe database column. Checkbox,
    // image and action columns deliberately fall back to id.
    $orderColumns = [
        0 => 'id',
        1 => 'id',
        2 => 'id',
        3 => 'username_post',
        4 => 'id',
        5 => 'money',
        6 => 'sale',
        7 => 'status',
        8 => 'id',
        9 => 'id',
        10 => 'created_at',
    ];

    $orderIndex = isset($_POST['order'][0]['column'])
        ? (int) $_POST['order'][0]['column']
        : 1;
    $orderColumn = $orderColumns[$orderIndex] ?? 'id';
    $orderDirection = strtoupper((string) ($_POST['order'][0]['dir'] ?? 'DESC'));
    if (!in_array($orderDirection, ['ASC', 'DESC'], true)) {
        $orderDirection = 'DESC';
    }

    $product = $db->get_row(
        "SELECT `id`, `type`, `detail` FROM `subcategory`"
        . " WHERE `type_category` = '" . $escapedType . "' LIMIT 1"
    );
    $productDetail = $product ? json_decode((string) $product['detail'], true) : [];
    if (!is_array($productDetail)) {
        $productDetail = [];
    }
    $productThumb = isset($productDetail['thumb']) && is_string($productDetail['thumb'])
        ? $productDetail['thumb']
        : '';

    $total = $db->num_rows(
        "SELECT `id` FROM `accounts` WHERE `type_category` = '" . $escapedType . "'"
    );
    $filtered = $db->num_rows("SELECT `id` FROM `accounts` WHERE " . $whereSql);

    $rows = $db->get_list(
        "SELECT * FROM `accounts` WHERE " . $whereSql
        . " ORDER BY `" . $orderColumn . "` " . $orderDirection
        . " LIMIT " . $start . ", " . $length
    );

    $data = [];
    foreach ($rows as $info) {
        try {
            $accountId = (int) ($info['id'] ?? 0);
            $accountType = (string) ($info['type'] ?? '');

            $accountDetail = json_decode((string) ($info['detail'] ?? ''), true);
            $detailRows = is_array($accountDetail) && isset($accountDetail['data'])
                && is_array($accountDetail['data'])
                ? $accountDetail['data']
                : [];

            $accountName = 'Không có dữ liệu';
            $encryptedAccount = isset($detailRows[0]['value'])
                ? (string) $detailRows[0]['value']
                : '';
            if ($encryptedAccount !== '') {
                try {
                    $decrypted = decodecryptData($encryptedAccount);
                    if (is_string($decrypted) && $decrypted !== '') {
                        $accountName = $decrypted;
                    } else {
                        $accountName = 'Không thể giải mã';
                    }
                } catch (Throwable $decryptError) {
                    // Legacy rows can have been encrypted with a key that is no
                    // longer present. Keep the list usable instead of returning 500.
                    error_log('Account #' . $accountId . ' decrypt failed: ' . $decryptError->getMessage());
                    $accountName = 'Không thể giải mã';
                }
            }

            $images = json_decode((string) ($info['image'] ?? ''), true);
            $imagePath = '';
            if ($accountType === 'ACCOUNT' && is_array($images) && isset($images[0])) {
                $imagePath = (string) $images[0];
            } elseif ($productThumb !== '') {
                $imagePath = $productThumb;
            }

            $imageHtml = $imagePath !== ''
                ? '<img width="100" alt="Account" src="'
                    . htmlspecialchars(rtrim((string) DOMAIN, '/') . '/' . ltrim($imagePath, '/'), ENT_QUOTES, 'UTF-8')
                    . '">'
                : '<span class="text-muted">Không có ảnh</span>';

            $statusMap = [
                'on' => 'Chưa bán',
                'off' => 'Đã bán',
            ];
            $status = $statusMap[(string) ($info['status'] ?? '')] ?? 'Không xác định';

            $createdAt = (int) ($info['created_at'] ?? 0);
            $createdText = $createdAt > 0 ? date('H:i d-m-Y', $createdAt) : '-';

            $data[] = [
                '<input type="checkbox" data-id="' . $accountId . '" name="checkbox_accounts"'
                    . ' class="form-check-input" value="' . $accountId . '" />',
                $accountId,
                $imageHtml,
                htmlspecialchars((string) ($info['username_post'] ?? ''), ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($accountName, ENT_QUOTES, 'UTF-8'),
                number_format((int) ($info['money'] ?? 0)),
                (int) ($info['sale'] ?? 0) . '%',
                $status,
                '<a class="btn btn-info btn-sm" href="/cpanel/account/edit/' . $accountId
                    . '" target="_blank" rel="noopener"><i class="fa fa-pen"></i></a>',
                '<button type="button" class="btn btn-danger btn-sm" onclick="confirmAction('
                    . $accountId . ')"><i class="fa fa-trash"></i></button>',
                $createdText,
            ];
        } catch (Throwable $rowError) {
            // Do not let one corrupt legacy row break the complete DataTable.
            error_log(
                'Admin account list skipped row #' . (int) ($info['id'] ?? 0)
                . ': ' . $rowError->getMessage()
            );
        }
    }

    admin_account_datatable_exit($draw, $total, $filtered, $data);
} catch (Throwable $error) {
    error_log('Admin account DataTable failed: ' . $error->getMessage());
    admin_account_datatable_exit(
        $draw,
        0,
        0,
        [],
        'Không thể tải danh sách tài khoản. Vui lòng kiểm tra error_log.'
    );
}
