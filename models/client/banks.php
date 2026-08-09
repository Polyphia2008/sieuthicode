<?php
// statically decompiled from banks.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
header('Content-Type: application/json');
$banks = [];
$userId = $user ? $data_user['id'] : '';
foreach ($db->get_list('SELECT * FROM `bank` ORDER BY `id` ASC') as $bank) {
    $banks[] = ['id' => $bank['id'], 'account_number' => $bank['accountNumber'], 'account_name' => $bank['accountName'], 'bank_name' => $bank['short_name'], 'transfer_content' => $db->site('prefix_autobank') . $userId, 'qr_code' => qr_bank($bank['short_name'], $bank['accountNumber'], $bank['accountName'], 0, $db->site('prefix_autobank') . ($user ? $data_user['id'] : 0)), 'logo' => convertBankImage($bank['short_name']), 'content' => $db->site('bank_notice')];
}
echo json_encode($banks);
