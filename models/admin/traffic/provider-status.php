<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';
if(!$user||!is_admin_account($data_user)){http_response_code(403);exit(json_encode(['status'=>'error']));}
header('Content-Type: application/json; charset=utf-8');
$provider=(string)($_GET['provider']??'');
if(!in_array($provider,['link4m','layma','link2m'],true))exit(json_encode(['status'=>'error','configured'=>false]));
$row=$db->get_row("SELECT `id`,`active` FROM `traffic_providers` WHERE `provider`='".$provider."' LIMIT 1");
echo json_encode(['status'=>'success','configured'=>(bool)($row&&$row['active']),'can_configure'=>is_superadmin_account($data_user)]);exit;
