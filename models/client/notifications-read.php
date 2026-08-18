<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit(JsonMsg('error','Method Not Allowed')); }
if (!$user || empty($data_user['id'])) { http_response_code(401); exit(JsonMsg('error','Vui lòng đăng nhập')); }
$posted=(string)($_POST['csrf_token']??'');$session=(string)($_SESSION['csrf_token']??'');
if($posted===''||$session===''||!hash_equals($session,$posted)){header('HTTP/1.1 419 Authentication Timeout',true,419);exit(JsonMsg('error','Invalid CSRF Protection Token'));}
$userId=(int)$data_user['id'];$now=date('Y-m-d H:i:s');
$ok=$db->query(
    "INSERT IGNORE INTO `user_notification_reads` (`notification_id`,`user_id`,`read_at`)"
    . " SELECT `id`,'".$userId."','".$now."' FROM `user_notifications`"
    . " WHERE `user_id`=0 OR `user_id`='".$userId."'"
);
if($ok===false) exit(JsonMsg('error','Không thể cập nhật trạng thái thông báo'));
exit(JsonMsg('success','Đã đọc thông báo'));
