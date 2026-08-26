<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';
require_once APP_ROOT.'/libs/traffic.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method Not Allowed');}
if(!$user){new Redirect('/login');exit;}
$posted=(string)($_POST['csrf_token']??'');$session=(string)($_SESSION['csrf_token']??'');
if($posted===''||$session===''||!hash_equals($session,$posted)){header('HTTP/1.1 419 Authentication Timeout',true,419);exit('Invalid CSRF');}
$id=(int)($_POST['task_id']??0);$userId=(int)$data_user['id'];
$db->query('START TRANSACTION');
$task=$db->get_row("SELECT * FROM `traffic_tasks` WHERE `id`='".$id."' AND `status`=1 FOR UPDATE");
if(!$task){$db->query('ROLLBACK');$_SESSION['traffic_error']='Nhiệm vụ không tồn tại';new Redirect('/kiem-tien-online');exit;}
$db->query("UPDATE `traffic_attempts` SET `status`='expired' WHERE `status`='issued' AND `expires_at`<NOW()");
$today=$db->get_row("SELECT COUNT(*) AS total FROM `traffic_attempts` WHERE `task_id`='".$id."' AND `user_id`='".$userId."' AND DATE(`created_at`)=CURDATE() AND `status` IN ('issued','completed')");
if((int)($today['total']??0)>=(int)$task['max_per_day']){$db->query('ROLLBACK');$_SESSION['traffic_error']='Bạn đã hết lượt nhận nhiệm vụ này trong hôm nay';new Redirect('/kiem-tien-online');exit;}
$rawToken=bin2hex(random_bytes(32));$binding=traffic_client_binding();$expires=date('Y-m-d H:i:s',time()+1800);
$ok=$db->insert('traffic_attempts',['task_id'=>$id,'user_id'=>$userId,'token_hash'=>hash('sha256',$rawToken),'session_hash'=>$binding['session_hash'],'device_hash'=>$binding['device_hash'],'ip_hash'=>$binding['ip_hash'],'provider'=>'','short_url'=>'','status'=>'issued','reward'=>(int)$task['reward'],'expires_at'=>$expires,'created_at'=>date('Y-m-d H:i:s')]);
$attemptId=(int)$db->get_id_insert();if(!$ok||$attemptId<=0){$db->query('ROLLBACK');$_SESSION['traffic_error']='Không thể tạo phiên nhiệm vụ';new Redirect('/kiem-tien-online');exit;}$db->query('COMMIT');
$callback=rtrim((string)DOMAIN,'/').'/traffic/verify?token='.rawurlencode($rawToken);
$configuredProvider=(string)($task['provider']??'auto');
if($configuredProvider==='auto')[$shortOk,$shortUrl,$provider]=traffic_auto_shorten($callback);
else{[$shortOk,$shortUrl]=traffic_shorten($configuredProvider,$callback,$callback);$provider=$configuredProvider;}
if(!$shortOk){$db->update('traffic_attempts',['status'=>'failed'],"`id`='".$attemptId."'");$_SESSION['traffic_error']='Không thể lấy link nhiệm vụ: '.$shortUrl;new Redirect('/kiem-tien-online');exit;}
$db->update('traffic_attempts',['short_url'=>$shortUrl,'provider'=>$provider],"`id`='".$attemptId."'");
header('Cache-Control: no-store');header('Location: '.$shortUrl,true,302);exit;
