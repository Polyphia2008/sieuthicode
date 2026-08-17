<?php
/**
 * Create/update account subcategories and their data-field schema.
 *
 * The old decompiled code started every data-field loop at index 2. New forms
 * submit fields at indexes 0 and 1, so "Tài khoản" and "Mật khẩu" were silently
 * discarded. RANDOM imports then stored accounts with an empty data array and
 * customers saw no credentials after buying. Keep all submitted fields and
 * assign stable zero-based ids here.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if (!function_exists('subcategory_build_data_fields')) {
    function subcategory_build_data_fields()
    {
        $names = $_POST['data_name'] ?? [];
        $types = $_POST['data_type'] ?? [];
        $values = $_POST['data_value'] ?? [];
        $shows = $_POST['data_show'] ?? [];

        if (!is_array($names) || !is_array($types) || !is_array($values) || !is_array($shows)) {
            return [[], 'Dữ liệu trường tài khoản không hợp lệ'];
        }

        $allowedTypes = ['input', 'select', 'number', 'password'];
        $allowedShows = ['on', 'off'];
        $fields = [];
        $usedNames = [];

        foreach ($names as $index => $rawLabel) {
            $label = trim((string) $rawLabel);
            $type = (string) ($types[$index] ?? 'input');
            $value = trim((string) ($values[$index] ?? ''));
            $show = (string) ($shows[$index] ?? 'off');

            // Completely empty extra rows are ignored. Partially filled rows are
            // rejected so the administrator can see what must be corrected.
            if ($label === '' && $value === '') {
                continue;
            }
            if ($label === '') {
                return [[], 'Vui lòng nhập Tên hiển thị cho tất cả trường dữ liệu'];
            }
            if (!in_array($type, $allowedTypes, true)) {
                return [[], 'Kiểu dữ liệu không hợp lệ tại trường ' . $label];
            }
            if (!in_array($show, $allowedShows, true)) {
                $show = 'off';
            }

            $name = toslug($label);
            if ($name === '') {
                return [[], 'Không thể tạo tên dữ liệu từ nhãn ' . $label];
            }
            if (isset($usedNames[$name])) {
                return [[], 'Tên hiển thị bị trùng: ' . $label];
            }
            $usedNames[$name] = true;

            if ($type === 'select' && $value === '') {
                return [[], 'Trường chọn ' . $label . ' cần có các lựa chọn, phân cách bằng dấu |'];
            }

            $fields[] = [
                'id' => count($fields),
                'label' => Anti_xss($label),
                'type' => $type,
                'name' => $name,
                'value' => Anti_xss($value),
                'show' => $show,
            ];
        }

        if (count($fields) < 1) {
            return [[], 'Vui lòng cấu hình ít nhất một trường dữ liệu (ví dụ: Tài khoản)'];
        }

        return [$fields, ''];
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(JsonMsg('error', 'Phương thức không hợp lệ'));
}
if (!$user) {
    exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
}
if (!is_admin_account($data_user)) {
    exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
}
if ($db->site('status_demo') != 0) {
    exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
}

$action = (string) ($_POST['action'] ?? '');
if (!in_array($action, ['add', 'update'], true)) {
    exit(JsonMsg('error', 'Vui lòng chọn dữ liệu'));
}

[$arrData, $fieldError] = subcategory_build_data_fields();
if ($fieldError !== '') {
    exit(JsonMsg('error', $fieldError));
}

$stt = (int) ($_POST['stt'] ?? 0);
$category = (int) ($_POST['category'] ?? 0);
$nameProduct = trim((string) ($_POST['name_product'] ?? ''));
$thele = (string) ($_POST['thele'] ?? '');
$tag = Anti_xss((string) ($_POST['tag'] ?? ''));
$fake = max(0, (int) ($_POST['fake'] ?? 0));

if ($stt < 1) {
    exit(JsonMsg('error', 'Vui lòng nhập vị trí hiển thị lớn hơn 0'));
}
if ($nameProduct === '') {
    exit(JsonMsg('error', 'Vui lòng nhập tên sản phẩm'));
}
if ($category < 1 || !$db->get_row("SELECT `id` FROM `categories` WHERE `id` = '" . $category . "' LIMIT 1")) {
    exit(JsonMsg('error', 'Chuyên mục chính không hợp lệ'));
}

if ($action === 'add') {
    $type = strtoupper((string) ($_POST['type'] ?? ''));
    if (!in_array($type, ['ACCOUNT', 'RANDOM'], true)) {
        exit(JsonMsg('error', 'Vui lòng chọn loại tài khoản'));
    }

    $typeCategory = create_slug($nameProduct);
    if ($typeCategory === '') {
        exit(JsonMsg('error', 'Tên sản phẩm không thể tạo đường dẫn hợp lệ'));
    }
    if ($db->num_rows(
        "SELECT `id` FROM `subcategory` WHERE `type_category` = '" . $db->escape($typeCategory) . "'"
    ) > 0) {
        exit(JsonMsg('error', 'Sản phẩm này đã tồn tại trên hệ thống'));
    }

    $thumbUrl = trim((string) ($_POST['thumb_url'] ?? ''));
    try {
        $thumb = $thumbUrl !== ''
            ? upload_image_from_url($thumbUrl, 'product')
            : upload_file('thumb', 'product');
    } catch (Throwable $imageError) {
        exit(JsonMsg('error', $imageError->getMessage()));
    }
    if (!is_string($thumb) || $thumb === '') {
        exit(JsonMsg('error', 'Vui lòng tải ảnh thumb hoặc nhập URL ảnh hợp lệ'));
    }

    $json = [
        'author' => 'SIEUTHICODE.NET',
        'name_product' => Anti_xss($nameProduct),
        'thumb' => $thumb,
        'tag' => $tag,
        'thele' => $thele,
        'data' => $arrData,
    ];

    if ($type === 'RANDOM') {
        // RANDOM có thể miễn phí (0đ) hoặc mất phí. Chỉ từ chối giá âm.
        $cash = (int) ($_POST['cash'] ?? 0);
        if ($cash < 0) {
            exit(JsonMsg('error', 'Giá tiền bán random không được là số âm'));
        }
        $json['cash'] = $cash;
    }

    $fullJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($fullJson === false) {
        exit(JsonMsg('error', 'Không thể tạo cấu hình danh mục'));
    }

    $inserted = $db->insert('subcategory', [
        'stt' => $stt,
        'category' => $category,
        'type' => $type,
        'type_category' => $typeCategory,
        'detail' => $fullJson,
        'fake' => $fake,
        'status' => 1,
    ]);
    if (!$inserted) {
        exit(JsonMsg('error', 'Không thể thêm danh mục'));
    }

    insert_log($data_user['id'], 'Thêm ngăn tài khoản game ' . $nameProduct);
    exit(JsonMsg('success', 'Thêm thành công danh mục ' . $nameProduct));
}

$id = (int) ($_POST['id'] ?? 0);
$subcategory = $db->get_row(
    "SELECT * FROM `subcategory` WHERE `id` = '" . $id . "'"
    . " AND `type` IN ('ACCOUNT','RANDOM') LIMIT 1"
);
if (!$subcategory) {
    exit(JsonMsg('error', 'Không tìm thấy sản phẩm để chỉnh sửa'));
}

$currentDetail = json_decode((string) $subcategory['detail'], true);
if (!is_array($currentDetail)) {
    $currentDetail = [];
}

$thumbUrl = trim((string) ($_POST['thumb_url'] ?? ''));
try {
    $thumb = $thumbUrl !== ''
        ? upload_image_from_url($thumbUrl, 'product')
        : update_file('thumb', (string) ($currentDetail['thumb'] ?? ''), 'product');
} catch (Throwable $imageError) {
    exit(JsonMsg('error', $imageError->getMessage()));
}
if (!is_string($thumb) || $thumb === '') {
    $thumb = (string) ($currentDetail['thumb'] ?? '');
}
if ($thumb === '') {
    exit(JsonMsg('error', 'Vui lòng tải ảnh thumb hoặc nhập URL ảnh hợp lệ'));
}

$json = [
    'author' => 'SIEUTHICODE.NET',
    'name_product' => Anti_xss($nameProduct),
    'thumb' => $thumb,
    'tag' => $tag,
    'thele' => $thele,
    'data' => $arrData,
];

if ((string) $subcategory['type'] === 'RANDOM') {
    // Giữ hỗ trợ RANDOM miễn phí khi cập nhật danh mục.
    $price = (int) ($_POST['price'] ?? 0);
    if ($price < 0) {
        exit(JsonMsg('error', 'Giá tiền bán random không được là số âm'));
    }
    $json['cash'] = $price;
}

$fullJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($fullJson === false) {
    exit(JsonMsg('error', 'Không thể tạo cấu hình danh mục'));
}

$display = (int) ($_POST['status'] ?? 0) === 1 ? 1 : 0;
$updated = $db->update('subcategory', [
    'category' => $category,
    'stt' => $stt,
    'detail' => $fullJson,
    'fake' => $fake,
    'status' => $display,
], "`id` = '" . $id . "'");

if (!$updated) {
    exit(JsonMsg('error', 'Không thể cập nhật danh mục'));
}

insert_log(
    $data_user['id'],
    'Chỉnh sửa danh mục ' . (string) ($currentDetail['name_product'] ?? $nameProduct)
);
exit(JsonMsg('success', 'Chỉnh sửa thành công danh mục ' . $nameProduct));
