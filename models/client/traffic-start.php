<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';require_once APP_ROOT.'/libs/traffic.php';
if(!$user){new Redirect('/login');exit;}$id=(int)($_GET['id']??0);$task=$db->get_row("SELECT * FROM `traffic_tasks` WHERE `id`='".$id."' AND `status`=1 LIMIT 1");if(!$task){new Redirect('/kiem-tien-online');exit;}
$existing=$db->get_row("SELECT * FROM `traffic_submissions` WHERE `task_id`='".$id."' AND `user_id`='".(int)$data_user['id']."' LIMIT 1");if($existing&&$existing['status']==='approved'){new Redirect('/kiem-tien-online');exit;}
$short=trim((string)$task['short_url']);if($short===''){[$ok,$result]=traffic_shorten($task['provider'],$task['destination_url'],$task['backup_url']);if(!$ok){$_SESSION['traffic_error']=$result;new Redirect('/kiem-tien-online');exit;}$short=$result;$db->update('traffic_tasks',['short_url'=>$short,'updated_at'=>date('Y-m-d H:i:s')],"`id`='".$id."'");}
if(!$existing)$db->insert('traffic_submissions',['task_id'=>$id,'user_id'=>$data_user['id'],'status'=>'started','reward'=>$task['reward'],'started_at'=>date('Y-m-d H:i:s')]);
header('Location: '.$short,true,302);exit;
