<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';
require_once APP_ROOT.'/libs/traffic.php';
function traffic_verify_redirect($message,$success=false){$_SESSION[$success?'traffic_success':'traffic_error']=$message;header('Location: /kiem-tien-online?traffic='.($success?'success':'error'),true,302);exit;}
if(!$user)traffic_verify_redirect('Phiên đăng nhập đã hết hạn, không thể xác thực nhiệm vụ');
$raw=(string)($_GET['token']??'');
if(!preg_match('/^[a-f0-9]{64}$/',$raw))traffic_verify_redirect('Mã xác thực nhiệm vụ không hợp lệ');
$hash=hash('sha256',$raw);$db->query('START TRANSACTION');
$attempt=$db->get_row("SELECT * FROM `traffic_attempts` WHERE `token_hash`='".$hash."' FOR UPDATE");
if(!$attempt){$db->query('ROLLBACK');traffic_verify_redirect('Mã xác thực không tồn tại');}
if($attempt['status']==='completed'){$db->query('ROLLBACK');traffic_verify_redirect('Nhiệm vụ này đã được nhận thưởng trước đó');}
if($attempt['status']!=='issued'||strtotime($attempt['expires_at'])<time()){$db->update('traffic_attempts',['status'=>'expired'],"id='".(int)$attempt['id']."'");$db->query('COMMIT');traffic_verify_redirect('Mã xác thực đã hết hạn');}
if((int)$attempt['user_id']!==(int)$data_user['id']){$db->query('ROLLBACK');traffic_verify_redirect('Mã xác thực không thuộc tài khoản này');}
$binding=traffic_client_binding();
if(!hash_equals($attempt['session_hash'],$binding['session_hash'])||!hash_equals($attempt['device_hash'],$binding['device_hash'])||!hash_equals($attempt['ip_hash'],$binding['ip_hash'])){$db->query('ROLLBACK');traffic_verify_redirect('Thiết bị, trình duyệt hoặc địa chỉ mạng đã thay đổi. Không thể xác thực');}
$task=$db->get_row("SELECT * FROM `traffic_tasks` WHERE `id`='".(int)$attempt['task_id']."' LIMIT 1");
if(!$task){$db->query('ROLLBACK');traffic_verify_redirect('Nhiệm vụ không còn tồn tại');}
$reward=traffic_effective_reward($task,time());
$updated=$db->update('traffic_attempts',['status'=>'completed','reward'=>$reward,'completed_at'=>date('Y-m-d H:i:s')],"`id`='".(int)$attempt['id']."' AND `status`='issued'");
if(!$updated||$db->affected_rows()!==1){$db->query('ROLLBACK');traffic_verify_redirect('Nhiệm vụ đã được xử lý');}
$credited=PlusCredits((int)$attempt['user_id'],$reward,'Thưởng hoàn thành nhiệm vụ Traffic #'.$attempt['task_id'],'TRAFFIC_AUTO_'.$attempt['id']);
if(!$credited){$db->query('ROLLBACK');traffic_verify_redirect('Không thể cộng tiền thưởng');}
$db->query('COMMIT');
$message='Hoàn thành nhiệm vụ! Đã cộng '.format_cash($reward).'đ vào số dư';
if(traffic_bonus_is_active($task))$message.=' (đã gồm thưởng khung giờ vàng)';
traffic_verify_redirect($message,true);
