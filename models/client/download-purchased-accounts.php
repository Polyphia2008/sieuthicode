<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user || empty($data_user['id'])) { http_response_code(401); exit('Unauthorized'); }
$rows = $db->get_list(
    "SELECT * FROM `history_buy` WHERE `username`='".$db->escape($data_user['username'])."' ORDER BY `id` ASC"
);
$lines = [];
$lines[] = 'TÀI KHOẢN ĐÃ MUA - '.date('d/m/Y H:i:s');
$lines[] = str_repeat('=', 60);
foreach ($rows as $row) {
    $detail = json_decode((string)$row['detail'], true);
    $fields = is_array($detail) && isset($detail['data']) && is_array($detail['data']) ? $detail['data'] : [];
    $lines[] = '';
    $lines[] = 'Mã giao dịch: '.(string)$row['trans_id'];
    $lines[] = 'Danh mục: '.(string)$row['type_category'];
    $lines[] = 'Ngày mua: '.date('d/m/Y H:i:s',(int)$row['created_at']);
    foreach ($fields as $field) {
        $label = trim((string)($field['label'] ?? 'Thông tin'));
        $value = account_field_display($field);
        $lines[] = $label.': '.$value;
    }
    $lines[] = str_repeat('-', 60);
}
$content = "\xEF\xBB\xBF".implode("\r\n", $lines)."\r\n";
$filename = date('j-n-Y').'.txt';
header('Content-Type: text/plain; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Length: '.strlen($content));
header('Cache-Control: no-store');
echo $content;
exit;
