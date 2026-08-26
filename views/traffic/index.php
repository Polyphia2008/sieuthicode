<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';if(!$user){new Redirect('/login');exit;}
$title='Nhiệm vụ Traffic | '.$db->site('title');require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/header.php');
$tasks=$db->get_list("SELECT t.*,(SELECT COUNT(*) FROM `traffic_attempts` a WHERE a.`task_id`=t.`id` AND a.`user_id`='".(int)$data_user['id']."' AND DATE(a.`created_at`)=CURDATE() AND a.`status` IN ('issued','completed')) AS used_today FROM `traffic_tasks` t WHERE t.`status`=1 ORDER BY t.`id` DESC");
$history=$db->get_list("SELECT a.*,t.title FROM `traffic_attempts` a JOIN `traffic_tasks` t ON t.id=a.task_id WHERE a.user_id='".(int)$data_user['id']."' ORDER BY a.id DESC LIMIT 30");
?>
<link rel="stylesheet" href="/assets/css/traffic.css?v=<?= (int)(@filemtime(APP_ROOT.'/assets/css/traffic.css')?:1) ?>">
<nav class="earning-mobile-nav"><a href="/kiem-tien-online" class="active"><i class="fas fa-coins"></i> Traffic</a><a href="#traffic-history"><i class="fas fa-history"></i> Lịch sử</a><a href="/customer/balance"><i class="fas fa-wallet"></i> Số dư</a></nav>
<section class="screen traffic-page"><div class="center"><div class="traffic-layout"><main>
<div class="traffic-hero"><h1>🎯 Nhiệm vụ Traffic</h1><p>Nhấn nhận nhiệm vụ, hoàn thành các bước tại nhà cung cấp và quay về URL xác thực để nhận thưởng tự động.</p><ol><li>Nhấn Nhận nhiệm vụ</li><li>Hệ thống tạo phiên xác thực riêng cho tài khoản của bạn</li><li>Hoàn thành shortlink trên cùng trình duyệt và thiết bị</li><li>Nhà cung cấp chuyển về trang xác thực của hệ thống</li><li>Tiền thưởng được cộng tự động</li></ol></div>
<?php if(!empty($_SESSION['traffic_error'])):?><div class="alert alert-danger"><?=htmlspecialchars($_SESSION['traffic_error'],ENT_QUOTES);unset($_SESSION['traffic_error']);?></div><?php endif;?>
<?php if(!empty($_SESSION['traffic_success'])):?><div class="alert alert-success"><?=htmlspecialchars($_SESSION['traffic_success'],ENT_QUOTES);unset($_SESSION['traffic_success']);?></div><?php endif;?>
<div class="traffic-tasks">
<?php if (count($tasks) === 0): ?>
  <div class="traffic-empty-state">
    <i class="fas fa-inbox"></i>
    <h3>Hiện chưa có nhiệm vụ Traffic</h3>
    <p>API Link4m/LAYMA/Link2m chỉ dùng để rút gọn liên kết, không tự cung cấp danh sách nhiệm vụ. Admin cần tạo nhiệm vụ trong cPanel trước.</p>
    <?php if (is_admin_account($data_user)): ?><a href="/cpanel/traffic" class="traffic-start">Mở trang quản lý nhiệm vụ</a><?php endif; ?>
  </div>
<?php else: foreach ($tasks as $task): $used=(int)($task['used_today']??0); $max=max(1,(int)$task['max_per_day']); $remaining=max(0,$max-$used); ?>
  <article class="traffic-task"><div><h3><?=htmlspecialchars($task['title'],ENT_QUOTES)?></h3><p>Thưởng: <strong><?=format_cash($task['reward'])?>đ</strong></p><small>Giới hạn <?=$max?> lượt/ngày · Còn <?=$remaining?> lượt hôm nay</small></div><div class="traffic-actions"><?php if($remaining<=0):?><span class="task-approved">Đã hết lượt hôm nay</span><?php else:?><form method="post" action="/model/traffic/start"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="task_id" value="<?=$task['id']?>"><button class="traffic-start" type="submit">Nhận nhiệm vụ</button></form><?php endif;?></div></article>
<?php endforeach; endif; ?>
</div>
</main><aside><div class="traffic-guide"><h3>Hướng dẫn</h3><p>Phải dùng cùng tài khoản đăng nhập, cùng phiên trình duyệt, thiết bị và địa chỉ mạng từ lúc nhận đến lúc hoàn thành. Không chia sẻ link hoặc đổi VPN giữa chừng.</p></div></aside></div>
<div id="traffic-history" class="traffic-history"><h2>Lịch sử nhận nhiệm vụ</h2><table><tr><th>Nhiệm vụ</th><th>Trạng thái</th><th>Thưởng</th></tr><?php if(!$history):?><tr><td colspan="3" class="traffic-history-empty">Bạn chưa tham gia nhiệm vụ nào.</td></tr><?php else:foreach($history as $h):?><tr><td><?=htmlspecialchars($h['title'],ENT_QUOTES)?></td><td><?=htmlspecialchars($h['status'],ENT_QUOTES)?></td><td><?=format_cash($h['reward'])?>đ</td></tr><?php endforeach;endif;?></table></div>
</div></section>
<?php if (($_GET['traffic'] ?? '') === 'success'): ?>
<script>document.addEventListener('DOMContentLoaded',function(){if(typeof alertSuccess==='function'){alertSuccess('Hoàn thành nhiệm vụ','Tiền thưởng đã được cộng vào số dư');}history.replaceState({},document.title,'/kiem-tien-online');});</script>
<?php endif; require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php');?>
