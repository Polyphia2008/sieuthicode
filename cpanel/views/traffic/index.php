<?php
$title='Quản lý nhiệm vụ Traffic';
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/cpanel/views/sidebar.php';
require_once APP_ROOT.'/libs/traffic.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf_token();$action=(string)($_POST['action']??'');
 if($action==='provider'){
  require_superadmin(false);$provider=(string)($_POST['provider']??'');$token=trim((string)($_POST['api_token']??''));
  if(!in_array($provider,['link4m','layma','link2m'],true)||$token==='')exit('<script>alert("Provider/token không hợp lệ");history.back();</script>');
  $enc=encryptData($token);$exists=$db->get_row("SELECT id FROM traffic_providers WHERE provider='".$provider."'");
  if($exists)$db->update('traffic_providers',['api_token'=>$enc,'active'=>1,'updated_at'=>date('Y-m-d H:i:s')],"id='".(int)$exists['id']."'");
  else $db->insert('traffic_providers',['provider'=>$provider,'api_token'=>$enc,'active'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
  exit('<script>alert("Đã lưu token an toàn");location.href="/cpanel/traffic";</script>');
 }
 if($action==='task'){
  $taskTitle=trim((string)($_POST['title']??''));$reward=max(0,(int)($_POST['reward']??0));$max=max(1,(int)($_POST['max_per_day']??1));
  if($taskTitle==='')exit('<script>alert("Vui lòng nhập tên nhiệm vụ");history.back();</script>');
  $db->insert('traffic_tasks',['title'=>$taskTitle,'keyword'=>'','destination_url'=>'','backup_url'=>'','provider'=>'auto','reward'=>$reward,'max_per_day'=>$max,'wait_seconds'=>10,'instructions'=>'','status'=>1,'created_by'=>$data_user['id'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
  exit('<script>alert("Đã tạo nhiệm vụ");location.href="/cpanel/traffic";</script>');
 }
 if($action==='toggle'){$id=(int)($_POST['task_id']??0);$db->query("UPDATE traffic_tasks SET status=IF(status=1,0,1),updated_at=NOW() WHERE id='".$id."'");exit('<script>location.href="/cpanel/traffic";</script>');}
}
$tasks=$db->get_list("SELECT t.*,(SELECT COUNT(*) FROM traffic_attempts a WHERE a.task_id=t.id AND DATE(a.created_at)=CURDATE() AND a.status IN ('issued','completed')) AS used_today,(SELECT COUNT(*) FROM traffic_attempts a WHERE a.task_id=t.id AND a.status='completed') AS completed_total FROM traffic_tasks t ORDER BY t.id DESC LIMIT 100");
?>
<main id="main-container"><div class="content"><h2>Nhiệm vụ Traffic tự động</h2>
<?php if(is_superadmin_account($data_user)):?><div class="block block-rounded"><div class="block-header"><h3 class="block-title">API Providers</h3></div><div class="block-content"><p class="text-muted">Token chỉ lưu mã hóa. Hệ thống tự chọn provider đang hoạt động khi user nhận nhiệm vụ.</p><form method="post" class="row g-2"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="action" value="provider"><div class="col-md-3"><select name="provider" class="form-control"><option>link4m</option><option>layma</option><option>link2m</option></select></div><div class="col-md-7"><input type="password" name="api_token" class="form-control" placeholder="API token mới" required></div><div class="col-md-2"><button class="btn btn-primary">Lưu token</button></div></form></div></div><?php endif;?>
<div class="block block-rounded"><div class="block-header"><h3 class="block-title">Tạo nhiệm vụ</h3></div><div class="block-content"><form method="post" class="row g-2"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="action" value="task"><div class="col-md-5"><label>Tên nhiệm vụ</label><input class="form-control" name="title" required></div><div class="col-md-3"><label>Số lượt tối đa/account/ngày</label><input class="form-control" type="number" min="1" name="max_per_day" value="1"></div><div class="col-md-3"><label>Tiền thưởng mỗi lượt</label><input class="form-control" type="number" min="0" name="reward" value="0"></div><div class="col-md-1 d-flex align-items-end"><button class="btn btn-success">Tạo</button></div></form></div></div>
<div class="block block-rounded"><div class="block-header"><h3 class="block-title">Danh sách nhiệm vụ</h3></div><div class="block-content table-responsive"><table class="table"><tr><th>ID</th><th>Tên</th><th>Giới hạn/ngày</th><th>Thưởng</th><th>Đã cấp hôm nay</th><th>Hoàn thành</th><th>Trạng thái</th></tr><?php foreach($tasks as $t):?><tr><td><?=$t['id']?></td><td><?=htmlspecialchars($t['title'],ENT_QUOTES)?></td><td><?=(int)$t['max_per_day']?></td><td><?=format_cash($t['reward'])?>đ</td><td><?=(int)$t['used_today']?></td><td><?=(int)$t['completed_total']?></td><td><form method="post"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="task_id" value="<?=$t['id']?>"><button class="btn btn-sm <?=$t['status']?'btn-success':'btn-secondary'?>"><?=$t['status']?'Đang bật':'Đã tắt'?></button></form></td></tr><?php endforeach;?></table></div></div>
</div></main><?php require_once realpath($_SERVER['DOCUMENT_ROOT']).'/cpanel/views/footer.php';?>
