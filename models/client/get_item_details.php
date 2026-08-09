<?php
// statically decompiled from get_item_details.php  [structured; all 3 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
header('Content-Type: application/json');
if (!isset($_GET['type'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
    exit();
} else {
    $type = Anti_xss($_GET['type']);
    $query = $db->get_row('SELECT * FROM `units` WHERE `id` = \'' . $type . '\' AND `status` = 1');
    if (!$query) {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy vật phẩm']);
        exit();
    } else {
        $detail_query = json_decode($query['detail'], true);
        $packages = $db->get_list('SELECT * FROM `package_units` WHERE `unit_id` = \'' . $type . '\' AND `status` = 1 ORDER BY `stt` ASC');
        $response = ['status' => 'success', 'message' => 'Lấy dữ liệu thành công', 'packages' => array_map(function ($pkg) {
    return ['id' => $pkg['id'], 'name' => $pkg['name']];
}, $packages), 'fields' => isset($detail_query['data']) ? array_map(function ($field) {
    return ['id' => $field['id'], 'label' => htmlspecialchars($field['label']), 'type' => $field['type'], 'options' => isset($field['option']) ? explode(',', $field['option']) : []];
}, $detail_query['data']) : []];
        echo json_encode($response);
    }
}
