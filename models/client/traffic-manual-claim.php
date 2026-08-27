<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';require_once APP_ROOT.'/libs/traffic.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit(JsonMsg('error','Method Not Allowed'));}if(!$user)exit(JsonMsg('error','Vui lòng đăng nhập'));
$posted=(string)($_POST['csrf_token']??'');if(!hash_equals((string)($_SESSION['csrf_token']??''),$posted)){header('HTTP/1.1 419 Authentication Timeout',true,419);exit(JsonMsg('error','Invalid CSRF'));}
$taskId=(int)($_POST['task_id']??0);$task=$db->get_row("SELECT * FROM traffic_manual_tasks WHERE id='".$taskId."' AND status=1 LIMIT 1");if(!$task)exit(JsonMsg('error','Nhiệm vụ không tồn tại'));
$userId=(int)$data_user['id'];$claim=$db->get_row("SELECT * FROM traffic_manual_claims WHERE task_id='".$taskId."' AND user_id='".$userId."' LIMIT 1");
if(!$claim)$db->insert('traffic_manual_claims',['task_id'=>$taskId,'user_id'=>$userId,'status'=>'pending','reward'=>(int)$task['reward'],'created_at'=>date('Y-m-d H:i:s')]);
elseif($claim['status']==='rejected')$db->update('traffic_manual_claims',['status'=>'pending','reward'=>(int)$task['reward'],'admin_note'=>'','created_at'=>date('Y-m-d H:i:s'),'reviewed_at'=>null],"id='".(int)$claim['id']."'");
header('Content-Type: application/json; charset=utf-8');echo json_encode(['status'=>'success','msg'=>'Đã ghi nhận nhận nhiệm vụ','join_url'=>$task['join_url']],JSON_UNESCAPED_UNICODE);exit;
