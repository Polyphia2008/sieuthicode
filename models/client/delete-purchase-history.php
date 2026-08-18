<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit(JsonMsg('error','Method Not Allowed')); }
if (!$user || empty($data_user['id'])) { http_response_code(401); exit(JsonMsg('error','Vui lòng đăng nhập')); }
$posted=(string)($_POST['csrf_token']??''); $session=(string)($_SESSION['csrf_token']??'');
if ($posted===''||$session===''||!hash_equals($session,$posted)) { header('HTTP/1.1 419 Authentication Timeout',true,419); exit(JsonMsg('error','Invalid CSRF Protection Token')); }
$historyRows=$db->get_list("SELECT `id`,`id_acc` FROM `history_buy` WHERE `username`='".$db->escape($data_user['username'])."'");
$ids=array_map(function($r){return (int)$r['id'];},$historyRows);
$accountIds=array_values(array_filter(array_map(function($r){return (int)$r['id_acc'];},$historyRows)));
$db->query('START TRANSACTION');
$ok=true;
if($ids){$list=implode(',',$ids);$ok=$db->query("DELETE FROM `reviews` WHERE `type`='account' AND `history_id` IN (".$list.")")!==false;}
if($accountIds){$ok=$db->query("DELETE FROM `accounts` WHERE `id` IN (".implode(',',$accountIds).")")!==false&&$ok;}
$ok=$db->query("DELETE FROM `history_buy` WHERE `username`='".$db->escape($data_user['username'])."'")!==false&&$ok;
if(!$ok){$db->query('ROLLBACK');exit(JsonMsg('error','Không thể xóa lịch sử mua hàng'));}
$db->query('COMMIT');
exit(JsonMsg('success','Đã xóa lịch sử mua tài khoản'));
